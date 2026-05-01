function item_form($req, $db) {
    try {
        
        $arrFields = [];

        // Default values
        $item_id = 0;
        $edit_id = 0;
        $item_title = "";
        $item_alias = "";
        $item_parent = 0;
        $item_type = $_GET['item_type'] ?? "";
        $item_sections_id = "";
        $item_description = "";
        $attachment1 = "";
        $attachment2 = "";
        $item_shortdescription = "";
        $user_id = 0;
        $controller = "";
        $action = "";
        $published_at = date("Y-m-d");
        $published_end_at = date("Y-m-d", strtotime("+5 years"));
        $meta_title = "";
        $meta_description = "";
        $display_order = "";
        $display_status = "";

        // EDIT MODE
        if (!empty($_GET['edit_id']) && $_GET['edit_id'] > 0) {
            $edit_id = $_GET['edit_id'];

            $stmt = $db->prepare("SELECT * FROM items WHERE item_id = ?");
            $stmt->execute([$edit_id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $item_id = $row['item_id'];
                $item_title = $row['item_title'];
                $item_alias = $row['item_alias'];
                $item_parent = $row['item_parent'];
                $item_type = $row['item_type'];
                $item_sections_id = $row['item_sections_id'];
                $item_description = $row['item_description'];
                $attachment1 = $row['attachment1'];
                $attachment2 = $row['attachment2'];
                $item_shortdescription = $row['item_shortdescription'];
                $user_id = $row['user_id'];
                $controller = $row['controller'];
                $action = $row['action'];
                $published_at = date("Y-m-d", strtotime($row['published_at']));
                $published_end_at = date("Y-m-d", strtotime($row['published_end_at']));
                $meta_title = $row['meta_title'];
                $meta_description = $row['meta_description'];
                $display_order = $row['display_order'];
                $display_status = $row['display_status'];
            }
        } else {
            //$display_order = common_functions::getItemsMaxNo($req, $item_type);
        }

        // Categories
        $blogCategories = in_array($item_type, ['default', 'blog']) 
            ? common_functions::getBlogCategory($item_type) 
            : [];

        // Fields array (same structure as Node)
        $arrFields[] = [
            "type" => "text",
            "lbl" => "Name",
            "nm" => "item_title",
            "val" => $item_title,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => ($item_type === "default") ? "text" : "hidden",
            "lbl" => "Item Parent",
            "nm" => "item_parent",
            "val" => $item_parent,
            "req" => "N",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ['default','blog']) ? "select" : "hidden",
            "lbl" => "Category",
            "nm" => "item_sections_id",
            "val" => $item_sections_id,
            "is_multiple" => "Y",
            "cls" => "form-control formfields",
            "options" => $blogCategories
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ['default','page','blog']) ? "textarea" : "hidden",
            "lbl" => "Description",
            "nm" => "item_description",
            "val" => $item_description,
            "req" => "Y",
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ['default','page','blog']) ? "file" : "hidden",
            "lbl" => "Attachment1",
            "nm" => "attachment1",
            "val" => $attachment1,
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => in_array($item_type, ['default','page','blog']) ? "text" : "hidden",
            "lbl" => "Meta Title",
            "nm" => "meta_title",
            "val" => $meta_title,
            "cls" => "form-control formfields"
        ];

        $arrFields[] = [
            "type" => "hidden",
            "lbl" => "Edit ID",
            "nm" => "item_id",
            "val" => $item_id
        ];

        if ($item_id == 0) {
            $arrFields[] = [
                "type" => "hidden",
                "nm" => "created_at",
                "val" => date("Y-m-d H:i:s")
            ];
        }

        $arrFields[] = [
            "type" => "hidden",
            "nm" => "user_id",
            "val" => $user_id
        ];

        // Response data (similar to Node)
        $responseData = [
            "fields" => $arrFields,
            "listUrl" => common_functions::getHostUrl($req) . "/items?item_type=" . $item_type,
            "formUrl" => common_functions::getHostUrl($req) . "/item_form?item_type=" . $item_type,
            "edit_id" => $edit_id,
            "item_sections_id" => $item_sections_id
        ];

        return $responseData;

    } catch (Exception $e) {
        return [
            "success" => false,
            "message" => $e->getMessage()
        ];
    }
}