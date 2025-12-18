Hướng dẫn nhanh để deploy lên InfinityFree

Bước 1 — Upload file cấu hình
- Tạo file `backend/config/config.local.php` (bạn có thể copy `backend/config/config.infinity.example.php` và điền thông tin):
  - db_host: (ví dụ `sql213.infinityfree.com`)
  - db_name: (ví dụ `if0_40099280_sinhvien`)
  - db_user: (ví dụ `if0_40099280`)
  - db_pass: (mật khẩu bạn đã tạo)

Bước 2 — Upload import script và SQL
- Upload `backend/import_sql.php` và `sinhvien.sql` (file SQL bạn đã sẵn có) vào thư mục `backend/` trên hosting (qua File Manager hoặc FTP).
- Trước khi upload, mở `import_sql.php` và chỉnh `$SECRET = 'CHANGE_ME_TOKEN'` thành 1 token ngẫu nhiên (ví dụ: `s3cr3t-abc123`)

Bước 3 — Run import
- Trỏ trình duyệt tới:
  `https://your-domain/backend/import_sql.php?token=THE_TOKEN`
- Kết quả sẽ trả JSON với `executed` và `errors`.

Bước 4 — Xoá file import
- Sau khi import thành công, **xóa** `backend/import_sql.php` và (tùy chọn) `backend/sinhvien.sql` khỏi host để bảo mật.

Bước 5 — Kiểm tra API
- Ping: `https://your-domain/backend/api/ping.php` → mong đợi `{ "status": "ok" }`
- Students: `https://your-domain/backend/api/students.php` → mong đợi JSON mảng
- Frontend: `https://your-domain/frontend/index.html`

Ghi chú bảo mật
- Không lưu mật khẩu DB trong repo commit. `config.local.php` phải được tạo trực tiếp trên server và **KHÔNG** đẩy lên GitHub.
- Xóa `import_sql.php` sau khi xong để tránh lỗ hổng an ninh.

Nếu bạn muốn, tôi có thể:
- Hướng dẫn chi tiết từng bước kèm ảnh chụp màn hình, hoặc
- upload các file này lên host cho bạn nếu bạn cung cấp FTP credentials.
