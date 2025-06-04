-- ========================================
-- SỬA LỖI HASH MẬT KHẨU ĐĂNG NHẬP
-- ========================================
-- File này sửa lại hash mật khẩu để có thể đăng nhập được với các tài khoản sẵn có
-- Vấn đề là hash mật khẩu trong database không được tạo bằng PHP password_hash()

USE webbanhang;

-- Tạo hash mới cho tài khoản admin
UPDATE users SET password = '$2y$10$VPGGGPOy3yqzKCNQnWxPKu7xIEqxsszKw1XmQea7d4wPPsG5YrRxO' WHERE username = 'admin';

-- Tạo hash mới cho tài khoản Admin1 và Admin2
UPDATE users SET password = '$2y$10$7HdQJgA5XYBQXJLrD0gfbuJkGWc6eLJqBWDBOuYU0Vm0qz0QgWPM.' WHERE username IN ('Admin1', 'Admin2');

-- Tạo hash mới cho tài khoản Staff1 và Staff2
UPDATE users SET password = '$2y$10$Sx/sD.zUFc3eVvYFyJT/AuWVFRY.nvGUh4HL0JxCwGSN32Sw/PkVq' WHERE username IN ('Staff1', 'Staff2');

-- Tạo hash mới cho tài khoản Customer1 và Customer2
UPDATE users SET password = '$2y$10$d.FiNBd6RcJgUXVw7YqHQekCVfLAT6BUezxVnVDOW0DnSvQXQCwA.' WHERE username IN ('Customer1', 'Customer2');

-- Kiểm tra kết quả
SELECT 'Đã sửa xong hash mật khẩu cho tất cả tài khoản!' as message;
SELECT username, role, status FROM users;

-- THÔNG TIN ĐĂNG NHẬP:
-- 1. admin / admin123
-- 2. Admin1 / Admin@123
-- 3. Admin2 / Admin@123
-- 4. Staff1 / Staff@123
-- 5. Staff2 / Staff@123
-- 6. Customer1 / Customer@123
-- 7. Customer2 / Customer@123
