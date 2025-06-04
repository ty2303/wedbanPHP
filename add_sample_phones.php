<?php
// Script để thêm dữ liệu mẫu điện thoại vào cơ sở dữ liệu
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/ProductModel.php');

// Kết nối đến cơ sở dữ liệu
$db = (new Database())->getConnection();
$categoryModel = new CategoryModel($db);
$productModel = new ProductModel($db);

echo "Bắt đầu thêm dữ liệu mẫu...\n";

// Thêm danh mục điện thoại
$category_id = $categoryModel->addCategory('Điện thoại di động', 'Các loại điện thoại di động thông minh từ các nhãn hiệu nổi tiếng');

if (is_array($category_id)) {
    echo "Lỗi khi tạo danh mục: ";
    print_r($category_id);
    exit;
}

echo "Đã tạo danh mục với ID: " . $category_id . "\n";

// Danh sách 10 điện thoại mẫu
$phones = [
    [
        'name' => 'iPhone 15 Pro Max',
        'description' => 'Điện thoại iPhone 15 Pro Max mới nhất với chip A17 Pro, camera 48MP và màn hình Super Retina XDR 6.7 inch. Pin siêu bền, chống nước IP68.',
        'price' => 28990000,
        'image' => 'iphone15promax.jpg'
    ],
    [
        'name' => 'Samsung Galaxy S24 Ultra',
        'description' => 'Samsung Galaxy S24 Ultra với chip Snapdragon 8 Gen 3, camera 200MP, màn hình Dynamic AMOLED 2X 6.8 inch. Hỗ trợ bút S Pen và AI tiên tiến.',
        'price' => 26990000,
        'image' => 'samsungs24ultra.jpg'
    ],
    [
        'name' => 'Xiaomi 14 Ultra',
        'description' => 'Xiaomi 14 Ultra với chip Snapdragon 8 Gen 3, hệ thống 4 camera Leica 50MP, màn hình AMOLED 6.73 inch. Pin 5000mAh với sạc nhanh 90W.',
        'price' => 21990000,
        'image' => 'xiaomi14ultra.jpg'
    ],
    [
        'name' => 'Google Pixel 8 Pro',
        'description' => 'Google Pixel 8 Pro với chip Tensor G3, camera 50MP với khả năng chụp đêm xuất sắc. Màn hình OLED 6.7 inch 120Hz, pin 5000mAh.',
        'price' => 19990000,
        'image' => 'pixel8pro.jpg'
    ],
    [
        'name' => 'OPPO Find X7 Ultra',
        'description' => 'OPPO Find X7 Ultra với camera Hasselblad 50MP, chip Dimensity 9300, màn hình AMOLED 6.8 inch. Sạc nhanh SUPERVOOC 100W.',
        'price' => 20990000,
        'image' => 'oppofindx7.jpg'
    ],
    [
        'name' => 'Vivo X100 Pro',
        'description' => 'Vivo X100 Pro với camera Zeiss 50MP, chip Dimensity 9300, màn hình AMOLED cong 6.78 inch. Pin 5400mAh với sạc nhanh 100W.',
        'price' => 18990000,
        'image' => 'vivox100pro.jpg'
    ],
    [
        'name' => 'Nothing Phone (2)',
        'description' => 'Nothing Phone (2) với thiết kế Glyph Interface độc đáo, chip Snapdragon 8+ Gen 1, màn hình OLED 6.7 inch. Hệ điều hành Nothing OS.',
        'price' => 13990000,
        'image' => 'nothingphone2.jpg'
    ],
    [
        'name' => 'OnePlus 12',
        'description' => 'OnePlus 12 với chip Snapdragon 8 Gen 3, camera Hasselblad 50MP, màn hình AMOLED 6.82 inch. Sạc nhanh 100W và pin 5400mAh.',
        'price' => 17990000,
        'image' => 'oneplus12.jpg'
    ],
    [
        'name' => 'Realme GT 5 Pro',
        'description' => 'Realme GT 5 Pro với chip Snapdragon 8 Gen 3, camera Sony IMX890 50MP, màn hình AMOLED 6.78 inch. Pin 5400mAh với sạc nhanh 100W.',
        'price' => 14990000,
        'image' => 'realmegt5pro.jpg'
    ],
    [
        'name' => 'Asus ROG Phone 8 Pro',
        'description' => 'Asus ROG Phone 8 Pro - điện thoại gaming với chip Snapdragon 8 Gen 3, màn hình AMOLED 6.78 inch 165Hz. Hệ thống tản nhiệt GameCool 8, pin 5800mAh.',
        'price' => 24990000,
        'image' => 'asusrogphone8.jpg'
    ]
];

// Thêm sản phẩm
$count = 0;
foreach ($phones as $phone) {
    $result = $productModel->addProduct(
        $phone['name'], 
        $phone['description'], 
        $phone['price'], 
        $category_id, 
        $phone['image']
    );
    
    if ($result === true) {
        $count++;
        echo "Đã thêm sản phẩm: " . $phone['name'] . "\n";
    } else {
        echo "Lỗi khi thêm sản phẩm " . $phone['name'] . ": ";
        print_r($result);
    }
}

echo "Hoàn tất! Đã thêm " . $count . " sản phẩm điện thoại.";
?>
