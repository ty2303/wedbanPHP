-- Cập nhật hệ thống quản lý trạng thái đơn hàng
-- Thêm cột status vào bảng orders
ALTER TABLE orders ADD COLUMN status ENUM(
    'pending',      -- Chờ xử lý
    'confirmed',    -- Đã xác nhận
    'processing',   -- Đang xử lý
    'packed',       -- Đã đóng gói
    'shipped',      -- Đã gửi hàng
    'delivered',    -- Đã giao hàng
    'cancelled',    -- Đã hủy
    'returned'      -- Đã trả hàng
) DEFAULT 'pending' AFTER total_amount;

-- Thêm cột ghi chú admin
ALTER TABLE orders ADD COLUMN admin_notes TEXT AFTER status;

-- Thêm cột estimated_delivery
ALTER TABLE orders ADD COLUMN estimated_delivery DATE AFTER admin_notes;

-- Thêm cột tracking_number cho mã vận đơn
ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(100) AFTER estimated_delivery;

-- Tạo bảng order_status_history để theo dõi lịch sử thay đổi trạng thái
CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    status VARCHAR(50) NOT NULL,
    notes TEXT,
    changed_by INT,
    changed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Cập nhật tất cả đơn hàng hiện tại thành trạng thái 'pending'
UPDATE orders SET status = 'pending' WHERE status IS NULL;

-- Thêm lịch sử cho các đơn hàng hiện tại
INSERT INTO order_status_history (order_id, status, notes, changed_at)
SELECT id, 'pending', 'Đơn hàng được tạo', created_at
FROM orders;
