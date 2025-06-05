-- Migration: Cập nhật hệ thống quản lý trạng thái đơn hàng
-- Chạy file này trong phpMyAdmin hoặc MySQL Workbench

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
