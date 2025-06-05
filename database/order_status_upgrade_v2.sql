-- Cập nhật hệ thống quản lý trạng thái đơn hàng (Version 2)

-- Cập nhật ENUM cho cột status hiện tại
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

-- Kiểm tra và thêm cột admin_notes nếu chưa có
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'webbanhang' 
AND TABLE_NAME = 'orders' 
AND COLUMN_NAME = 'admin_notes';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE orders ADD COLUMN admin_notes TEXT AFTER status', 
    'SELECT "admin_notes column already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Kiểm tra và thêm cột estimated_delivery nếu chưa có
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'webbanhang' 
AND TABLE_NAME = 'orders' 
AND COLUMN_NAME = 'estimated_delivery';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE orders ADD COLUMN estimated_delivery DATE AFTER admin_notes', 
    'SELECT "estimated_delivery column already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Kiểm tra và thêm cột tracking_number nếu chưa có
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = 'webbanhang' 
AND TABLE_NAME = 'orders' 
AND COLUMN_NAME = 'tracking_number';

SET @sql = IF(@col_exists = 0, 
    'ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(100) AFTER estimated_delivery', 
    'SELECT "tracking_number column already exists"');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Kiểm tra và tạo bảng order_status_history nếu chưa có
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

-- Cập nhật trạng thái đơn hàng hiện tại từ 'completed' thành 'delivered'
UPDATE orders SET status = 'delivered' WHERE status = 'completed';

-- Thêm lịch sử cho các đơn hàng hiện tại (chỉ nếu chưa có)
INSERT IGNORE INTO order_status_history (order_id, status, notes, changed_at)
SELECT id, status, 'Đơn hàng được tạo', created_at
FROM orders;
