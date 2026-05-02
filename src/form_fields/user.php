<?php
function getFormFields($request) {
    try {
        
        $arrFields = [];

        // Default values
        $edit_id = 0;
        $edit_firstname = "";
        $edit_lastname = "";
        $edit_email = "";
        $edit_active_status = "Y";
        $edit_role_id = 0;
        $edit_site_db = getenv('DB_NAME');
        $readonly = "";
        $user_photo = "";

        // EDIT MODE
        if (!empty($_GET['edit_id']) && $_GET['edit_id'] > 0) {
            $edit_id = $_GET['edit_id'];

            $sql = "SELECT * FROM users WHERE user_id = $edit_id";
            $row = sqlSelect($sql);

            if ($row && count($row) > 0) {
                $row = $row[0];

                $edit_id = $row['user_id'];
                $edit_firstname = $row['user_firstname'];
                $edit_lastname = $row['user_lastname'];
                $edit_email = $row['user_email'];
                $edit_active_status = $row['active_status'];
                $edit_role_id = $row['user_role_id'];
                $user_photo = $row['user_photo'];
                $edit_site_db = $row['site_db'];
                $readonly = "readonly";
            }
        }

        // Hidden Edit ID
        $arrFields[] = [
            "type" => "text",
            "lbl" => "Edit ID",
            "nm" => "edit_id",
            "val" => $edit_id,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        // Created At (only new)
        if ($edit_id == 0) {
            $arrFields[] = [
                "type" => "text",
                "lbl" => "Created",
                "nm" => "created_at",
                "val" => date("Y-m-d H:i:s"),
                "req" => "N",
                "cls" => "form-control formfields"
            ];
        }

        // Fields
        $arrFields[] = [
            "type" => "text",
            "lbl" => "Database Name",
            "nm" => "site_db",
            "val" => $edit_site_db,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "text",
            "lbl" => "First Name",
            "nm" => "user_firstname",
            "val" => $edit_firstname,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "text",
            "lbl" => "Last Name",
            "nm" => "user_lastname",
            "val" => $edit_lastname,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "file",
            "lbl" => "Photo",
            "nm" => "user_photo",
            "val" => $user_photo,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "email",
            "lbl" => "Email",
            "nm" => "user_email",
            "val" => $edit_email,
            "req" => "Y",
            "cls" => "form-control formfields " . $readonly
        ];

        $arrFields[] = [
            "type" => "select",
            "lbl" => "Active Status",
            "nm" => "active_status",
            "val" => $edit_active_status,
            "req" => "N",
            "is_multiple" => "N",
            "options" => displayStatus(),
            "cls" => "form-control js-example-basic-single formfields"
        ];

        $arrFields[] = [
            "type" => "select",
            "lbl" => "Role",
            "nm" => "user_role_id",
            "val" => $edit_role_id,
            "req" => "Y",
            "is_multiple" => "N",
            "options" => getAllRoles(),
            "cls" => "form-control js-example-basic-single formfields"
        ];

        // Response Data
        $responseData = [
            "fields" => $arrFields
        ];

        return $responseData;

    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}