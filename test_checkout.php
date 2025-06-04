<?php
// Test cart và checkout functionality
// Truy cập: http://localhost:85/webbanhang/test_checkout.php

session_start();
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/OrderModel.php');
require_once('app/models/VoucherModel.php');

echo "<h1>Test Checkout System</h1>";
echo "<hr>";

try {
    $db = (new Database())->getConnection();
    $productModel = new ProductModel($db);
    $orderModel = new OrderModel($db);
    
    echo "<h2>1. Kiểm tra database connection</h2>";
    echo "✅ Database connected successfully!<br><br>";
    
    echo "<h2>2. Kiểm tra giỏ hàng hiện tại</h2>";
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        echo "Giỏ hàng có " . count($_SESSION['cart']) . " loại sản phẩm:<br>";
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            echo "- Product ID: $product_id, Quantity: $quantity<br>";
        }
    } else {
        echo "Giỏ hàng trống. Thêm sản phẩm vào giỏ hàng...<br>";
        
        // Lấy sản phẩm đầu tiên để test
        $products = $productModel->getProducts();
        if (!empty($products)) {
            $testProduct = $products[0];
            $_SESSION['cart'][$testProduct->id] = 2; // Thêm 2 sản phẩm
            echo "✅ Đã thêm sản phẩm '{$testProduct->name}' (ID: {$testProduct->id}) x2 vào giỏ hàng<br>";
        } else {
            echo "❌ Không có sản phẩm nào trong database!<br>";
        }
    }
    echo "<br>";
    
    echo "<h2>3. Kiểm tra hàm getCartItems</h2>";
    // Simulate CartController's getCartItems method
    $cart_items = [];
    if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product = $productModel->getProductById($product_id);
            if ($product) {
                $cart_items[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity
                ];
            }
        }
    }
    
    if (!empty($cart_items)) {
        echo "✅ Cart items được load thành công:<br>";
        $total = 0;
        foreach ($cart_items as $item) {
            echo "- {$item['product']->name}: {$item['quantity']} x " . number_format($item['product']->price, 0, ',', '.') . "đ = " . number_format($item['subtotal'], 0, ',', '.') . "đ<br>";
            $total += $item['subtotal'];
        }
        echo "<strong>Tổng: " . number_format($total, 0, ',', '.') . "đ</strong><br><br>";
    } else {
        echo "❌ Không thể load cart items!<br><br>";
    }
    
    echo "<h2>4. Test tạo đơn hàng</h2>";
    if (!empty($cart_items)) {
        $test_name = "Nguyen Van Test";
        $test_email = "test@example.com";
        $test_phone = "0123456789";
        $test_address = "123 Test Street, Test City";
        
        echo "Đang tạo đơn hàng test với thông tin:<br>";
        echo "- Tên: $test_name<br>";
        echo "- Email: $test_email<br>";
        echo "- Phone: $test_phone<br>";
        echo "- Address: $test_address<br><br>";
        
        $order_id = $orderModel->createOrder($test_name, $test_email, $test_phone, $test_address, $cart_items);
        
        if ($order_id) {
            echo "✅ Đơn hàng được tạo thành công! Order ID: $order_id<br>";
            
            // Kiểm tra đơn hàng vừa tạo
            $order = $orderModel->getOrderById($order_id);
            $order_details = $orderModel->getOrderDetails($order_id);
            
            echo "Chi tiết đơn hàng:<br>";
            echo "- ID: {$order->id}<br>";
            echo "- Tên: {$order->name}<br>";
            echo "- Tổng tiền: " . number_format($order->total_amount, 0, ',', '.') . "đ<br>";
            echo "- Số sản phẩm: " . count($order_details) . "<br>";
            
        } else {
            echo "❌ Lỗi tạo đơn hàng!<br>";
            
            // Kiểm tra lỗi từ PHP error log
            $error_log = error_get_last();
            if ($error_log) {
                echo "Error log: " . $error_log['message'] . "<br>";
            }
        }
    } else {
        echo "❌ Không thể test tạo đơn hàng vì giỏ hàng trống!<br>";
    }
    
    echo "<br><h2>5. Navigation Links</h2>";
    echo "<a href='/webbanhang/Product' target='_blank'>🔗 Thêm sản phẩm vào giỏ hàng</a><br>";
    echo "<a href='/webbanhang/Cart' target='_blank'>🔗 Xem giỏ hàng</a><br>";
    echo "<a href='/webbanhang/Cart/checkout' target='_blank'>🔗 Checkout</a><br>";
    echo "<a href='/webbanhang/Cart/orders' target='_blank'>🔗 Danh sách đơn hàng</a><br>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
}
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
a { color: #007bff; text-decoration: none; margin-right: 10px; }
a:hover { text-decoration: underline; }
</style>
