-- ========================================
-- WEBBANHANG COMPLETE DATABASE SETUP
-- ========================================
-- File này chứa toàn bộ cấu trúc database cho hệ thống TITI Shop
-- Bao gồm: tables, roles, sample data
-- Chạy file này để tạo database hoàn chỉnh trên máy mới
-- ========================================

-- Tạo database nếu chưa tồn tại
DROP DATABASE IF EXISTS webbanhang;
CREATE DATABASE webbanhang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE webbanhang;

-- ========================================
-- BẢNG USERS (Người dùng)
-- ========================================
CREATE TABLE users ( 
    id INT AUTO_INCREMENT PRIMARY KEY, 
    username VARCHAR(255) NOT NULL UNIQUE, 
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, 
    avatar VARCHAR(255) DEFAULT NULL,
    age INT DEFAULT NULL,
    role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer',
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'approved',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

-- ========================================
-- BẢNG CATEGORIES (Danh mục sản phẩm)
-- ========================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- BẢNG PRODUCTS (Sản phẩm)
-- ========================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- ========================================
-- BẢNG ORDERS (Đơn hàng)
-- ========================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ========================================
-- BẢNG ORDER_ITEMS (Chi tiết đơn hàng)
-- ========================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ========================================
-- BẢNG PERMISSIONS (Quyền hạn - Tùy chọn)
-- ========================================
CREATE TABLE permissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- BẢNG ROLE_PERMISSIONS (Vai trò - Quyền hạn)
-- ========================================
CREATE TABLE role_permissions (
    role_id VARCHAR(20) NOT NULL,
    permission_id INT NOT NULL,
    PRIMARY KEY (role_id, permission_id),
    FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- ========================================
-- DỮ LIỆU MẪU - USERS (Admin, Staff, Customer)
-- ========================================

-- ADMIN ACCOUNTS
-- Username: admin, Password: admin123
INSERT INTO users (username, email, password, role, status) 
VALUES ('admin', 'admin@titishop.com', '$2y$10$VPGGGPOy3yqzKCNQnWxPKu7xIEqxsszKw1XmQea7d4wPPsG5YrRxO', 'admin', 'approved');

-- Username: Admin1, Password: Admin@123
INSERT INTO users (username, email, password, role, status) 
VALUES ('Admin1', 'admin1@titishop.com', '$2y$10$7HdQJgA5XYBQXJLrD0gfbuJkGWc6eLJqBWDBOuYU0Vm0qz0QgWPM.', 'admin', 'approved');

-- Username: Admin2, Password: Admin@123
INSERT INTO users (username, email, password, role, status) 
VALUES ('Admin2', 'admin2@titishop.com', '$2y$10$7HdQJgA5XYBQXJLrD0gfbuJkGWc6eLJqBWDBOuYU0Vm0qz0QgWPM.', 'admin', 'approved');

-- STAFF ACCOUNTS  
-- Username: Staff1, Password: Staff@123
INSERT INTO users (username, email, password, role, status) 
VALUES ('Staff1', 'staff1@titishop.com', '$2y$10$Sx/sD.zUFc3eVvYFyJT/AuWVFRY.nvGUh4HL0JxCwGSN32Sw/PkVq', 'staff', 'approved');

-- Username: Staff2, Password: Staff@123  
INSERT INTO users (username, email, password, role, status) 
VALUES ('Staff2', 'staff2@titishop.com', '$2y$10$Sx/sD.zUFc3eVvYFyJT/AuWVFRY.nvGUh4HL0JxCwGSN32Sw/PkVq', 'staff', 'approved');

-- CUSTOMER ACCOUNTS
-- Username: Customer1, Password: Customer@123
INSERT INTO users (username, email, password, role, status) 
VALUES ('Customer1', 'customer1@titishop.com', '$2y$10$d.FiNBd6RcJgUXVw7YqHQekCVfLAT6BUezxVnVDOW0DnSvQXQCwA.', 'customer', 'approved');

-- Username: Customer2, Password: Customer@123
INSERT INTO users (username, email, password, role, status) 
VALUES ('Customer2', 'customer2@titishop.com', '$2y$10$d.FiNBd6RcJgUXVw7YqHQekCVfLAT6BUezxVnVDOW0DnSvQXQCwA.', 'customer', 'approved');

-- ========================================
-- DỮ LIỆU MẪU - CATEGORIES
-- ========================================
INSERT INTO categories (name, description) VALUES 
('Điện thoại di động', 'Các loại điện thoại di động thông minh từ các nhãn hiệu nổi tiếng'),
('Laptop', 'Máy tính xách tay cho công việc và giải trí'),
('Tablet', 'Máy tính bảng cho học tập và giải trí'),
('Phụ kiện', 'Các phụ kiện công nghệ như tai nghe, sạc, ốp lưng'),
('Đồng hồ thông minh', 'Smartwatch và đồng hồ thể thao');

-- ========================================
-- DỮ LIỆU MẪU - PRODUCTS (ĐIỆN THOẠI)
-- ========================================
INSERT INTO products (name, description, price, category_id, image) VALUES 
('iPhone 15 Pro Max', 'Điện thoại iPhone 15 Pro Max mới nhất với chip A17 Pro, camera 48MP và màn hình Super Retina XDR 6.7 inch. Pin siêu bền, chống nước IP68.', 28990000, 1, 'iphone15promax.jpg'),

('Samsung Galaxy S24 Ultra', 'Flagship Android với bút S Pen, camera 200MP, màn hình Dynamic AMOLED 6.8 inch và chip Snapdragon 8 Gen 3. Hỗ trợ AI và chụp ảnh chuyên nghiệp.', 26990000, 1, 'galaxys24ultra.jpg'),

('Xiaomi 14 Ultra', 'Camera Leica đỉnh cao với cảm biến chính 50MP, màn hình AMOLED 6.73 inch 120Hz, chip Snapdragon 8 Gen 3 và sạc nhanh 90W.', 22990000, 1, 'xiaomi14ultra.jpg'),

('OPPO Find X7 Ultra', 'Thiết kế cao cấp với camera Hasselblad, màn hình cong 6.82 inch, chip Snapdragon 8 Gen 3 và công nghệ sạc siêu tốc 100W.', 24990000, 1, 'oppofindx7ultra.jpg'),

('iPhone 14', 'iPhone 14 với chip A15 Bionic, camera kép 12MP, màn hình Super Retina XDR 6.1 inch. Thiết kế bền bỉ, hiệu năng mạnh mẽ.', 19990000, 1, 'iphone14.jpg'),

('Samsung Galaxy A55', 'Điện thoại tầm trung với camera 50MP, màn hình Super AMOLED 6.6 inch, chip Exynos 1480 và pin 5000mAh. Giá cả phải chăng.', 8990000, 1, 'galaxya55.jpg'),

('Xiaomi Redmi Note 13 Pro', 'Smartphone gaming với màn hình AMOLED 6.67 inch 120Hz, camera 200MP, chip MediaTek Dimensity 7200 và pin khủng 5100mAh.', 6990000, 1, 'redminote13pro.jpg'),

('Realme GT 5 Pro', 'Performance phone với chip Snapdragon 8 Gen 3, màn hình cong 6.78 inch, camera 50MP và sạc nhanh 100W. Thiết kế thể thao.', 12990000, 1, 'realmegt5pro.jpg'),

('Vivo V30 Pro', 'Chuyên gia selfie với camera trước 50MP, màn hình cong 6.78 inch, chip MediaTek Dimensity 8200 và thiết kế mỏng nhẹ sang trọng.', 11990000, 1, 'vivov30pro.jpg'),

('Honor Magic6 Pro', 'Công nghệ AI tiên tiến với camera 50MP, màn hình OLED 6.8 inch, chip Snapdragon 8 Gen 3 và pin 5600mAh siêu bền.', 18990000, 1, 'honormagic6pro.jpg');

-- ========================================
-- DỮ LIỆU MẪU - PRODUCTS (LAPTOP)
-- ========================================
INSERT INTO products (name, description, price, category_id, image) VALUES 
('MacBook Pro 14 inch M3', 'Laptop chuyên nghiệp với chip M3, màn hình Liquid Retina XDR 14 inch, 16GB RAM và SSD 512GB. Hoàn hảo cho developer và designer.', 45990000, 2, 'macbookpro14.jpg'),

('Dell XPS 13', 'Ultrabook cao cấp với màn hình InfinityEdge 13.4 inch, Intel Core i7, 16GB RAM và thiết kế siêu mỏng nhẹ chỉ 1.2kg.', 32990000, 2, 'dellxps13.jpg'),

('ASUS ROG Strix G15', 'Gaming laptop với RTX 4060, AMD Ryzen 7, màn hình 15.6 inch 144Hz và hệ thống tản nhiệt ROG Intelligent Cooling.', 25990000, 2, 'asusrogstrix.jpg'),

('HP Spectre x360', 'Laptop 2-in-1 cao cấp với màn hình cảm ứng 13.5 inch, Intel Core i7, 16GB RAM và thiết kế xoay 360 độ sang trọng.', 28990000, 2, 'hpspectre.jpg'),

('Lenovo ThinkPad X1 Carbon', 'Business laptop với độ bền quân đội, màn hình 14 inch, Intel Core i7, 16GB RAM và pin 15 giờ sử dụng.', 35990000, 2, 'thinkpadx1.jpg');

-- ========================================
-- DỮ LIỆU MẪU - PRODUCTS (TABLET)
-- ========================================
INSERT INTO products (name, description, price, category_id, image) VALUES 
('iPad Pro 12.9 inch M2', 'Tablet chuyên nghiệp với chip M2, màn hình Liquid Retina XDR 12.9 inch, hỗ trợ Apple Pencil và Magic Keyboard.', 29990000, 3, 'ipadpro129.jpg'),

('Samsung Galaxy Tab S9 Ultra', 'Android tablet cao cấp với màn hình AMOLED 14.6 inch, S Pen đi kèm và hiệu năng Snapdragon 8 Gen 2 mạnh mẽ.', 24990000, 3, 'galaxytabs9.jpg'),

('iPad Air', 'Tablet cân bằng giữa hiệu năng và giá cả với chip M1, màn hình 10.9 inch và thiết kế mỏng nhẹ, đa màu sắc.', 14990000, 3, 'ipadair.jpg');

-- ========================================
-- DỮ LIỆU MẪU - PRODUCTS (PHỤ KIỆN)
-- ========================================
INSERT INTO products (name, description, price, category_id, image) VALUES 
('AirPods Pro 2', 'Tai nghe không dây cao cấp với chống ồn chủ động, âm thanh không gian và hộp sạc MagSafe.', 5990000, 4, 'airpodspro2.jpg'),

('Samsung Galaxy Buds2 Pro', 'Tai nghe true wireless với ANC thông minh, âm thanh 360 độ và khả năng chống nước IPX7.', 3990000, 4, 'galaxybuds2pro.jpg'),

('Anker PowerBank 20000mAh', 'Pin dự phòng dung lượng cao với sạc nhanh PD 22.5W, 3 cổng sạc và màn hình LCD hiển thị dung lượng.', 890000, 4, 'ankerpowerbank.jpg'),

('Belkin MagSafe Charger', 'Đế sạc không dây MagSafe 15W cho iPhone, thiết kế tối giản và sạc nhanh an toàn.', 1290000, 4, 'belkinmagsafe.jpg');

-- ========================================
-- DỮ LIỆU MẪU - PRODUCTS (ĐỒNG HỒ)
-- ========================================
INSERT INTO products (name, description, price, category_id, image) VALUES 
('Apple Watch Series 9', 'Smartwatch cao cấp với chip S9, màn hình Always-On Retina, GPS + Cellular và tính năng sức khỏe toàn diện.', 9990000, 5, 'applewatch9.jpg'),

('Samsung Galaxy Watch6', 'Đồng hồ thông minh Android với thiết kế cổ điển, theo dõi sức khỏe 24/7 và pin 40 giờ sử dụng.', 6990000, 5, 'galaxywatch6.jpg'),

('Amazfit GTR 4', 'Smartwatch thể thao với pin 14 ngày, GPS dual-band, 150+ chế độ thể thao và thiết kế cứng cáp.', 3990000, 5, 'amazfitgtr4.jpg');

-- ========================================
-- DỮ LIỆU MẪU - PERMISSIONS
-- ========================================
INSERT INTO permissions (name, description) VALUES 
('manage_products', 'Quản lý sản phẩm - thêm, sửa, xóa'),
('manage_categories', 'Quản lý danh mục sản phẩm'),
('manage_orders', 'Quản lý đơn hàng'),
('view_reports', 'Xem báo cáo doanh thu'),
('manage_users', 'Quản lý người dùng - chỉ dành cho Admin'),
('approve_staff', 'Duyệt tài khoản nhân viên - chỉ dành cho Admin');

-- ========================================
-- DỮ LIỆU MẪU - ROLE PERMISSIONS
-- ========================================
INSERT INTO role_permissions (role_id, permission_id) VALUES 
-- Admin có tất cả quyền
('admin', 1), ('admin', 2), ('admin', 3), ('admin', 4), ('admin', 5), ('admin', 6),
-- Staff có quyền quản lý sản phẩm, danh mục, đơn hàng và xem báo cáo
('staff', 1), ('staff', 2), ('staff', 3), ('staff', 4);

-- ========================================
-- HOÀN THÀNH SETUP
-- ========================================
-- Database đã được tạo thành công!
-- 
-- DANH SÁCH TÀI KHOẢN MẪU:
-- 
-- ADMIN ACCOUNTS (3):
-- 1. Username: admin     | Password: admin123     | Email: admin@titishop.com
-- 2. Username: Admin1    | Password: Admin@123    | Email: admin1@titishop.com  
-- 3. Username: Admin2    | Password: Admin@123    | Email: admin2@titishop.com
--
-- STAFF ACCOUNTS (2):
-- 1. Username: Staff1    | Password: Staff@123    | Email: staff1@titishop.com
-- 2. Username: Staff2    | Password: Staff@123    | Email: staff2@titishop.com
--
-- CUSTOMER ACCOUNTS (2):  
-- 1. Username: Customer1 | Password: Customer@123 | Email: customer1@titishop.com
-- 2. Username: Customer2 | Password: Customer@123 | Email: customer2@titishop.com

SELECT 'Database WEBBANHANG đã được tạo thành công!' as message;
SELECT 'Tổng cộng 7 tài khoản: 3 Admin + 2 Staff + 2 Customer' as accounts_info;
SELECT COUNT(*) as total_products FROM products;
SELECT COUNT(*) as total_categories FROM categories;
SELECT COUNT(*) as total_users FROM users;
