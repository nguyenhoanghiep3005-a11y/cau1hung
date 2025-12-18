<?php
// Simple read-only API for classes (improved CORS)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require __DIR__ . '/../config/db.php';
require __DIR__ . '/helpers/response.php';

$pdo = getPDO();
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

try {
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) json_response(['error' => 'Class not found'], 404);
        json_response($row);
    }

    $stmt = $pdo->query("SELECT * FROM classes ORDER BY id ASC");
    $rows = $stmt->fetchAll();
    json_response($rows);
} catch (Exception $e) {
    json_response(['error' => $e->getMessage()], 500);
}
