<?php
// Simple PDO connection helper. Use getPDO() to fetch the PDO instance.
// Loads config from config.php (which will prefer config.local.php if present)

$config = include __DIR__ . '/config.php';

$dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset={$config['db_charset']}";
try {
    $pdo = new PDO($dsn, $config['db_user'], $config['db_pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database connection error: " . htmlspecialchars($e->getMessage());
    exit;
}

function getPDO()
{
    global $pdo;
    return $pdo;
}
