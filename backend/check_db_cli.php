<?php
require __DIR__ . '/config/db.php';
try {
    $pdo = getPDO();
    $stmt = $pdo->query('SHOW TABLES');
    $rows = $stmt->fetchAll(PDO::FETCH_NUM);
    echo json_encode(['status' => 'ok', 'tables' => $rows], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    $buf = ob_get_level() ? ob_get_clean() : '';
    echo json_encode(['status' => 'error', 'message' => $e->getMessage(), 'debug_output' => trim($buf)], JSON_UNESCAPED_UNICODE);
}
