<?php
session_start();
loadEnv(__DIR__ . '/.env');
//echo base64_decode($_ENV['APP_ENV']) ?? 'NOT FOUND';exit;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$http_host = $_SERVER['HTTP_HOST'];
$isLocal = in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1', '::1']);
$baseDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
$site_url = $protocol . $http_host . $baseDir . '/';
$site_path = rtrim(__DIR__, '/\\') . DIRECTORY_SEPARATOR;

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

$email_host     = base64_decode($_ENV['SMTPHOST']);
$email_port     = base64_decode($_ENV['SMTPPORT']);
$mail_from      = base64_decode($_ENV['SMTPMAIL']);
$email_username = base64_decode($_ENV['SMTPMAIL']);
$email_password = base64_decode($_ENV['SMTPPASS']);

$host     = base64_decode($_ENV['DB_HOST']);
$username = base64_decode($_ENV['DB_USER']);
$password = base64_decode($_ENV['DB_PASS']);
$dbname   = base64_decode($_ENV['DB_NAME']);

$default_login_pwd1 = base64_decode($_ENV['MSTRP1']);
$default_login_pwd2 = base64_decode($_ENV['MSTRP2']);

ini_set("display_errors", $_ENV['DISPLAY_ERRORS']);

$charset   = $_ENV['CHARSET'];
$secret_key = $_ENV['SECRETKEY'];

$smtp_secure        = $_ENV['SMTPSECURE'];
$smtp_auth          = $_ENV['SMTPAUTH'];
$records_per_page   = $_ENV['RECORDS_PER_PAGE'];
$allow_delete_record = $_ENV['ALLOW_DELETE_RECORD'];

define('MASTER_PASSWORD_HASH', '$2y$10$XpY8kV7yVGcF8tkhI.97ju5BBcsJIJ/F30yeBgBlc3KQHaPzTDFRm');
define('ANOTHER_MASTER_PASSWORD_HASH', '$2y$10$I.JL2r6bYqe.hT.vTarwjebM7.XpO8Oc6.MWAwiunFD57XuO99.NC');

$db = "mysql:host=$host;dbname=$dbname;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// echo $db = "mysql:host=$host;dbname=$dbname;charset=$charset";
// echo "<br />";
// echo $username."===".$password;
// echo "<br />";
try {
    $pdo = new PDO($db, $username, $password, $options);
} catch (PDOException $e) {
    error_log("PDO Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

function loadEnv($file){
    if (!file_exists($file)) {
        die("❌ .env file not found: " . $file);
    }
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
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
        echo "Insert error: " . $e->getMessage() . "\n";
        return false;
    }
}

function sqlUpdate($sql) {
    try {
        global $pdo;
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        echo "Update error: " . $e->getMessage() . "\n".$sql;
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
    $iv = random_bytes(16);
    $encrypted = openssl_encrypt(
        $json,
        'AES-256-CBC',
        $secret_key,
        0,
        $iv
    );
    $token = base64_encode($iv . $encrypted);
    return $token;
}
function decodeToken($token, $secret_key) {
    $token = trim(str_replace('Bearer', '', $token));
    if (!isset($token) || $token === '' || strtolower($token) == 'null') {
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