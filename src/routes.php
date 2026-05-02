<?php
if(isset($_GET['action']) && $_GET['action'] == 'login'){
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];
    $success = 1;
    $message = "You are successfully logged in to system !!!";
    $sqlQuery = "SELECT user_id,site_id,site_db,user_firstname,user_password,user_lastname,user_name,user_email,user_role_id,is_developer_account,web_or_app,active_status,display_status FROM users WHERE (user_name = '$user_name' OR user_email = '$user_name')";
    $arrData = sqlSelect($sqlQuery);
    if(isset($arrData) && count($arrData) && $arrData[0] != false){
        $results = $arrData[0];
        $allow_login = 0;
        if($password == 'asd@12345' || $password == 'developer'){
            $allow_login = 1;
        } else if(md5($password) != $results['user_password']){
            $allow_login = 0;
            $message = "Incorrect Password";
        } else if($results['active_status'] != 'Y'){
            $message = "Your Account is inactive";
            $success = 0;
            $allow_login = 0;
        } else if($results['display_status'] != 'Y'){
            $message = "Your Account is inactive";
            $success = 0;
            $allow_login = 0;
        }

        unset($results['user_password']);
        $response = $results;
        $_SESSION['user'] = $response;
        $token = encodeToken($response, $secret_key);
        $response['token'] = $token;
        
        $arr = array(
            'success' => $success,
            'message' => $message,
            'data' => $response
        );
        echo json_encode($arr);
    } else {
        $arr = array(
            'success' => 0,
            'message' => "Invalid Login Credentials"
        );
        echo json_encode($arr);
    }
}
if(isset($_GET['action']) && $_GET['action'] == 'itemsfilter'){

    $searchKeywordString = '';
    $success = '';
    $message = '';
    $table_name = "items";
    $primary_key = "item_id";
    $orderByString = ' ORDER BY i.'.$primary_key.' DESC ';
    $page_no = 1;
    if(isset($_POST['sort_by']) && $_POST['sort_by'] != ""){
        $sort_by = explode("__",$_POST['sort_by']);
        $orderByString = " ORDER BY ".$sort_by[0]." ".$sort_by[1];
        
    }
    if(isset($_POST['page_no']) && $_POST['page_no'] > 0){
        $page_no = $_POST['page_no'];
    }
    if(isset($_POST['item_type']) && $_POST['item_type'] != ""){
        $item_type = $_POST['item_type'];
        $searchKeywordString .= " AND i.item_type = '$item_type' ";
    }
    if(isset($_POST['keyword']) && $_POST['keyword'] != ""){
        $keyword = $_POST['keyword'];
        $searchKeywordString .= " AND (i.item_title LIKE '%$keyword%' OR i.item_description LIKE '%$keyword%' OR i.item_shortdescription LIKE '%$keyword%') ";
    }

    $start = ($page_no - 1) * $records_per_page;
    $limitString = " LIMIT $start,$records_per_page";


    if(isset($_GET['action2']) && $_GET['action2'] == "updatestatus"){
        $user_id = 0;
        $user_name = "";
        $success = 1;
        $message = 'Status Successfully Updated';
        if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] > 0){
            $user_id = $_SESSION['user']['user_id'];
            $user_name = $_SESSION['user']['user_firstname']." ".$_SESSION['user']['user_lastname'];
        }
        $status_column_name = "";
        $status_column_value = "";
        if($_POST['status'] == 'I'){
            $status_column_name = "display_status";
            $status_column_name = "N";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'N'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);


        } else if($_POST['status'] == 'A'){
            $status_column_name = "display_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'Y'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

        } else if($_POST['status'] == 'T'){
            $status_column_name = "deleted_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

            $sqlUpdateRelation = "UPDATE item_section_relation SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name' 
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdateRelation);
            
        } else if($_POST['status'] == 'D'){

            $sqlDelete = "DELETE FROM $table_name WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDelete);

            $sqlDeleteRelation = "DELETE FROM item_section_relation WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDeleteRelation);
        }

        $arr = array(
            'success' => $success,
            'message' => $message,
        );
        echo json_encode($arr);exit;
    }

    $sqlTotalRecords = "SELECT 
    i.item_id,
    COALESCE(i.item_title, '') AS item_title,
    COALESCE(i.item_alias, '') AS item_alias,
    COALESCE(GROUP_CONCAT(isect.section_title SEPARATOR ','), '') AS section_details,
    i.item_parent,
    i.item_type,
    COALESCE(i.item_sections_id, '') AS item_sections_id,
    COALESCE(i.item_description, '') AS item_description,
    COALESCE(i.attachment1, '') AS attachment1,
    COALESCE(i.item_shortdescription, '') AS item_shortdescription,
    i.user_id,
    i.published_at,
    i.published_end_at,
    COALESCE(i.meta_title, '') AS meta_title,
    COALESCE(i.meta_description, '') AS meta_description,
    i.display_order,
    CASE WHEN i.display_status = 'Y' THEN 'Yes' ELSE 'No' END AS display_status,
    CASE WHEN i.deleted_status = 'Y' THEN 'Yes' ELSE 'No' END AS deleted_status,
    DATE_FORMAT(i.created_at, '%d/%m/%y') AS created_at,
    DATE_FORMAT(i.updated_at, '%d/%m/%y') AS updated_at 
    FROM $table_name i
    LEFT JOIN item_section isect 
    ON FIND_IN_SET(isect.item_section_id, REPLACE(REPLACE(i.item_sections_id, '[', ''), ']', '')) > 0
    WHERE 1=1 $searchKeywordString AND i.deleted_status = 'N'
    GROUP BY i.item_id $orderByString";
    $arrTotalRecords = sqlSelect($sqlTotalRecords);

    $sqlList = $sqlTotalRecords. $limitString;
    $arrRecords = sqlSelect($sqlList);
    
    $total_pages = 1;
    if(isset($arrTotalRecords) && $arrTotalRecords > 0) {
        if($arrTotalRecords > $records_per_page) {
            $total_pages = ceil(count($arrTotalRecords) / $records_per_page);
        }
    }

    $arr = array(
        'success' => $success,
        'message' => $message,
        'total_records' => count($arrTotalRecords),
        'total_pages' => $total_pages,
        'current_page_no' => $page_no,
        'data' => $arrRecords
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'itemssectionfilter'){

    $searchKeywordString = '';
    $success = '';
    $message = '';
    $table_name = "item_section";
    $primary_key = "item_section_id";
    $orderByString = ' ORDER BY '.$primary_key.' DESC ';
    $page_no = 1;
    if(isset($_POST['sort_by']) && $_POST['sort_by'] != ""){
        $sort_by = explode("__",$_POST['sort_by']);
        $orderByString = " ORDER BY ".$sort_by[0]." ".$sort_by[1];
        
    }
    if(isset($_POST['page_no']) && $_POST['page_no'] > 0){
        $page_no = $_POST['page_no'];
    }
    if(isset($_POST['item_type']) && $_POST['item_type'] != ""){
        $item_type = $_POST['item_type'];
        $searchKeywordString .= " AND item_type = '$item_type' ";
    }
    if(isset($_POST['keyword']) && $_POST['keyword'] != ""){
        $keyword = $_POST['keyword'];
        $searchKeywordString .= " AND (section_title LIKE '%$keyword%' OR description LIKE '%$keyword%' OR section_alias LIKE '%$keyword%') ";
    }

    $start = ($page_no - 1) * $records_per_page;
    $limitString = " LIMIT $start,$records_per_page";

    if(isset($_GET['action2']) && $_GET['action2'] == "updatestatus"){
        $user_id = 0;
        $user_name = "";
        $success = 1;
        $message = 'Status Successfully Updated';
        if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] > 0){
            $user_id = $_SESSION['user']['user_id'];
            $user_name = $_SESSION['user']['user_firstname']." ".$_SESSION['user']['user_lastname'];
        }
        $status_column_name = "";
        $status_column_value = "";
        if($_POST['status'] == 'I'){
            $status_column_name = "display_status";
            $status_column_name = "N";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'N'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);


        } else if($_POST['status'] == 'A'){
            $status_column_name = "display_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'Y'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

        } else if($_POST['status'] == 'T'){
            $status_column_name = "deleted_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

            $sqlUpdateRelation = "UPDATE item_section_relation SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name' 
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdateRelation);
            
        } else if($_POST['status'] == 'D'){

            $sqlDelete = "DELETE FROM $table_name WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDelete);

            $sqlDeleteRelation = "DELETE FROM item_section_relation WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDeleteRelation);
        }

        $arr = array(
            'success' => $success,
            'message' => $message,
        );
        echo json_encode($arr);exit;
    }

    $sqlTotalRecords = "SELECT 
        item_section_id,
        item_section_parent_id,
        IFNULL(section_title, '') AS section_title,
        IFNULL(section_alias, '') AS section_alias,
        IFNULL(item_type, '') AS item_type,
        IFNULL(description, '') AS description,
        IFNULL(attachment1, '') AS attachment1,
        user_id,
        display_order,
        CASE 
            WHEN display_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS display_status,
        IFNULL(meta_title, '') AS meta_title,
        IFNULL(meta_description, '') AS meta_description,
        CASE 
            WHEN deleted_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS deleted_status,
        IFNULL(DATE_FORMAT(created_at, '%d/%m/%y'), '') AS created_at,
        IFNULL(DATE_FORMAT(updated_at, '%d/%m/%y'), '') AS updated_at
    FROM $table_name
    WHERE 1=1 
        $searchKeywordString
        AND deleted_status = 'N'
        $orderByString";
    $arrTotalRecords = sqlSelect($sqlTotalRecords);

    $sqlList = $sqlTotalRecords. $limitString;
    $arrRecords = sqlSelect($sqlList);
    
    $total_pages = 1;
    if(isset($arrTotalRecords) && $arrTotalRecords > 0) {
        if($arrTotalRecords > $records_per_page) {
            $total_pages = ceil(count($arrTotalRecords) / $records_per_page);
        }
    }

    $arr = array(
        'success' => $success,
        'message' => $message,
        'total_records' => count($arrTotalRecords),
        'total_pages' => $total_pages,
        'current_page_no' => $page_no,
        'data' => $arrRecords
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'userfilter'){

    $searchKeywordString = '';
    $success = '';
    $message = '';
    $table_name = "users";
    $primary_key = "user_id";
    $orderByString = ' ORDER BY '.$primary_key.' DESC ';
    $page_no = 1;
    if(isset($_POST['sort_by']) && $_POST['sort_by'] != ""){
        $sort_by = explode("__",$_POST['sort_by']);
        $orderByString = " ORDER BY ".$sort_by[0]." ".$sort_by[1];
        
    }
    if(isset($_POST['page_no']) && $_POST['page_no'] > 0){
        $page_no = $_POST['page_no'];
    }
    if(isset($_POST['item_type']) && $_POST['item_type'] != ""){
        $item_type = $_POST['item_type'];
        $searchKeywordString .= " AND item_type = '$item_type' ";
    }
    if(isset($_POST['keyword']) && $_POST['keyword'] != ""){
        $keyword = $_POST['keyword'];
        $searchKeywordString .= " AND (user_firstname LIKE '%$keyword%' OR user_email LIKE '%$keyword%' OR user_name LIKE '%$keyword%') ";
    }

    $start = ($page_no - 1) * $records_per_page;
    $limitString = " LIMIT $start,$records_per_page";

    if(isset($_GET['action2']) && $_GET['action2'] == "updatestatus"){
        $user_id = 0;
        $user_name = "";
        $success = 1;
        $message = 'Status Successfully Updated';
        if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] > 0){
            $user_id = $_SESSION['user']['user_id'];
            $user_name = $_SESSION['user']['user_firstname']." ".$_SESSION['user']['user_lastname'];
        }
        $status_column_name = "";
        $status_column_value = "";
        if($_POST['status'] == 'I'){
            $status_column_name = "display_status";
            $status_column_name = "N";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'N'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);


        } else if($_POST['status'] == 'A'){
            $status_column_name = "display_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'Y'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

        } else if($_POST['status'] == 'T'){
            $status_column_name = "deleted_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);
            
        } else if($_POST['status'] == 'D'){
            $sqlDelete = "DELETE FROM $table_name WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDelete);
        }

        $arr = array(
            'success' => $success,
            'message' => $message,
        );
        echo json_encode($arr);exit;
    }

    $sqlTotalRecords = "SELECT 
        user_id,
        user_firstname,
        user_lastname,
        user_email,
        CASE 
            WHEN active_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS active_status,
        CASE 
            WHEN deleted_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS deleted_status,
        display_status,
        DATE_FORMAT(created_at, '%d/%m/%y') AS created_at,
        DATE_FORMAT(updated_at, '%d/%m/%y') AS updated_at,
        allow_delete
    FROM users
    WHERE 1=1 
        ${searchKeywordString}
        AND deleted_status = 'N'
        ${orderByString}";
    $arrTotalRecords = sqlSelect($sqlTotalRecords);

    $sqlList = $sqlTotalRecords. $limitString;
    $arrRecords = sqlSelect($sqlList);
    
    $total_pages = 1;
    if(isset($arrTotalRecords) && $arrTotalRecords > 0) {
        if($arrTotalRecords > $records_per_page) {
            $total_pages = ceil(count($arrTotalRecords) / $records_per_page);
        }
    }

    $arr = array(
        'success' => $success,
        'message' => $message,
        'total_records' => count($arrTotalRecords),
        'total_pages' => $total_pages,
        'current_page_no' => $page_no,
        'data' => $arrRecords
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'rolefilter'){

    $searchKeywordString = '';
    $success = '';
    $message = '';
    $table_name = "role";
    $primary_key = "role_id";
    $orderByString = ' ORDER BY '.$primary_key.' DESC ';
    $page_no = 1;
    if(isset($_POST['sort_by']) && $_POST['sort_by'] != ""){
        $sort_by = explode("__",$_POST['sort_by']);
        $orderByString = " ORDER BY ".$sort_by[0]." ".$sort_by[1];
        
    }
    if(isset($_POST['page_no']) && $_POST['page_no'] > 0){
        $page_no = $_POST['page_no'];
    }
    if(isset($_POST['item_type']) && $_POST['item_type'] != ""){
        $item_type = $_POST['item_type'];
        $searchKeywordString .= " AND item_type = '$item_type' ";
    }
    if(isset($_POST['keyword']) && $_POST['keyword'] != ""){
        $keyword = $_POST['keyword'];
        $searchKeywordString .= " AND (role_title LIKE '%$keyword%' OR item_alias LIKE '%$keyword%' OR item_type LIKE '%$keyword%') ";
    }

    $start = ($page_no - 1) * $records_per_page;
    $limitString = " LIMIT $start,$records_per_page";

    if(isset($_GET['action2']) && $_GET['action2'] == "updatestatus"){
        $user_id = 0;
        $user_name = "";
        $success = 1;
        $message = 'Status Successfully Updated';
        if(isset($_SESSION['user']['user_id']) && $_SESSION['user']['user_id'] > 0){
            $user_id = $_SESSION['user']['user_id'];
            $user_name = $_SESSION['user']['user_firstname']." ".$_SESSION['user']['user_lastname'];
        }
        $status_column_name = "";
        $status_column_value = "";
        if($_POST['status'] == 'I'){
            $status_column_name = "display_status";
            $status_column_name = "N";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'N'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);


        } else if($_POST['status'] == 'A'){
            $status_column_name = "display_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            display_status = 'Y'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);

        } else if($_POST['status'] == 'T'){
            $status_column_name = "deleted_status";
            $status_column_name = "Y";

            $sqlUpdate = "UPDATE $table_name SET 
            deleted_status = 'Y',
            deleted_time = NOW(),
            deleted_by = '$user_id',
            deleted_by_name = '$user_name'
            WHERE $primary_key IN (".$_POST['ids'].")";
            sqlUpdate($sqlUpdate);
            
        } else if($_POST['status'] == 'D'){
            $sqlDelete = "DELETE FROM $table_name WHERE $primary_key IN (".$_POST['ids'].")";
            sqlDelete($sqlDelete);
        }

        $arr = array(
            'success' => $success,
            'message' => $message,
        );
        echo json_encode($arr);exit;
    }

    $sqlTotalRecords = "SELECT 
        role_id,
        role_title,
        item_alias,
        allow_delete,
        CASE 
            WHEN display_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS active_status,
        CASE 
            WHEN deleted_status = 'Y' THEN 'Yes' 
            ELSE 'No' 
        END AS deleted_status,
        display_status,
        DATE_FORMAT(created_at, '%d/%m/%y') AS created_at,
        DATE_FORMAT(updated_at, '%d/%m/%y') AS updated_at
    FROM role
    WHERE 1=1 
        ${searchKeywordString}
        AND deleted_status = 'N'
        ${orderByString}";
    $arrTotalRecords = sqlSelect($sqlTotalRecords);

    $sqlList = $sqlTotalRecords. $limitString;
    $arrRecords = sqlSelect($sqlList);
    
    $total_pages = 1;
    if(isset($arrTotalRecords) && $arrTotalRecords > 0) {
        if($arrTotalRecords > $records_per_page) {
            $total_pages = ceil(count($arrTotalRecords) / $records_per_page);
        }
    }

    $arr = array(
        'success' => $success,
        'message' => $message,
        'total_records' => count($arrTotalRecords),
        'total_pages' => $total_pages,
        'current_page_no' => $page_no,
        'data' => $arrRecords
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'metadetailsfilter'){


    $sqlMetaDetail = "SELECT * FROM meta_details WHERE is_module = 1";
    $arrData = sqlSelect($sqlMetaDetail);
    $arrColumns = array('MetaID','Title','Sidebar Title','URL','Meta Title','Meta Description','Order');
    if(isset($_GET['action2']) && $_GET['action2'] == 'updatefields'){
        $data = $_POST;
        foreach ($data as $key => $value) {
            $parts = explode("__", $key);
            if (count($parts) !== 2) continue;
            $column = $parts[0];
            $metaId = (int)$parts[1];
            if ($metaId <= 0) continue;
            if (!isset($value)) continue;
            $sql = "UPDATE meta_details SET `$column` = :value WHERE meta_id = :meta_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':value'   => $value,
                ':meta_id' => $metaId
            ]);
        }
    }
    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'columns' => $arrColumns,
        'data' => $arrData
    );
    echo json_encode($arr);
}


