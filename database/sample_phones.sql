-- Thêm danh mục điện thoại
INSERT INTO categories (name, description) 
VALUES ('Điện thoại di động', 'Các loại điện thoại di động thông minh từ các nhãn hiệu nổi tiếng');

-- Lấy ID của danh mục vừa thêm
SET @category_id = LAST_INSERT_ID();

-- Thêm 10 sản phẩm điện thoại
INSERT INTO products (name, description, price, category_id, image) VALUES 
('iPhone 15 Pro Max', 'Điện thoại iPhone 15 Pro Max mới nhất với chip A17 Pro, camera 48MP và màn hình Super Retina XDR 6.7 inch. Pin siêu bền, chống nước IP68.', 28990000, @category_id, 'iphone15promax.jpg'),

('Samsung Galaxy S24 Ultra', 'Samsung Galaxy S24 Ultra với chip Snapdragon 8 Gen 3, camera 200MP, màn hình Dynamic AMOLED 2X 6.8 inch. Hỗ trợ bút S Pen và AI tiên tiến.', 26990000, @category_id, 'samsungs24ultra.jpg'),

('Xiaomi 14 Ultra', 'Xiaomi 14 Ultra với chip Snapdragon 8 Gen 3, hệ thống 4 camera Leica 50MP, màn hình AMOLED 6.73 inch. Pin 5000mAh với sạc nhanh 90W.', 21990000, @category_id, 'xiaomi14ultra.jpg'),

('Google Pixel 8 Pro', 'Google Pixel 8 Pro với chip Tensor G3, camera 50MP với khả năng chụp đêm xuất sắc. Màn hình OLED 6.7 inch 120Hz, pin 5000mAh.', 19990000, @category_id, 'pixel8pro.jpg'),

('OPPO Find X7 Ultra', 'OPPO Find X7 Ultra với camera Hasselblad 50MP, chip Dimensity 9300, màn hình AMOLED 6.8 inch. Sạc nhanh SUPERVOOC 100W.', 20990000, @category_id, 'oppofindx7.jpg'),

('Vivo X100 Pro', 'Vivo X100 Pro với camera Zeiss 50MP, chip Dimensity 9300, màn hình AMOLED cong 6.78 inch. Pin 5400mAh với sạc nhanh 100W.', 18990000, @category_id, 'vivox100pro.jpg'),

('Nothing Phone (2)', 'Nothing Phone (2) với thiết kế Glyph Interface độc đáo, chip Snapdragon 8+ Gen 1, màn hình OLED 6.7 inch. Hệ điều hành Nothing OS.', 13990000, @category_id, 'nothingphone2.jpg'),

('OnePlus 12', 'OnePlus 12 với chip Snapdragon 8 Gen 3, camera Hasselblad 50MP, màn hình AMOLED 6.82 inch. Sạc nhanh 100W và pin 5400mAh.', 17990000, @category_id, 'oneplus12.jpg'),

('Realme GT 5 Pro', 'Realme GT 5 Pro với chip Snapdragon 8 Gen 3, camera Sony IMX890 50MP, màn hình AMOLED 6.78 inch. Pin 5400mAh với sạc nhanh 100W.', 14990000, @category_id, 'realmegt5pro.jpg'),

('Asus ROG Phone 8 Pro', 'Asus ROG Phone 8 Pro - điện thoại gaming với chip Snapdragon 8 Gen 3, màn hình AMOLED 6.78 inch 165Hz. Hệ thống tản nhiệt GameCool 8, pin 5800mAh.', 24990000, @category_id, 'asusrogphone8.jpg');
