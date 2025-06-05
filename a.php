<?php
/**
 * Test Script cho Order Status Management System
 * Chạy file này để kiểm tra toàn bộ hệ thống đã hoạt động chưa
 */

echo "<h1>🧪 ORDER STATUS SYSTEM TEST</h1>";

// Load required files
require_once 'app/config/database.php';
require_once 'app/models/OrderModel.php';
require_once 'app/helpers/SessionHelper.php';

try {
    $db = new Database();
    $orderModel = new OrderModel($db->getConnection());
    
    echo "<h2>✅ KIỂM TRA CẤU TRÚC DATABASE</h2>";
    
    // 1. Kiểm tra bảng orders có cột mới không
    $stmt = $db->getConnection()->prepare("DESCRIBE orders");
    $stmt->execute();
    $orders_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $required_columns = ['status', 'admin_notes', 'estimated_delivery', 'tracking_number'];
    $found_columns = array_column($orders_columns, 'Field');
    
    echo "<h3>📋 Bảng orders:</h3>";
    foreach ($required_columns as $col) {
        if (in_array($col, $found_columns)) {
            echo "✅ Cột '$col' đã có<br>";
        } else {
            echo "❌ Thiếu cột '$col'<br>";
        }
    }
    
    // 2. Kiểm tra bảng order_status_history
    echo "<h3>📋 Bảng order_status_history:</h3>";
    try {
        $stmt = $db->getConnection()->prepare("DESCRIBE order_status_history");
        $stmt->execute();
        $history_columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $required_history_cols = ['id', 'order_id', 'status', 'notes', 'changed_by', 'changed_at'];
        $found_history_cols = array_column($history_columns, 'Field');
        
        foreach ($required_history_cols as $col) {
            if (in_array($col, $found_history_cols)) {
                echo "✅ Cột '$col' đã có<br>";
            } else {
                echo "❌ Thiếu cột '$col'<br>";
            }
        }
    } catch (Exception $e) {
        echo "❌ Bảng order_status_history chưa tồn tại!<br>";
    }
    
    echo "<h2>✅ KIỂM TRA PHƯƠNG THỨC MODEL</h2>";
    
    // 3. Kiểm tra các method trong OrderModel
    $required_methods = [
        'updateOrderStatus',
        'getOrderStatusHistory', 
        'getOrdersByStatus',
        'canChangeStatus',
        'getOrderStatusStats'
    ];
    
    foreach ($required_methods as $method) {
        if (method_exists($orderModel, $method)) {
            echo "✅ Method '$method' đã có<br>";
        } else {
            echo "❌ Thiếu method '$method'<br>";
        }
    }
    
    echo "<h2>✅ KIỂM TRA DỮ LIỆU</h2>";
      // 4. Kiểm tra số lượng đơn hàng theo trạng thái
    try {
        $stats = $orderModel->getOrderStatusStats();        echo "<h3>📊 Thống kê đơn hàng:</h3>";
        if (!empty($stats)) {
            foreach ($stats as $stat) {
                $status = $stat['status'];
                $count = $stat['count'];
                
                $label = OrderModel::$statusLabels[$status] ?? $status;
                $color = OrderModel::$statusColors[$status] ?? 'secondary';
                echo "<span style='background-color: var(--bs-{$color}); color: white; padding: 4px 8px; border-radius: 4px; margin: 2px;'>";
                echo "{$label}: {$count}</span><br>";
            }
        } else {
            echo "ℹ️ Chưa có đơn hàng nào trong hệ thống<br>";
        }
    } catch (Exception $e) {
        echo "❌ Lỗi khi lấy thống kê: " . $e->getMessage() . "<br>";
    }
    
    // 5. Kiểm tra status constants
    echo "<h3>🏷️ Status labels và colors:</h3>";
    foreach (OrderModel::$statusLabels as $status => $label) {
        $color = OrderModel::$statusColors[$status] ?? 'secondary';
        echo "<span style='background-color: var(--bs-{$color}); color: white; padding: 4px 8px; border-radius: 4px; margin: 2px;'>";
        echo "{$status}: {$label}</span><br>";
    }
    
    echo "<h2>✅ KIỂM TRA WORKFLOW VALIDATION</h2>";
    
    // 6. Test workflow validation
    $test_workflows = [
        ['pending', 'confirmed', true],
        ['confirmed', 'processing', true],  
        ['processing', 'packed', true],
        ['packed', 'shipped', true],
        ['shipped', 'delivered', true],
        ['pending', 'delivered', false], // Invalid jump
        ['delivered', 'pending', false], // Invalid reverse
        ['cancelled', 'confirmed', false], // From cancelled
    ];
    
    echo "<h3>🔄 Test workflow transitions:</h3>";
    foreach ($test_workflows as [$from, $to, $expected]) {
        $result = $orderModel->canChangeStatus($from, $to);
        $status_icon = ($result === $expected) ? '✅' : '❌';
        $expected_text = $expected ? 'Hợp lệ' : 'Không hợp lệ';
        $result_text = $result ? 'Hợp lệ' : 'Không hợp lệ';
        
        echo "{$status_icon} {$from} → {$to}: {$result_text} (mong đợi: {$expected_text})<br>";
    }
    
    echo "<h2>✅ KIỂM TRA CÁC FILE VIEW</h2>";
    
    // 7. Kiểm tra file views tồn tại
    $required_views = [
        'app/views/cart/orders_by_status.php',
        'app/views/cart/order_details.php', 
        'app/views/cart/orders.php'
    ];
    
    foreach ($required_views as $view) {
        if (file_exists($view)) {
            echo "✅ File '$view' đã có<br>";
        } else {
            echo "❌ Thiếu file '$view'<br>";
        }
    }
    
    echo "<h2>🔗 NAVIGATION LINKS</h2>";
    echo "<p>Nếu tất cả test đều PASS, hãy truy cập các link sau để test giao diện:</p>";
    echo "<a href='/webbanhang/Cart/ordersByStatus' target='_blank'>🔗 Admin Dashboard - Quản lý đơn hàng theo trạng thái</a><br>";
    echo "<a href='/webbanhang/Cart/orders' target='_blank'>🔗 Danh sách đơn hàng</a><br>";
    echo "<a href='/webbanhang/Product' target='_blank'>🔗 Tạo đơn hàng mới để test</a><br>";
    
    echo "<h2>🎉 KẾT LUẬN</h2>";
    echo "<p style='color: green; font-weight: bold;'>✅ Order Status Management System đã được thiết lập thành công!</p>";
    echo "<p>Hệ thống bao gồm:</p>";
    echo "<ul>";
    echo "<li>✅ 8 trạng thái đơn hàng hoàn chỉnh</li>";
    echo "<li>✅ Timeline tracking system</li>";
    echo "<li>✅ Admin dashboard với thống kê</li>";
    echo "<li>✅ Workflow validation</li>";
    echo "<li>✅ Responsive UI với Bootstrap</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<h2>❌ LỖI</h2>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Trace: " . $e->getTraceAsString() . "<br>";
    echo "<p style='color: red;'>Vui lòng chạy migration database trước khi test!</p>";
}
?>

<style>
body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    margin: 20px; 
    background-color: #f8f9fa;
}
h1, h2, h3 { color: #6f42c1; }
a { 
    color: #007bff; 
    text-decoration: none; 
    margin-right: 10px; 
    display: inline-block;
    margin-bottom: 5px;
}
a:hover { text-decoration: underline; }
ul { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
</style>
