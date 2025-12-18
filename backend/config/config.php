<?php
// Tự động kiểm tra môi trường
$isLocal = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

if ($isLocal) {
    // --- CẤU HÌNH CHO LOCALHOST (Máy tính của bạn) ---
    return [
        'db_host'    => 'localhost',
        'db_user'    => 'root',
        'db_pass'    => '',          
        'db_name'    => 'sinhvien',  // Đã khớp với ảnh phpMyAdmin của bạn
        'db_charset' => 'utf8mb4'
    ];
} else {
    // --- CẤU HÌNH CHO HOSTING (InfinityFree) ---
    return [
        'db_host'    => 'sql213.infinityfree.com',
        'db_user'    => 'if0_40099280',
        'db_pass'    => 'DxyY10Dm1L', 
        'db_name'    => 'if0_40099280_sinhvien',
        'db_charset' => 'utf8mb4'
    ];
}