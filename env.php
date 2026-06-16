<?php

function loadEnv($file)
{
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
loadEnv(__DIR__ . '/.env');
echo "<h2>ENV Test Result</h2>";
echo "<pre>";
print_r($_ENV);
echo "</pre>";
echo "<hr>";
echo "APP_ENV = " . ($_ENV['APP_ENV'] ?? 'NOT FOUND') . "<br>";
echo "DB_HOST = " . ($_ENV['DB_HOST'] ?? 'NOT FOUND') . "<br>";
echo "DB_NAME = " . ($_ENV['DB_NAME'] ?? 'NOT FOUND') . "<br>";
echo "DB_USER = " . ($_ENV['DB_USER'] ?? 'NOT FOUND') . "<br>";
echo "DB_PASS = " . ($_ENV['DB_PASS'] ?? 'NOT FOUND') . "<br>";
echo "SITE_URL = " . ($_ENV['SITE_URL'] ?? 'NOT FOUND') . "<br>";