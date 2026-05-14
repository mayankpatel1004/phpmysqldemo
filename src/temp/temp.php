<?php
// Database Connection
$host     = "localhost";
$username = "root";
$password = "";
$database = "your_database_name";

$conn = new mysqli($host, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => false,
        "message" => "Database connection failed"
    ]));
}

/*
|--------------------------------------------------------------------------
| Save Configuration
|--------------------------------------------------------------------------
| Table Structure Example:
|
| CREATE TABLE site_config (
|   id INT AUTO_INCREMENT PRIMARY KEY,
|   config_name VARCHAR(255) UNIQUE,
|   config_value TEXT
| );
|
*/

if (isset($_GET['action']) && $_GET['action'] == 'saveconfig') {

    $data = $_POST;

    if (empty($data)) {
        echo json_encode([
            "status" => false,
            "message" => "No data received"
        ]);
        exit;
    }

    foreach ($data as $config_name => $config_value) {

        // Check Existing Record
        $check = $conn->prepare("
            SELECT id 
            FROM site_config 
            WHERE config_name = ?
        ");

        $check->bind_param("s", $config_name);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            // Update Existing
            $update = $conn->prepare("
                UPDATE site_config 
                SET config_value = ?
                WHERE config_name = ?
            ");

            $update->bind_param("ss", $config_value, $config_name);
            $update->execute();

        } else {

            // Insert New
            $insert = $conn->prepare("
                INSERT INTO site_config 
                (config_name, config_value)
                VALUES (?, ?)
            ");

            $insert->bind_param("ss", $config_name, $config_value);
            $insert->execute();
        }
    }

    echo json_encode([
        "status" => true,
        "message" => "Configuration saved successfully"
    ]);

    exit;
}
?>