<?php
function saveItemSection($data,$files) {
    global $pdo,$site_path;
    $now = date('Y-m-d H:i:s');
    $keys = [];
    $values = [];

    if (isset($files['attachment1']) && $files['attachment1']['error'] == 0) {
        $uploadDir = $site_path."public/uploads/";
        $tmp_file_name = $files['attachment1']['tmp_name'];
        $extension = pathinfo($files['attachment1']['name'], PATHINFO_EXTENSION);
        $newFileName = time() . "_" ."isec_". rand(1000, 9999) . "." . $extension;
        $targetFile = $uploadDir . $newFileName;
        if (move_uploaded_file($tmp_file_name, $targetFile)) {
            $data['attachment1'] = $newFileName;
        }
    }

    foreach ($data as $key => $value) {
        if ($key === "item_section_id") continue;
        $keys[] = $key;
        if (strpos($key, "_at") !== false && is_string($value)) {
            $values[] = date("Y-m-d H:i:s", strtotime($value));
        } else {
            $values[] = $value;
        }
    }

    if (!empty($data['item_section_id']) && (int)$data['item_section_id'] > 0) {
        $setParts = [];
        foreach ($keys as $k) {
            $setParts[] = "$k = ?";
        }

        $sql = "UPDATE item_section SET " . implode(", ", $setParts) . " WHERE item_section_id = ?";
        $stmt = $pdo->prepare($sql);
        $arrValues = [...$values, $data['item_section_id']];
        $stmt->execute($arrValues);
        logQuery($sql, $arrValues);
        $arr = array(
            "success" => 1,
            "message" => "Success",
            "data" => ["item_section_id" => $data['item_section_id']]
        );
        echo json_encode($arr);
    } else {
        try {
            $pdo->beginTransaction();
            $placeholders = implode(", ", array_fill(0, count($keys), "?"));
            $sql = "INSERT INTO item_section (" . implode(", ", $keys) . ") VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            logQuery($sql, $values);
            $insertedId = $pdo->lastInsertId();

            if ($insertedId > 0) {
                $section_alias = createAlias($data['section_title']);

                $checkSql = "SELECT section_alias FROM item_section WHERE section_alias = ?";
                $stmt = $pdo->prepare($checkSql);
                $stmt->execute([$section_alias]);
                $exists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($exists) > 0) {
                    $section_alias .= "-" . time();
                }

                $updateSql = "UPDATE item_section SET section_alias = ?,created_at = ? WHERE item_section_id = ?";
                $stmt = $pdo->prepare($updateSql);
                $arrValues = [$section_alias,$now, $insertedId];
                logQuery($updateSql, $arrValues);
                $stmt->execute($arrValues);

                $pdo->commit();

                $arr = array(
                    "success" => 1,
                    "message" => "Success",
                    "data" => array(
                        "item_section_id" => $insertedId,
                        "section_alias" => $section_alias
                    )
                );
                echo json_encode($arr);
            } else {
                throw new Exception("Insert failed, no ID returned.");
            }
        } catch (Exception $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $arr = array(
                "success" => 0,
                "message" => $e->getMessage()
            );

            echo json_encode($arr);
        }
    }
}


