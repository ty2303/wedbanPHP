-- Dữ liệu mẫu cho test hệ thống quản lý trạng thái đơn hàng
-- Chạy sau khi đã chạy order_management_upgrade.sql

-- Cập nhật một số đơn hàng hiện tại với các trạng thái khác nhau để test
UPDATE orders SET status = 'confirmed', admin_notes = 'Đơn hàng đã được xác nhận và sẽ được xử lý trong 1-2 ngày làm việc' WHERE id = 1;
UPDATE orders SET status = 'processing', tracking_number = 'VN123456789', estimated_delivery = DATE_ADD(CURDATE(), INTERVAL 3 DAY) WHERE id = 2;
UPDATE orders SET status = 'shipped', tracking_number = 'VN987654321', estimated_delivery = DATE_ADD(CURDATE(), INTERVAL 1 DAY) WHERE id = 3;

-- Thêm lịch sử trạng thái cho các đơn hàng test
INSERT INTO order_status_history (order_id, status, notes, changed_by, changed_at) VALUES
(1, 'confirmed', 'Đơn hàng đã được xác nhận bởi admin', 1, NOW()),
(2, 'confirmed', 'Đơn hàng đã được xác nhận', 1, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 'processing', 'Bắt đầu xử lý và đóng gói đơn hàng', 1, DATE_SUB(NOW(), INTERVAL 1 HOUR)),
(3, 'confirmed', 'Đơn hàng đã được xác nhận', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 'processing', 'Đang xử lý đơn hàng', 1, DATE_SUB(NOW(), INTERVAL 20 HOUR)),
(3, 'packed', 'Đơn hàng đã được đóng gói', 1, DATE_SUB(NOW(), INTERVAL 18 HOUR)),
(3, 'shipped', 'Đơn hàng đã được gửi đi với mã vận đơn VN987654321', 1, DATE_SUB(NOW(), INTERVAL 16 HOUR));

-- Tạo thêm một vài đơn hàng mẫu với trạng thái khác nhau (tùy chọn)
-- Bỏ comment nếu muốn tạo thêm đơn hàng test
/*
INSERT INTO orders (user_id, name, email, phone, address, subtotal, discount_amount, total_amount, status, admin_notes, created_at) VALUES
(NULL, 'Nguyễn Test 1', 'test1@example.com', '0987654321', '123 Test Street, Test City', 500000, 0, 500000, 'pending', '', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(NULL, 'Nguyễn Test 2', 'test2@example.com', '0987654322', '456 Test Avenue, Test City', 750000, 75000, 675000, 'cancelled', 'Khách hàng yêu cầu hủy đơn', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(NULL, 'Nguyễn Test 3', 'test3@example.com', '0987654323', '789 Test Road, Test City', 1200000, 0, 1200000, 'delivered', 'Đơn hàng đã được giao thành công', DATE_SUB(NOW(), INTERVAL 3 DAY));
*/
