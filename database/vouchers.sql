-- ========================================
-- BẢNG VOUCHERS (Mã giảm giá)
-- ========================================
CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    discount_type ENUM('percentage', 'fixed') NOT NULL DEFAULT 'percentage',
    discount_value DECIMAL(10, 2) NOT NULL,
    min_order_amount DECIMAL(10, 2) DEFAULT 0,
    max_discount_amount DECIMAL(10, 2) NULL,
    applies_to ENUM('all_products', 'specific_products', 'specific_categories') NOT NULL DEFAULT 'all_products',
    product_ids JSON NULL,
    category_ids JSON NULL,
    usage_limit INT NULL,
    used_count INT DEFAULT 0,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ========================================
-- BẢNG VOUCHER_USAGE (Lịch sử sử dụng voucher)
-- ========================================
CREATE TABLE voucher_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    voucher_id INT NOT NULL,
    order_id INT NOT NULL,
    user_id INT NULL,
    discount_amount DECIMAL(10, 2) NOT NULL,
    used_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ========================================
-- DỮ LIỆU MẪU CHO VOUCHERS
-- ========================================
INSERT INTO vouchers (code, name, description, discount_type, discount_value, min_order_amount, max_discount_amount, applies_to, start_date, end_date, is_active) VALUES
('WELCOME10', 'Voucher chào mừng', 'Giảm 10% cho khách hàng mới', 'percentage', 10.00, 100000, 50000, 'all_products', '2024-01-01 00:00:00', '2024-12-31 23:59:59', TRUE),
('FREESHIP', 'Miễn phí vận chuyển', 'Giảm 30.000đ phí vận chuyển', 'fixed', 30000.00, 200000, NULL, 'all_products', '2024-01-01 00:00:00', '2024-12-31 23:59:59', TRUE),
('SUMMER20', 'Voucher mùa hè', 'Giảm 20% tối đa 100.000đ', 'percentage', 20.00, 500000, 100000, 'all_products', '2024-06-01 00:00:00', '2024-08-31 23:59:59', TRUE);
