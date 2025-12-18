<?php
// Simple REST endpoints for students
// Routes (single file):
// GET    /backend/api/students.php             => list all
// GET    /backend/api/students.php?id=1        => get one
// POST   /backend/api/students.php             => create (JSON)
// PUT    /backend/api/students.php?id=1        => update (JSON)
// DELETE /backend/api/students.php?id=1        => delete

// CORS + basic headers (improved)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, X-Requested-With');
header('Access-Control-Allow-Credentials: false');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Preflight response
    http_response_code(204);
    exit;
}

// Start output buffering and avoid direct PHP warnings printed to output
ob_start();
ini_set('display_errors', '0');
ini_set('log_errors', '1');

require __DIR__ . '/../config/db.php';
require __DIR__ . '/helpers/response.php';
require __DIR__ . '/helpers/validator.php';

$pdo = getPDO();
$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

try {
    if ($method === 'GET') {
        if ($id) {
            $stmt = $pdo->prepare("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = :id LIMIT 1");
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();
            if (!$row) {
                json_response(['error' => 'Student not found'], 404);
            }
            json_response($row);
        }
        // list all
        $stmt = $pdo->query("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id ORDER BY s.id DESC");
        $rows = $stmt->fetchAll();
        json_response($rows);
    }

    if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $errors = validate_student($data, false);
        if ($errors) json_response(['errors' => $errors], 422);

        // check unique email (if provided)
        if (!empty($data['email'])) {
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $data['email']]);
            if ($stmt->fetch()) json_response(['error' => 'Email already in use'], 409);
        }

        $stmt = $pdo->prepare("INSERT INTO students (name, email, class_id) VALUES (:name, :email, :class_id)");
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'class_id' => $data['class_id'] ?? null,
        ]);
        $newId = (int)$pdo->lastInsertId();
        $stmt = $pdo->prepare("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = :id LIMIT 1");
        $stmt->execute(['id' => $newId]);
        $new = $stmt->fetch();
        json_response($new, 201);
    }

    if ($method === 'PUT') {
        if (!$id) json_response(['error' => 'Missing id'], 400);
        $data = json_decode(file_get_contents('php://input'), true) ?: [];
        $errors = validate_student($data, true);
        if ($errors) json_response(['errors' => $errors], 422);

        // ensure student exists
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $existing = $stmt->fetch();
        if (!$existing) json_response(['error' => 'Student not found'], 404);

        // if email changed, check uniqueness
        if (isset($data['email']) && $data['email'] !== $existing['email'] && !empty($data['email'])) {
            $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
            $stmt->execute(['email' => $data['email']]);
            if ($stmt->fetch()) json_response(['error' => 'Email already in use'], 409);
        }

        $fields = [];
        $params = ['id' => $id];
        if (array_key_exists('name', $data)) { $fields[] = 'name = :name'; $params['name'] = $data['name']; }
        if (array_key_exists('email', $data)) { $fields[] = 'email = :email'; $params['email'] = $data['email']; }
        if (array_key_exists('class_id', $data)) { $fields[] = 'class_id = :class_id'; $params['class_id'] = $data['class_id']; }

        if (!$fields) json_response(['message' => 'Nothing to update'], 200);

        $sql = "UPDATE students SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $stmt = $pdo->prepare("SELECT s.*, c.name AS class_name FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        json_response($row);
    }

    if ($method === 'DELETE') {
        if (!$id) json_response(['error' => 'Missing id'], 400);
        $stmt = $pdo->prepare("SELECT id FROM students WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        if (!$stmt->fetch()) json_response(['error' => 'Student not found'], 404);
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
        $stmt->execute(['id' => $id]);
        json_response(['message' => 'Deleted'], 200);
    }

    json_response(['error' => 'Method not allowed'], 405);
} catch (Exception $e) {
    json_response(['error' => $e->getMessage()], 500);
}