function saveItemForm($data, $files = [], $headers = []) {
    global $pdo,$site_path;
    $now = date('Y-m-d H:i:s');

    try {
        $pdo->beginTransaction();
        $keys = [];
        $values = [];

        if (isset($files['attachment1']) && $files['attachment1']['error'] == 0) {
            $uploadDir = $site_path."public/uploads/";
            $tmp_file_name = $files['attachment1']['tmp_name'];
            $extension = pathinfo($files['attachment1']['name'], PATHINFO_EXTENSION);
            $newFileName = time() . "_" ."item1_". rand(1000, 9999) . "." . $extension;
            $targetFile = $uploadDir . $newFileName;
            if (move_uploaded_file($tmp_file_name, $targetFile)) {
                $data['attachment1'] = $newFileName;
            }
        }

        if (isset($files['attachment2']) && $files['attachment2']['error'] == 0) {
            $uploadDir = $site_path."public/uploads/";
            $tmp_file_name = $files['attachment2']['tmp_name'];
            $extension = pathinfo($files['attachment2']['name'], PATHINFO_EXTENSION);
            $newFileName = time() . "_" ."item2_". rand(1000, 9999) . "." . $extension;
            $targetFile = $uploadDir . $newFileName;
            if (move_uploaded_file($tmp_file_name, $targetFile)) {
                $data['attachment2'] = $newFileName;
            }
        }

        foreach ($data as $key => $value) {
            if ($key === "item_id") continue;
            $keys[] = $key;
            if (strpos($key, "_at") !== false && is_string($value)) {
                $values[] = date("Y-m-d H:i:s", strtotime($value));
            } else {
                $values[] = $value;
            }
        }
        $itemId = $data['item_id'] ?? 0;

        if (!empty($itemId) && (int)$itemId > 0) {
            $setParts = [];
            foreach ($keys as $k) {
                $setParts[] = "$k = ?";
            }

            $sql = "UPDATE items SET " . implode(", ", $setParts) . " WHERE item_id = ?";
            $stmt = $pdo->prepare($sql);
            $arrValues1 = [...$values, $itemId];
            $stmt->execute($arrValues1);
            logQuery($sql, $arrValues1);

            if (!empty($data['item_sections_id'])) {
                $deleteSql = "DELETE FROM item_section_relation WHERE item_id = ?";
                $stmt = $pdo->prepare($deleteSql);
                $stmt->execute([$itemId]);

                $sections = array_filter(array_map('intval', explode(",", $data['item_sections_id'])));

                foreach ($sections as $sectionId) {
                    $insertSql = "INSERT INTO item_section_relation (item_id, section_id, created_at) VALUES (?, ?, ? )";
                    $stmt = $pdo->prepare($insertSql);
                    $arrValues2 = [$itemId, $sectionId, $now];
                    $stmt->execute($arrValues2);
                    logQuery($insertSql, $arrValues2);
                }
            }
        } else {

            $placeholders = implode(", ", array_fill(0, count($keys), "?"));
            $sql = "INSERT INTO items (" . implode(", ", $keys) . ") VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            logQuery($sql, $values);
            $itemId = $pdo->lastInsertId();

            if ($itemId > 0) {
                $item_alias = createAlias($data['item_title']);
                $checkSql = "SELECT item_alias FROM items WHERE item_alias = ?";
                $stmt = $pdo->prepare($checkSql);
                $stmt->execute([$item_alias]);
                $exists = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($exists) > 0) {
                    $item_alias .= "-" . time();
                }

                $updateSql = "UPDATE items SET item_alias = ?,created_at = ? WHERE item_id = ?";
                $stmt = $pdo->prepare($updateSql);
                $arrValues = [$item_alias,$now, $itemId];
                $stmt->execute($arrValues);
                logQuery($updateSql, $arrValues);

                if (!empty($data['item_sections_id'])) {
                    $sections = array_filter(array_map('intval', explode(",", $data['item_sections_id'])));

                    $deleteSql = "DELETE FROM item_section_relation WHERE item_id = ?";
                    $stmt = $pdo->prepare($deleteSql);
                    $stmt->execute([$itemId]);

                    foreach ($sections as $sectionId) {
                        $insertSql = "INSERT INTO item_section_relation (item_id, section_id,created_at) VALUES (?, ?, ?)";
                        $stmt = $pdo->prepare($insertSql);
                        $arrValues2 = [$itemId, $sectionId, $now];
                        $stmt->execute($arrValues2);
                        logQuery($insertSql, $arrValues2);
                    }
                }
                
            } else {
                //throw new Exception("Insert failed, no ID returned.");
                $arr = array(
                    "success" => 0,
                    "message" => "Insert failed, no ID returned.",
                    "data" => array()
                );
                echo json_encode($arr);
            }
        }
        $pdo->commit();
        $arr = array(
            "success" => 1,
            "message" => "Success",
            "data" => array(
                "item_id" => $itemId
            )
        );
        echo json_encode($arr);
    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $arr = array(
            "success" => 0,
            "message" => $e->getMessage(),
            "data" => array()
        );
        echo json_encode($arr);
    }
}


function saveRoleForm($data) {
    global $pdo;
    try {
        $roleId = $data['edit_id'] ?? 0;
        $excludeKeys = ["edit_id", "view", "add", "edit", "delete", "module_id"];

        $keys = [];
        $values = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $excludeKeys)) continue;
            $keys[] = $key;
            if (strpos($key, "_at") !== false && is_string($value)) {
                $values[] = date("Y-m-d H:i:s", strtotime($value));
            } else {
                $values[] = $value;
            }
        }

        if (!empty($roleId) && (int)$roleId > 0) {
            $setParts = [];
            foreach ($keys as $k) {
                $setParts[] = "$k = ?";
            }
            $sql = "UPDATE role SET " . implode(", ", $setParts) . " WHERE role_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([...$values, $roleId]);

            $deleteSql = "DELETE FROM role_access WHERE role_id = ?";
            $stmt = $pdo->prepare($deleteSql);
            $stmt->execute([$roleId]);

        } else {
            $placeholders = implode(", ", array_fill(0, count($keys), "?"));
            $sql = "INSERT INTO role (" . implode(", ", $keys) . ") VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);
            $roleId = $pdo->lastInsertId();
        }
        //echo "Role ID: " . $roleId; // Debugging line to check role ID
        

        if (
            isset($data['module_id'], $data['view']) &&
            is_array($data['module_id']) &&
            is_array($data['view']) &&
            count($data['module_id']) === count($data['view'])
        ) {

            $insertSql = "INSERT INTO role_access (role_id, module_id, grant_view, grant_add, grant_edit, grant_delete, display_status, display_order, created_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($insertSql);

            for ($i = 0; $i < count($data['module_id']); $i++) {
                $moduleId = (int)$data['module_id'][$i];
                if ($moduleId <= 0) continue;

                $grantView   = ($data['view'][$i] ?? "0") === "1" ? "Y" : "N";
                $grantAdd    = ($data['add'][$i] ?? "0") === "1" ? "Y" : "N";
                $grantEdit   = ($data['edit'][$i] ?? "0") === "1" ? "Y" : "N";
                $grantDelete = ($data['delete'][$i] ?? "0") === "1" ? "Y" : "N";

                $displayStatus = $data['display_status'] ?? "Y";
                $displayOrder  = 0;
                $createdAt     = date("Y-m-d H:i:s");

                $stmt->execute([
                    $roleId,
                    $moduleId,
                    $grantView,
                    $grantAdd,
                    $grantEdit,
                    $grantDelete,
                    $displayStatus,
                    $displayOrder,
                    $createdAt
                ]);
            }
        }
        
        $arr = array(
            "success" => 1,
            "message" => "Success",
            "data" => array(
                "role_id" => $roleId
            )
        );
        echo json_encode($arr);
    } catch (Exception $e) {
        $arr = array(
            "success" => 0,
            "message" => $e->getMessage(),
            "data" => array()
        );
        echo json_encode($arr);
    }
}

