<?php
function getFormFields($request)
{
    try {
        $user_id = 0;
        $arrFields = [];
        
        $item_section_id = 0;
        $section_title = "";
        $section_alias = "";
        $item_type = $request['item_type'] ?? "page";
        $description = "";
        $attachment1 = "";
        $display_order = 0;
        $display_status = "";
        $meta_title = "";
        $meta_description = "";
        $created_at = "";
        
        if (!empty($request['edit_id']) && $request['edit_id'] > 0) {
            $item_section_id = $request['edit_id'];

            $sqlQuery = "SELECT * FROM item_section WHERE item_section_id = $item_section_id";
            $row = sqlSelect($sqlQuery);
            
            if ($row) {
                $row = $row[0];
                $item_section_id = $row['item_section_id'];
                $section_title = $row['section_title'];
                $section_alias = $row['section_alias'];
                $item_type = $row['item_type'];
                $description = $row['description'];
                $attachment1 = $row['attachment1'];
                $user_id = $row['user_id'];
                $display_order = $row['display_order'];
                $display_status = $row['display_status'];
                $meta_title = $row['meta_title'];
                $meta_description = $row['meta_description'];
                $created_at = $row['created_at'];
            }
        }

        // Fields array
        $arrFields[] = [
            "type" => "text",
            "lbl" => "Title",
            "nm" => "section_title",
            "val" => $section_title,
            "ph" => "",
            "req" => "Y",
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ["default", "blog"]) ? "text" : "hidden",
            "lbl" => "Item Description",
            "nm" => "description",
            "val" => $description,
            "req" => "N",
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => $item_type === "default" ? "file" : "hidden",
            "lbl" => "Attachment",
            "nm" => "attachment1",
            "val" => $attachment1,
            "req" => "N",
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => $item_type === "default" ? "text" : "hidden",
            "lbl" => "UserID",
            "nm" => "user_id",
            "val" => !empty($user_id) ? $user_id : 0,
            "req" => "N",
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => $item_type === "default" ? "text" : "hidden",
            "lbl" => "Sort Order",
            "nm" => "display_order",
            "val" => $display_order,
            "req" => "N",
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => $item_type === "default" ? "select" : "hidden",
            "lbl" => "Item Section Type",
            "nm" => "item_type",
            "val" => $item_type,
            "options" => itemSectionTypes(),
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ["default", "blog"]) ? "select" : "hidden",
            "lbl" => "Status",
            "nm" => "display_status",
            "val" => $display_status,
            "options" => displayStatus(),
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ["default", "blog"]) ? "text" : "hidden",
            "lbl" => "Meta Title",
            "nm" => "meta_title",
            "val" => $meta_title,
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ["default", "blog"]) ? "text" : "hidden",
            "lbl" => "Meta Description",
            "nm" => "meta_description",
            "val" => $meta_description,
            "cls" => "form-control formfields",
        ];

        $arrFields[] = [
            "type" => "hidden",
            "nm" => "item_section_id",
            "val" => $item_section_id,
        ];

        if ($item_section_id == 0) {
            $arrFields[] = [
                "type" => "hidden",
                "nm" => "created_at",
                "val" => date("Y-m-d H:i:s"),
            ];
        }

        // API Response
        return [
            "status" => true,
            "fields" => $arrFields,
        ];

    } catch (Exception $e) {
        return [
            "status" => false,
            "message" => "Internal Server Error",
            "error" => $e->getMessage()
        ];
    }
}