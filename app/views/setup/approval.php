<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hướng dẫn cài đặt hệ thống duyệt tài khoản - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <h1>Hướng dẫn cài đặt hệ thống duyệt tài khoản</h1>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Bước 1: Cập nhật Database</h5>
            </div>
            <div class="card-body">
                <p>Chạy script SQL sau trong phpMyAdmin:</p>
                <div class="bg-light p-3 border rounded">
                    <pre><code>-- Thêm cột status vào bảng users
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved' 
AFTER role;

-- Cập nhật trạng thái cho users hiện tại
UPDATE users SET status = 'approved' WHERE status IS NULL OR status = '';</code></pre>
                </div>
                <p class="mt-3">
                    <strong>Hoặc</strong> chạy file: 
                    <code>/webbanhang/database/final_update_approval_system.sql</code>
                </p>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Bước 2: Kiểm tra tính năng</h5>
            </div>
            <div class="card-body">
                <h6>Hệ thống duyệt tài khoản đã hoạt động với các tính năng:</h6>
                <ul>
                    <li><strong>Đăng ký Khách hàng:</strong> Tự động được duyệt và có thể đăng nhập ngay</li>
                    <li><strong>Đăng ký Nhân viên:</strong> Cần Admin duyệt trước khi có thể đăng nhập</li>
                    <li><strong>Quản lý người dùng:</strong> Admin có thể xem, duyệt/từ chối tài khoản</li>
                    <li><strong>Thông báo:</strong> Hệ thống hiển thị số lượng tài khoản chờ duyệt</li>
                </ul>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Bước 3: Test hệ thống</h5>
            </div>
            <div class="card-body">
                <h6>Để test hệ thống:</h6>
                <ol>
                    <li>Đăng xuất tài khoản Admin hiện tại</li>
                    <li>Tạo tài khoản mới với vai trò "Nhân viên"</li>
                    <li>Thử đăng nhập → Sẽ bị chặn với thông báo "chờ duyệt"</li>
                    <li>Đăng nhập lại bằng Admin</li>
                    <li>Vào "Quản lý người dùng" để duyệt tài khoản</li>
                    <li>Đăng xuất và thử đăng nhập bằng tài khoản vừa duyệt</li>
                </ol>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Các trang quản lý</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dành cho Admin:</h6>
                        <ul>
                            <li><a href="/webbanhang/User">Quản lý người dùng</a> - Xem tất cả users và tài khoản chờ duyệt</li>
                            <li><a href="/webbanhang/Setup">Thiết lập hệ thống</a> - Hướng dẫn cài đặt</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Dành cho tất cả:</h6>
                        <ul>
                            <li><a href="/webbanhang/Auth/register">Đăng ký tài khoản</a> - Form đăng ký với lựa chọn vai trò</li>
                            <li><a href="/webbanhang/Auth/login">Đăng nhập</a> - Kiểm tra trạng thái duyệt</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center">
            <a href="/webbanhang/User" class="btn btn-primary">Quản lý người dùng</a>
            <a href="/webbanhang/" class="btn btn-secondary">Về trang chủ</a>
        </div>
    </main>
    
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