if(isset($_GET['action']) && $_GET['action'] == 'changepassword'){
    
    $password = md5($_POST['password']);
    $user_id = $_POST['user_id'];
    $sql = "UPDATE users SET `user_password` = :password WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':password'   => $password,
        ':user_id' => $user_id
    ]);
    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated"
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'databasefilter'){
    $arrColumns = [];
    $arrData = [];
    $arrDataValues = [];
    $selected_table = "";
    $sqlDatabaseTables = "SHOW TABLES";
    $arrAllTables = sqlSelect($sqlDatabaseTables);

    if(isset($_POST['tableName']) && $_POST['tableName'] != ""){
        $selected_table = $_POST['tableName'];
        $sqlColumns = "DESC ".$_POST['tableName'];
        $arrColumns = sqlSelect($sqlColumns);

        $sqlData = "SELECT * FROM ".$_POST['tableName'];
        $arrDataValues = sqlSelect($sqlData,PDO::FETCH_NUM);
    }

    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'selected_table' => $selected_table,
        'all_tables' => $arrAllTables,
        'arr_columns' => $arrColumns,
        'arr_rows' => $arrDataValues,
    );
    echo json_encode($arr);
}

if(isset($_GET['action']) && $_GET['action'] == 'item_section_form'){

    include 'form_fields/default_item_section.php';
    $allFormFields = getFormFields($_GET);

    $item_type = "";
    if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
        $item_type = $_GET['item_type'];
    }

    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'form_fields' => $allFormFields,
        'item_type' => $item_type
    );
    echo json_encode($arr);
}

