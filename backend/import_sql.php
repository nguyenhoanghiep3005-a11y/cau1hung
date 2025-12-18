<?php
// import_sql.php
// Usage: upload this file and `sinhvien.sql` to the backend/ folder on your server.
// Then visit: https://your-domain/backend/import_sql.php?token=YOUR_TOKEN
// IMPORTANT: Replace $SECRET with a random value before uploading. Delete this file after import.

$SECRET = 'CHANGE_ME_TOKEN'; // <<< CHANGE THIS BEFORE UPLOADING
$token = $_GET['token'] ?? null;

header('Content-Type: application/json; charset=utf-8');
if (!$token || $token !== $SECRET) {
    http_response_code(403);
    echo json_encode(['error' => 'Invalid token']);
    exit;
}

// Load DB connection
require __DIR__ . '/config/db.php';
$pdo = getPDO();

$sqlFile = __DIR__ . '/sinhvien.sql';
if (!file_exists($sqlFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'sinhvien.sql not found in backend/']);
    exit;
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Unable to read SQL file']);
    exit;
}

// Optionally disable foreign key checks while importing
try {
    $pdo->beginTransaction();
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    // Split statements by semicolon followed by newline (naive but works for simple dumps)
    $statements = preg_split('/;\r?\n/', $sql);
    $executed = 0;
    $errors = [];
    foreach ($statements as $stmt) {
        $stmt = trim($stmt);
        if (!$stmt) continue;
        try {
            $pdo->exec($stmt);
            $executed++;
        } catch (PDOException $e) {
            $errors[] = ['sql' => $stmt, 'error' => $e->getMessage()];
        }
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
    $pdo->commit();

    echo json_encode(['status' => 'ok', 'executed' => $executed, 'errors' => $errors], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
