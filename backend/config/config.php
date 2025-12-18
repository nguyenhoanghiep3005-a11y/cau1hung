<?php
// Default config. Don't commit local credentials—copy config.local.example.php -> config.local.php and set your values.
$local = __DIR__ . '/config.local.php';
if (file_exists($local)) {
    return include $local;
}

return [
    'db_host' => '127.0.0.1',
    'db_name' => 'student_management',
    'db_user' => 'root',
    'db_pass' => '',
    'db_charset' => 'utf8mb4',
];