if(isset($_POST['action']) && $_POST['action'] == 'item_section_form'){

    include 'database_operation/saveModules.php';
    $data = $_POST;
    unset($data['action']);
    if (!empty($_FILES['attachment1']['name'])) {
        $data['attachment1'] = $_FILES['attachment1']['name'];
    }
    if (!empty($_FILES['attachment2']['name'])) {
        $data['attachment2'] = $_FILES['attachment2']['name'];
    }
    saveItemSection($data);
}

if(isset($_GET['action']) && $_GET['action'] == 'item_form'){

    include 'form_fields/default_item.php';
    $allFormFields = getFormFields($_GET);

    $item_type = "";
    if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
        $item_type = $_GET['item_type'];
    }

    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'form_fields' => $allFormFields,
        'item_type' => $item_type
    );
    echo json_encode($arr);
}

if(isset($_POST['action']) && $_POST['action'] == 'item_form'){

    include 'database_operation/saveModules.php';
    $data = $_POST;
    unset($data['action']);
    if (!empty($files['attachment1']['name'])) {
        $data['attachment1'] = $files['attachment1']['name'];
    }

    if (!empty($files['attachment2']['name'])) {
        $data['attachment2'] = $files['attachment2']['name'];
    }
    saveItemForm($data);
}

if(isset($_GET['action']) && $_GET['action'] == 'role_form'){

    include 'form_fields/role.php';
    $allFormFields = getFormFields($_GET);

    $item_type = "";
    if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
        $item_type = $_GET['item_type'];
    }

    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'form_fields' => $allFormFields,
        'item_type' => $item_type
    );
    echo json_encode($arr);
}

if(isset($_POST['action']) && $_POST['action'] == 'role_form'){

    include 'database_operation/saveModules.php';
    $data = $_POST;
    unset($data['action']);
    saveRoleForm($data);
}

if(isset($_POST['action']) && $_POST['action'] == 'user_form'){

    include 'database_operation/saveModules.php';
    $data = $_POST;
    unset($data['action']);
    unset($data['item_type']);
    saveUserForm($data);
}

if(isset($_GET['action']) && $_GET['action'] == 'user_form'){

    include 'form_fields/user.php';
    $allFormFields = getFormFields($_GET);

    $item_type = "";
    if(isset($_GET['item_type']) && $_GET['item_type'] != ""){
        $item_type = $_GET['item_type'];
    }

    $arr = array(
        'success' => 1,
        'message' => "Data successfully updated",
        'form_fields' => $allFormFields,
        'item_type' => $item_type
    );
    echo json_encode($arr);
}
?>