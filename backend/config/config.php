<?php
// Đường dẫn tới file cấu hình local
$localConfigFile = __DIR__ . '/config.local.php';

// 1. Kiểm tra: Nếu có file config.local.php thì dùng nó luôn (Ưu tiên Local)
if (file_exists($localConfigFile)) {
    return require $localConfigFile;
}

// 2. Nếu không tìm thấy file local -> Nghĩa là đang ở trên Host -> Dùng cấu hình InfinityFree
return [
    'db_host'    => 'sql213.infinityfree.com', // Check kỹ xem host của bạn là sql bao nhiêu
    'db_user'    => 'if0_40099280',
    'db_pass'    => 'DxyY10DmlL', // Mật khẩu đúng (chữ l thường)
    'db_name'    => 'if0_40099280_sinhvien',
    'db_charset' => 'utf8mb4'
];