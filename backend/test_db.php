<?php
// Quick test: open this in your browser (e.g., http://localhost/web-don-gian/backend/test_db.php)
require __DIR__ . '/config/db.php';

// $config is available because config.php returns the array
if (!isset($config)) {
    $config = include __DIR__ . '/config.php';
}

$pdo = getPDO();

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_NUM);
    echo "<h3>Connected to database: " . htmlspecialchars($config['db_name']) . "</h3>";
    if ($tables) {
        echo "<ul>";
        foreach ($tables as $t) {
            echo "<li>" . htmlspecialchars($t[0]) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No tables found. Import <code>backend/database/create_database.sql</code> first via phpMyAdmin.</p>";
    }
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage());
}