function saveUserForm($data,$files){
    global $pdo,$site_path;
    if (isset($files['user_photo']) && $files['user_photo']['error'] == 0) {
        $uploadDir = $site_path."public/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $tmp_file_name = $files['user_photo']['tmp_name'];
        $extension = pathinfo($files['user_photo']['name'], PATHINFO_EXTENSION);
        $newFileName = time() . "_" ."user_". rand(1000, 9999) . "." . $extension;
        $targetFile = $uploadDir . $newFileName;
        if (move_uploaded_file($tmp_file_name, $targetFile)) {
            $data['user_photo'] = $newFileName;
        }
    }
    try {
        if ((empty($data['edit_id']) || (int)$data['edit_id'] === 0) && defined('USER_EMAIL_UNIQUE') && USER_EMAIL_UNIQUE === "Y") {
            $sqlCheck = "SELECT user_id FROM users WHERE user_email = ? LIMIT 1";
            $stmt = $pdo->prepare($sqlCheck);
            $stmt->execute([$data['user_email']]);
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($exists) {
                $arr = array(
                    "success" => 0,
                    "message" => "Email already exists",
                    "data" => array()
                );
                echo json_encode($arr);
            }
        }

        $keys = [];
        $values = [];
        foreach ($data as $key => $value) {
            if ($key === "edit_id") continue;
            $keys[] = $key;
            if (strpos($key, "_at") !== false && is_string($value)) {
                $values[] = date("Y-m-d H:i:s", strtotime($value));
            } else {
                $values[] = $value;
            }
        }

        if (!empty($data['edit_id']) && (int)$data['edit_id'] > 0) {
            $userId = $data['edit_id'];
            $setParts = [];
            foreach ($keys as $k) {
                $setParts[] = "$k = ?";
            }

            $sql = "UPDATE users SET " . implode(", ", $setParts) . " WHERE user_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([...$values, $userId]);

            // if ($stmt->rowCount() === 0) {
            //     $arr = array(
            //         "success" => 0,
            //         "message" => "Data not found",
            //         "data" => array()
            //     );
            //     echo json_encode($arr);
            // }

            $arr = array(
                "success" => 1,
                "message" => "Success",
                "data" => array("user_id" => $userId)
            );
            echo json_encode($arr);
    } else {
        $placeholders = implode(", ", array_fill(0, count($keys), "?"));
        $sql = "INSERT INTO users (" . implode(", ", $keys) . ") VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $userId = $pdo->lastInsertId();
        if (!empty($data['user_email'])) {
            $to = $data['user_email'];
            $subject = 'Account Created';
            $body = ($data['user_firstname'] ?? '') . ",<br>Your account has been created successfully.";
            $emailResponse = sendMail($to,$subject,$body);
            if (!$emailResponse['success']) {
                error_log("Email Error: " . $emailResponse['message']);
            }
        }

        if ($userId > 0) {
            $arr = array(
                "success" => 1,
                "message" => "Success",
                "data" => array("user_id" => $userId)
            );
            echo json_encode($arr);
        } else {
            $arr = array(
                "success" => 0,
                "message" => "Fail to insert data",
                "data" => array()
            );
            echo json_encode($arr);
        }
    }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $arr = array(
                "success" => 0,
                "message" => "Email already exists",
                "data" => array()
            );
            echo json_encode($arr);
        }
        $arr = array(
            "success" => 0,
            "message" => $e->getMessage(),
            "data" => array()
        );
        echo json_encode($arr);
    }
}