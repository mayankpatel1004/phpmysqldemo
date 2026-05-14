global $pdo,$site_path;
    if (isset($files['user_photo']) && $files['user_photo']['error'] == 0) {
        $uploadDir = $site_path."public/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $tmp_file_name = $files['user_photo']['tmp_name'];
        $extension = pathinfo($files['user_photo']['name'], PATHINFO_EXTENSION);
        $newFileName = time() . "_" . rand(1000, 9999) . "." . $extension;
        $targetFile = $uploadDir . $newFileName;
        if (move_uploaded_file($tmp_file_name, $targetFile)) {
            $data['user_photo'] = $newFileName;
        }
    }