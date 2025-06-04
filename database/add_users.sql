-- ========================================
-- THÊM TÀI KHOẢN ADMIN, STAFF, CUSTOMER
-- ========================================
-- File này chỉ thêm tài khoản mới vào database đã tồn tại
-- Chạy trên database webbanhang

USE webbanhang;

-- Thêm 2 Admin bổ sung
INSERT INTO users (username, email, password, role, status) 
VALUES ('Admin1', 'admin1@titishop.com', '$2y$10$vQ1LiZjzJn8hAd.bm2x6v.Y8JH7UrEyGxFP9/k4K7m6C2lU8s0W1G', 'admin', 'approved');

INSERT INTO users (username, email, password, role, status) 
VALUES ('Admin2', 'admin2@titishop.com', '$2y$10$vQ1LiZjzJn8hAd.bm2x6v.Y8JH7UrEyGxFP9/k4K7m6C2lU8s0W1G', 'admin', 'approved');

-- Thêm 2 Staff
INSERT INTO users (username, email, password, role, status) 
VALUES ('Staff1', 'staff1@titishop.com', '$2y$10$xR2MjAkzkO9iBeNm3y7w.Z9KI8VsEzHyGFQ0/l5L8n7D3mV9t1X2H', 'staff', 'approved');

INSERT INTO users (username, email, password, role, status) 
VALUES ('Staff2', 'staff2@titishop.com', '$2y$10$xR2MjAkzkO9iBeNm3y7w.Z9KI8VsEzHyGFQ0/l5L8n7D3mV9t1X2H', 'staff', 'approved');

-- Thêm 2 Customer
INSERT INTO users (username, email, password, role, status) 
VALUES ('Customer1', 'customer1@titishop.com', '$2y$10$yS3NkBlAlP0jCfOn4z8x.A0LJ9WtFzIzHGR1/m6M9o8E4nW0u2Y3I', 'customer', 'approved');

INSERT INTO users (username, email, password, role, status) 
VALUES ('Customer2', 'customer2@titishop.com', '$2y$10$yS3NkBlAlP0jCfOn4z8x.A0LJ9WtFzIzHGR1/m6M9o8E4nW0u2Y3I', 'customer', 'approved');

-- Kiểm tra kết quả
SELECT 'Đã thêm thành công 6 tài khoản mới!' as message;
SELECT username, email, role, status FROM users ORDER BY role, username;
SELECT 
    role,
    COUNT(*) as total_accounts
FROM users 
GROUP BY role 
ORDER BY role;
