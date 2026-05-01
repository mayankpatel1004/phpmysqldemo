<?php
function getFormFields($request) {
    try {

        $arrFields = [];

        // Default values
        $edit_id = 0;
        $edit_role_title = "";
        $edit_item_alias = "";
        $edit_display_order = "";
        $edit_display_status = "";
        $edit_created_by = 0;

        // EDIT MODE
        if (!empty($_GET['edit_id']) && $_GET['edit_id'] > 0) {
            $edit_id = $_GET['edit_id'];

            $results = getRoleById($edit_id);
            
            if ($results && count($results) > 0) {
                $row = $results[0];

                $edit_id = $row['role_id'];
                $edit_role_title = $row['role_title'];
                $edit_item_alias = $row['item_alias'];
                $edit_display_order = $row['display_order'];
                $edit_display_status = $row['display_status'];
                $edit_created_by = $row['created_by'];
            }
        }

        // Hidden Edit ID
        $arrFields[] = [
            "type" => "hidden",
            "lbl" => "Edit ID",
            "nm" => "edit_id",
            "val" => $edit_id,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        // Created At (only for new)
        if ($edit_id == 0) {
            $arrFields[] = [
                "type" => "hidden",
                "lbl" => "Created",
                "nm" => "created_at",
                "val" => date("Y-m-d H:i:s"),
                "req" => "N",
                "cls" => "form-control formfields"
            ];
        }

        // Main Fields
        $arrFields[] = [
            "type" => "text",
            "lbl" => "Role Name",
            "nm" => "role_title",
            "val" => $edit_role_title,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "hidden",
            "lbl" => "Item Alias",
            "nm" => "item_alias",
            "val" => $edit_item_alias,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "select",
            "lbl" => "Display Status",
            "nm" => "display_status",
            "val" => $edit_display_status,
            "req" => "N",
            "is_multiple" => "N",
            "options" => displayStatus(),
            "cls" => "form-control js-example-basic-single formfields"
        ];

        $arrFields[] = [
            "type" => "text",
            "lbl" => "Created By",
            "nm" => "created_by",
            "val" => $edit_created_by,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        // Meta Modules
        $metaRecords = getRoleMetaDetails();

        // Role Access
        $roleAccessRecords = getRoleAccess($edit_id);

        // Response Data
        $responseData = [
            "modules" => $metaRecords,
            "role_id" => 0,
            "fields" => $arrFields,
            "role_access" => $roleAccessRecords
        ];

        return $responseData;

    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => CONSTANTS::REQUEST_FAIL,
            "data" => [],
            "totalRecords" => 0
        ];
    }
}