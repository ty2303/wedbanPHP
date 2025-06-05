-- Script tổng hợp để chạy toàn bộ migration system quản lý trạng thái đơn hàng
-- Chạy file này trong phpMyAdmin hoặc MySQL Workbench để setup hoàn chỉnh

-- ======================================================
-- BƯỚC 1: CHẠY MIGRATION CHÍNH
-- ======================================================

-- 1. Cập nhật cột status trong bảng orders với các trạng thái mới
ALTER TABLE orders MODIFY COLUMN status ENUM(
    'pending',      -- Chờ xử lý
    'confirmed',    -- Đã xác nhận
    'processing',   -- Đang xử lý
    'packed',       -- Đã đóng gói
    'shipped',      -- Đã gửi hàng
    'delivered',    -- Đã giao hàng
    'cancelled',    -- Đã hủy
    'returned'      -- Đã trả hàng
) DEFAULT 'pending';

-- 2. Thêm các cột mới vào bảng orders
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS admin_notes TEXT AFTER status,
ADD COLUMN IF NOT EXISTS estimated_delivery DATE AFTER admin_notes,
ADD COLUMN IF NOT EXISTS tracking_number VARCHAR(100) AFTER estimated_delivery;

-- 3. Tạo bảng order_status_history để theo dõi lịch sử thay đổi trạng thái
CREATE TABLE IF NOT EXISTS order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    changed_by INT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- 4. Cập nhật tất cả đơn hàng hiện tại thành trạng thái 'pending' nếu chưa có trạng thái
UPDATE orders SET status = 'pending' WHERE status IS NULL OR status = '';

-- 5. Thêm lịch sử cho các đơn hàng hiện tại (chỉ những đơn chưa có lịch sử)
INSERT INTO order_status_history (order_id, status, notes, changed_at)
SELECT o.id, 'pending', 'Đơn hàng được tạo', o.created_at
FROM orders o
WHERE NOT EXISTS (
    SELECT 1 FROM order_status_history h WHERE h.order_id = o.id
);

-- 6. Tạo index để tăng hiệu suất truy vấn
CREATE INDEX IF NOT EXISTS idx_orders_status ON orders(status);
CREATE INDEX IF NOT EXISTS idx_order_status_history_order_id ON order_status_history(order_id);
CREATE INDEX IF NOT EXISTS idx_order_status_history_status ON order_status_history(status);

-- ======================================================
-- BƯỚC 2: THÊM DỮ LIỆU SAMPLE (TÙY CHỌN)
-- ======================================================

-- Cập nhật một số đơn hàng hiện tại với các trạng thái khác nhau để test
-- Bỏ comment các dòng dưới nếu muốn tạo dữ liệu mẫu

/*
-- Cập nhật trạng thái cho các đơn hàng có sẵn (nếu có)
UPDATE orders SET 
    status = 'confirmed', 
    admin_notes = 'Đơn hàng đã được xác nhận và sẽ được xử lý trong 1-2 ngày làm việc' 
WHERE id = 1;

UPDATE orders SET 
    status = 'processing', 
    tracking_number = 'VN123456789', 
    estimated_delivery = DATE_ADD(CURDATE(), INTERVAL 3 DAY) 
WHERE id = 2;

UPDATE orders SET 
    status = 'shipped', 
    tracking_number = 'VN987654321', 
    estimated_delivery = DATE_ADD(CURDATE(), INTERVAL 1 DAY) 
WHERE id = 3;

-- Thêm lịch sử trạng thái cho các đơn hàng test
INSERT INTO order_status_history (order_id, status, notes, changed_by, changed_at) VALUES
(1, 'confirmed', 'Đơn hàng đã được xác nhận bởi admin', 1, NOW()),
(2, 'confirmed', 'Đơn hàng đã được xác nhận', 1, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 'processing', 'Bắt đầu xử lý và đóng gói đơn hàng', 1, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(3, 'confirmed', 'Đơn hàng đã được xác nhận', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'processing', 'Đang xử lý đơn hàng', 1, DATE_SUB(NOW(), INTERVAL 20 HOUR)),
(3, 'packed', 'Đơn hàng đã được đóng gói', 1, DATE_SUB(NOW(), INTERVAL 18 HOUR)),
(3, 'shipped', 'Đơn hàng đã được gửi đi với mã vận đơn VN987654321', 1, DATE_SUB(NOW(), INTERVAL 16 HOUR));
*/

-- ======================================================
-- BƯỚC 3: XÁC NHẬN THIẾT LẬP
-- ======================================================

-- Kiểm tra bảng orders đã được cập nhật
SELECT 'Checking orders table structure...' AS status;
DESCRIBE orders;

-- Kiểm tra bảng order_status_history đã được tạo
SELECT 'Checking order_status_history table...' AS status;
DESCRIBE order_status_history;

-- Kiểm tra số lượng đơn hàng theo trạng thái
SELECT 'Order status summary...' AS status;
SELECT 
    status,
    COUNT(*) as count,
    DATE(MIN(created_at)) as earliest_order,
    DATE(MAX(created_at)) as latest_order
FROM orders 
GROUP BY status 
ORDER BY count DESC;

-- Kiểm tra lịch sử trạng thái
SELECT 'Status history summary...' AS status;
SELECT 
    status,
    COUNT(*) as change_count,
    DATE(MIN(changed_at)) as first_change,
    DATE(MAX(changed_at)) as last_change
FROM order_status_history 
GROUP BY status 
ORDER BY change_count DESC;

-- ======================================================
-- HOÀN THÀNH MIGRATION
-- ======================================================

SELECT '✅ Order Status Management Migration Completed Successfully!' AS result;
SELECT '📌 Next steps:' AS info;
SELECT '1. Test order status workflow in admin panel' AS step1;
SELECT '2. Visit /webbanhang/Cart/ordersByStatus to manage orders' AS step2;
SELECT '3. Create test orders to verify tracking system' AS step3;
SELECT '4. Check timeline display in order details' AS step4;
