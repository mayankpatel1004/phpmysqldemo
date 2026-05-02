<?php
session_start();
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$secret_key = "asdffffs@122334";
$records_per_page = 10;
$allow_delete_record = "N";

$email_host = "smtp.hostinger.com";
$email_port = 465;
$mail_from = "notifications@cloudswiftsolutions.com";
$mail_from_name = "Cloudswift Solutions";
$email_username = "notifications@cloudswiftsolutions.com";
$email_password = "Cloud@112018"; 
$smtp_secure = "ssl";
$smtp_auth = true;

$site_url = $protocol . $host . $baseDir . '/';
$site_path = rtrim(__DIR__, '/\\') . DIRECTORY_SEPARATOR;

ini_set("display_errors",1);

$host = 'localhost';
$dbname = 'Demonstration';
$username = 'developer';
$password = 'Online@112018';
$charset = 'utf8mb4';

// db (Data Source Name)
$db = "mysql:host=$host;dbname=$dbname;charset=$charset";

// PDO options
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetch associative arrays by default
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Use native prepared statements
];

try {
    $pdo = new PDO($db, $username, $password, $options);
} catch (PDOException $e) {
    error_log("PDO Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}


function sqlSelect($sql,$fetchMode = PDO::FETCH_ASSOC) {
    try {
        global $pdo;
        $fetchAll = true;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $fetchAll ? $stmt->fetchAll($fetchMode) : $stmt->fetch($fetchMode);
    } catch (PDOException $e) {
        echo "❌ Select error: " . $e->getMessage() . "\n";
        return false;
    }
}

function sqlInsert($table, $data) {
    try {
        global $pdo;
        $columns = array_keys($data);
        $placeholders = ':' . implode(', :', $columns);
        $columnsList = implode(', ', $columns);

        $sql = "INSERT INTO " . $pdo->quote($table) . " ($columnsList) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);

        // Bind values
        foreach ($data as $col => $val) {
            $stmt->bindValue(':' . $col, $val);
        }

        $stmt->execute();
        return (int) $pdo->lastInsertId();
    } catch (PDOException $e) {
        echo "❌ Insert error: " . $e->getMessage() . "\n";
        return false;
    }
}

function sqlUpdate($sql) {
    try {
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "❌ Update error: " . $e->getMessage() . "\n".$sql;
        return false;
    }
}

function sqlDelete($sql) {
    try {
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Delete error: " . $e->getMessage() . "\n";
        return false;
    }
}
function encodeToken($data, $secret_key) {
    $json = json_encode($data);
    $iv = random_bytes(16); // AES-256 needs 16 bytes IV
    $encrypted = openssl_encrypt(
        $json,
        'AES-256-CBC',
        $secret_key,
        0,
        $iv
    );
    // Combine IV + encrypted data
    $token = base64_encode($iv . $encrypted);
    return $token;
}
function decodeToken($token, $secret_key) {
    $data = base64_decode($token);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    $decrypted = openssl_decrypt(
        $encrypted,
        'AES-256-CBC',
        $secret_key,
        0,
        $iv
    );
    return json_decode($decrypted, true);
}
?>