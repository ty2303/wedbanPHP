<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/OrderModel.php');
require_once('app/models/VoucherModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/middleware/AuthMiddleware.php');

class CartController
{
    private $db;
    private $productModel;
    private $orderModel;
    private $voucherModel;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->orderModel = new OrderModel($this->db);
        $this->voucherModel = new VoucherModel($this->db);
        
        // Initialize session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }    public function index()
    {
        // Ngăn admin truy cập trang giỏ hàng customer
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần sử dụng giỏ hàng. Bạn có thể quản lý đơn hàng từ trang quản lý.');
            header('Location: /webbanhang/Cart/ordersByStatus');
            exit;
        }

        $cart_items = $this->getCartItems();
        $subtotal = $this->getCartTotal();
        $discount = 0;
        $total = $subtotal;
        
        // Apply voucher discount if exists
        if (isset($_SESSION['applied_voucher'])) {
            $discount = $_SESSION['applied_voucher']['discount'];
            $total = $subtotal - $discount;
        }
        
        include 'app/views/cart/index.php';
    }
      public function add($product_id, $quantity = 1)
    {
        // Ngăn admin sử dụng chức năng thêm vào giỏ hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            // Nếu là AJAX request, trả về JSON response
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Admin không cần sử dụng giỏ hàng. Bạn có thể quản lý sản phẩm từ trang quản lý.'
                ]);
                exit;
            }
            
            SessionHelper::setFlash('info', 'Admin không cần sử dụng giỏ hàng. Bạn có thể quản lý sản phẩm từ trang quản lý.');
            header('Location: /webbanhang/Product');
            exit;
        }

        // Get the product
        $product = $this->productModel->getProductById($product_id);
        
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại!";
            header('Location: /webbanhang/Product');
            return;
        }
        
        // If from POST request, get quantity value
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quantity'])) {
            $quantity = (int)$_POST['quantity'];
        }
        
        // If product is already in cart, increase quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng!";
        
        // If request is AJAX, return JSON response
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo json_encode([
                'success' => true,
                'cart_count' => $this->getCartCount(),
                'message' => 'Đã thêm sản phẩm vào giỏ hàng!'
            ]);
            exit;
        }
        
        // Get the referrer URL or use a default
        $redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/webbanhang/Product';
        
        // Check if we should stay on the current page
        if (isset($_GET['stay_on_page']) && $_GET['stay_on_page'] === 'true') {
            header('Location: ' . $redirect_url);
        } else if (isset($_GET['redirect_back']) && $_GET['redirect_back'] === 'true') {
            header('Location: /webbanhang/Product/show/' . $product_id);
        } else {
            header('Location: /webbanhang/Cart');
        }
    }
      public function update()
    {
        // Ngăn admin sử dụng chức năng cập nhật giỏ hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần sử dụng giỏ hàng.');
            header('Location: /webbanhang/Product');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['quantities']) && is_array($_POST['quantities'])) {
                foreach ($_POST['quantities'] as $product_id => $quantity) {
                    if ($quantity > 0) {
                        $_SESSION['cart'][$product_id] = (int)$quantity;
                    } else {
                        unset($_SESSION['cart'][$product_id]);
                    }
                }
            }
            
            $_SESSION['success'] = "Giỏ hàng đã được cập nhật!";
        }
        
        header('Location: /webbanhang/Cart');
    }
      public function remove($product_id)
    {
        // Ngăn admin sử dụng chức năng xóa khỏi giỏ hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần sử dụng giỏ hàng.');
            header('Location: /webbanhang/Product');
            exit;
        }

        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = "Sản phẩm đã được xóa khỏi giỏ hàng!";
        }
        
        header('Location: /webbanhang/Cart');
    }
      public function clear()
    {
        // Ngăn admin sử dụng chức năng xóa giỏ hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần sử dụng giỏ hàng.');
            header('Location: /webbanhang/Product');
            exit;
        }

        $_SESSION['cart'] = [];
        $_SESSION['success'] = "Giỏ hàng đã được xóa!";
        header('Location: /webbanhang/Cart');
    }
    public function checkout()
    {
        // Ngăn admin truy cập trang checkout
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần thực hiện checkout. Bạn có thể quản lý đơn hàng từ trang quản lý.');
            header('Location: /webbanhang/Cart/ordersByStatus');
            exit;
        }

        $cart_items = $this->getCartItems();
        $subtotal = $this->getCartTotal();
        $discount = 0;
        $total = $subtotal;
        
        // Apply voucher discount if exists
        if (isset($_SESSION['applied_voucher'])) {
            $discount = $_SESSION['applied_voucher']['discount'];
            $total = $subtotal - $discount;
        }
        
        if (empty($cart_items)) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống!";
            header('Location: /webbanhang/Cart');
            return;
        }
        
        include 'app/views/cart/checkout.php';
    }
      public function placeOrder()
    {
        // Ngăn admin thực hiện đặt hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('error', 'Admin không thể đặt hàng thông qua hệ thống customer.');
            header('Location: /webbanhang/Cart/ordersByStatus');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /webbanhang/Cart/checkout');
            return;
        }
        
        $cart_items = $this->getCartItems();
        if (empty($cart_items)) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống!";
            header('Location: /webbanhang/Cart');
            return;
        }
        
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        // Validate input
        $errors = [];
        if (empty($name)) {
            $errors[] = "Vui lòng nhập họ tên!";
        }
        if (empty($email)) {
            $errors[] = "Vui lòng nhập email!";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ!";
        }
        if (empty($phone)) {
            $errors[] = "Vui lòng nhập số điện thoại!";
        } else if (!preg_match("/^[0-9]{10,11}$/", $phone)) {
            $errors[] = "Số điện thoại không hợp lệ!";
        }
        if (empty($address)) {
            $errors[] = "Vui lòng nhập địa chỉ giao hàng!";
        }
        
        if (!empty($errors)) {
            $_SESSION['checkout_errors'] = $errors;
            $_SESSION['checkout_data'] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address
            ];
            header('Location: /webbanhang/Cart/checkout');
            return;
        }
          // Get user_id if logged in
        $user_id = null;
        if (SessionHelper::isLoggedIn()) {
            $user_id = SessionHelper::getUserId();
        }
        
        // Prepare voucher data if applied
        $voucher_data = null;
        if (isset($_SESSION['applied_voucher'])) {
            $voucher_data = [
                'voucher_id' => $_SESSION['applied_voucher']['id'],
                'voucher_code' => $_SESSION['applied_voucher']['code'],
                'discount_amount' => $_SESSION['voucher_discount']
            ];
        }
        
        // Create the order
        $order_id = $this->orderModel->createOrder($name, $email, $phone, $address, $cart_items, $user_id, $voucher_data);
        
        if ($order_id) {
            // Record voucher usage if applied
            if ($voucher_data) {
                $this->voucherModel->recordVoucherUsage(
                    $voucher_data['voucher_id'], 
                    $order_id, 
                    $user_id, 
                    $voucher_data['discount_amount']
                );
            }
            
            // Clear the cart and voucher after successful order
            $_SESSION['cart'] = [];
            unset($_SESSION['applied_voucher']);
            unset($_SESSION['voucher_discount']);
            
            // Redirect to success page
            header('Location: /webbanhang/Cart/success/' . $order_id);
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra trong quá trình đặt hàng. Vui lòng thử lại!";
            header('Location: /webbanhang/Cart/checkout');
        }
    }
    
    public function success($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        $order_details = $this->orderModel->getOrderDetails($order_id);
        
        if (!$order || !$order_details) {
            header('Location: /webbanhang/Product');
            return;
        }
        
        include 'app/views/cart/success.php';
    }
    
    public function orders()
    {
        // Chỉ admin và staff mới có thể xem tất cả đơn hàng
        // Khách hàng chỉ có thể xem đơn hàng của mình
        
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            $orders = $this->orderModel->getOrders();
        } else {
            // Đảm bảo người dùng đã đăng nhập
            AuthMiddleware::requireLogin();
            $user_id = SessionHelper::getUserId();
            $orders = $this->orderModel->getUserOrders($user_id);
        }
        
        include 'app/views/cart/orders.php';
    }
      public function orderDetails($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
          // Đảm bảo chỉ admin, staff hoặc chủ đơn hàng mới có thể xem chi tiết
        if (!SessionHelper::isAdmin() && !SessionHelper::isStaff()) {
            // Đảm bảo người dùng đã đăng nhập
            AuthMiddleware::requireLogin();
            
            $user_id = SessionHelper::getUserId();
            if ($order && $order->user_id != $user_id) {
                SessionHelper::setFlash('error', 'Bạn không có quyền xem đơn hàng này!');
                header('Location: /webbanhang/Cart/orders');
                return;
            }
        }
        
        $order_details = $this->orderModel->getOrderDetails($order_id);
        $status_history = $this->orderModel->getOrderStatusHistory($order_id);
        
        if (!$order || !$order_details) {
            header('Location: /webbanhang/Cart/orders');
            return;
        }
        
        include 'app/views/cart/order_details.php';
    }
    
    private function getCartItems()
    {
        $items = [];
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product = $this->productModel->getProductById($product_id);
                if ($product) {
                    $items[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'subtotal' => $product->price * $quantity
                    ];
                }
            }
        }
        return $items;
    }
    
    private function getCartTotal()
    {
        $total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product = $this->productModel->getProductById($product_id);
                if ($product) {
                    $total += $product->price * $quantity;
                }
            }
        }
        return $total;
    }
    
    private function getCartCount()
    {
        $count = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $quantity) {
                $count += $quantity;
            }        }
        return $count;
    }
      public function applyVoucher()
    {
        // Ngăn admin sử dụng voucher trong giỏ hàng
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                echo json_encode(['success' => false, 'message' => 'Admin không cần sử dụng voucher.']);
                exit;
            }
            SessionHelper::setFlash('info', 'Admin không cần sử dụng voucher.');
            header('Location: /webbanhang/Product');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $code = strtoupper(trim($_POST['voucher_code'] ?? ''));
            $cartTotal = $this->getCartTotal();
            
            // Get product IDs from cart
            $productIds = [];
            if (isset($_SESSION['cart'])) {
                $productIds = array_keys($_SESSION['cart']);
            }
            
            $result = $this->voucherModel->validateVoucher($code, $cartTotal, $productIds);
            
            if ($result['valid']) {
                $discount = $this->voucherModel->calculateDiscount($result['voucher'], $cartTotal);
                $_SESSION['applied_voucher'] = [
                    'id' => $result['voucher']->id,
                    'code' => $result['voucher']->code,
                    'name' => $result['voucher']->name,
                    'discount' => $discount
                ];
                
                // Return JSON response for AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode([
                        'success' => true, 
                        'discount' => $discount, 
                        'message' => 'Áp dụng voucher thành công!',
                        'voucher_name' => $result['voucher']->name
                    ]);
                    exit;
                }
                
                $_SESSION['success'] = 'Áp dụng voucher thành công!';
            } else {
                // Return JSON response for AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['success' => false, 'message' => $result['message']]);
                    exit;
                }
                
                $_SESSION['error'] = $result['message'];
            }
        }
        
        header('Location: /webbanhang/Cart');
    }
      public function removeVoucher()
    {
        // Ngăn admin sử dụng voucher
        if (SessionHelper::isAdmin() || SessionHelper::isStaff()) {
            SessionHelper::setFlash('info', 'Admin không cần sử dụng voucher.');
            header('Location: /webbanhang/Product');
            exit;
        }

        unset($_SESSION['applied_voucher']);
        $_SESSION['success'] = 'Đã hủy voucher!';
        
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
            header('Location: /webbanhang/Cart');
        }
    }
    
    /**
     * Cập nhật trạng thái đơn hàng (chỉ admin/staff)
     */
    public function updateOrderStatus($order_id)
    {
        // Chỉ admin và staff mới có thể cập nhật trạng thái
        if (!SessionHelper::isAdmin() && !SessionHelper::isStaff()) {
            SessionHelper::setFlash('error', 'Bạn không có quyền thực hiện thao tác này!');
            header('Location: /webbanhang/Cart/orders');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_status = $_POST['status'] ?? '';
            $notes = $_POST['notes'] ?? '';
            $tracking_number = $_POST['tracking_number'] ?? '';
            $estimated_delivery = $_POST['estimated_delivery'] ?? '';
              $order = $this->orderModel->getOrderById($order_id);
            if (!$order) {
                SessionHelper::setFlash('error', 'Đơn hàng không tồn tại!');
                header('Location: /webbanhang/Cart/orders');
                return;
            }
            
            // Kiểm tra trạng thái có hợp lệ không
            if (!array_key_exists($new_status, OrderModel::$statusLabels)) {
                SessionHelper::setFlash('error', 'Trạng thái không hợp lệ!');
                header('Location: /webbanhang/Cart/orderDetails/' . $order_id);
                return;
            }
            
            // Kiểm tra nếu trạng thái mới giống trạng thái hiện tại
            if ($order->status === $new_status) {
                SessionHelper::setFlash('warning', 'Trạng thái đã là: ' . OrderModel::$statusLabels[$new_status]);
                header('Location: /webbanhang/Cart/orderDetails/' . $order_id);
                return;
            }
            
            $admin_id = SessionHelper::getUserId();
            
            if ($this->orderModel->updateOrderStatus($order_id, $new_status, $notes, $admin_id, $tracking_number, $estimated_delivery)) {
                SessionHelper::setFlash('success', 'Cập nhật trạng thái đơn hàng thành công!');
            } else {
                SessionHelper::setFlash('error', 'Có lỗi xảy ra khi cập nhật trạng thái!');
            }
            
            header('Location: /webbanhang/Cart/orderDetails/' . $order_id);
            return;
        }
        
        header('Location: /webbanhang/Cart/orderDetails/' . $order_id);
    }
    
    /**
     * Lấy danh sách đơn hàng theo trạng thái (cho admin)
     */
    public function ordersByStatus($status = null)
    {
        // Chỉ admin và staff mới có thể xem
        if (!SessionHelper::isAdmin() && !SessionHelper::isStaff()) {
            AuthMiddleware::requireLogin();
            header('Location: /webbanhang/Cart/orders');
            return;
        }
        
        $orders = $this->orderModel->getOrdersByStatus($status);
        $stats = $this->orderModel->getOrderStatusStats();
        $current_status = $status;
        
        include 'app/views/cart/orders_by_status.php';
    }
}
?>