<?php
session_start();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$http_host = $_SERVER['HTTP_HOST'];
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']);
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$site_url = $protocol . $http_host . $baseDir . '/';
$site_path = rtrim(__DIR__, '/\\') . DIRECTORY_SEPARATOR;

if($isLocal){
    $host = 'localhost';
    $dbname = 'Demonstration';
    $username = 'developer';
    $password = 'Online@112018';
    ini_set("display_errors",1);
    error_reporting(E_ALL);
    ini_set('log_errors', '1'); 
    $errorLogPath = $site_path . "src/logs/error.log";
    $logDir = dirname($errorLogPath);
    if (!is_dir($logDir)) {mkdir($logDir, 0755, true);}
    if (!file_exists($errorLogPath)) {
        touch($errorLogPath);
        chmod($errorLogPath, 0644);
    }
    ini_set('error_log', $errorLogPath);
} else {
    $host = 'localhost';
    $dbname = 'u797036281_demo';
    $username = 'u797036281_demo';
    $password = 'Online@112018';
    ini_set("display_errors",0);
}

$charset = 'utf8mb4';
$secret_key = "asdffffs@122334";

$email_host = "smtp.hostinger.com";
$email_port = 465;
$mail_from = "notifications@cloudswiftsolutions.com";
$mail_from_name = "Cloudswift Solutions";
$email_username = "notifications@cloudswiftsolutions.com";
$email_password = "Cloud@112018"; 
$smtp_secure = "ssl";
$smtp_auth = true;


$records_per_page = 10;
$allow_delete_record = "N";

$db = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
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
    $token = trim(str_replace('Bearer', '', $token));
    //echo $token;exit;
    if (
        !isset($token) ||
        $token === '' ||
        strtolower($token) == 'null'
    ) {
        return [
            'status' => 0,
            'message' => 'Invalid Token'
        ];
    }
    $data = base64_decode($token, true);
    if ($data === false) {
        return [
            'status' => 0,
            'message' => 'Invalid token format'
        ];
    }
    if (strlen($data) < 16) {
        return [
            'status' => 0,
            'message' => 'Invalid token data'
        ];
    }
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    $decrypted = openssl_decrypt(
        $encrypted,
        'AES-256-CBC',
        $secret_key,
        0,
        $iv
    );
    if ($decrypted === false) {
        return [
            'status' => 0,
            'message' => 'Token decryption failed'
        ];
    }
    $decoded = json_decode($decrypted, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [
            'status' => 0,
            'message' => 'Invalid token JSON'
        ];
    }
    return [
        'status' => true,
        'data' => $decoded
    ];
}

function get_request_headers() {
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    } else {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) == 'HTTP_') {
                $header = str_replace(
                    ' ',
                    '-',
                    ucwords(str_replace('_', ' ', strtolower(substr($key, 5))))
                );

                $headers[$header] = $value;
            }
        }
    }

    return isset($headers['Authorization']) 
        ? $headers['Authorization'] 
        : '';
}

function fnInvalidToken(){
    $arr = array(
        'success' => 0,
        'message' => "Invalid Token",
        'total_records' => 0,
        'total_pages' => 0,
        'current_page_no' => 0,
        'data' => []
    );
    return $arr;
}

function logQuery($sql, $params, $logDir = null) {
    global $site_path;
    if ($logDir === null) {
        $logDir = $site_path . 'src/logs/';
    }
    if (!is_dir($logDir)) {
        if (!mkdir($logDir, 0755, true)) {
            error_log("Failed to create log directory: " . $logDir);
            return false;
        }
    }
    $filename = 'query_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.log';
    $filePath = $logDir . $filename;
    $parts = explode('?', $sql);
    $output = '';
    foreach ($parts as $i => $part) {
        $output .= $part;
        if (isset($params[$i])) {
            $val = $params[$i];
            if ($val === null) {
                $output .= 'NULL';
            } elseif (is_int($val) || is_float($val)) {
                $output .= $val;
            } elseif (is_bool($val)) {
                $output .= ($val ? '1' : '0');
            } else {
                $output .= "'" . addslashes((string)$val) . "'";
            }
        }
    }
    $logLine = date('[Y-m-d H:i:s]') . " " . $output . PHP_EOL;
    if (file_put_contents($filePath, $logLine, LOCK_EX) === false) {
        error_log("Failed to write log file: " . $filePath);
        return false;
    }
    chmod($filePath, 0644);
    return $filePath;
}
function createAlias($title, $separator = '-') {
    $alias = mb_strtolower($title, 'UTF-8');
    $alias = preg_replace('/[^\p{L}\p{N}\s]/u', '', $alias);
    $alias = preg_replace('/\s+/', $separator, $alias);
    $alias = trim($alias, $separator);
    $alias = preg_replace('/' . preg_quote($separator, '/') . '{2,}/', $separator, $alias);
    return $alias ?: 'alias';
}
?>