<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/OrderModel.php');

class CartController
{
    private $db;
    private $productModel;
    private $orderModel;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->orderModel = new OrderModel($this->db);
        
        // Initialize session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize cart if it doesn't exist
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }
    
    public function index()
    {
        $cart_items = $this->getCartItems();
        $total = $this->getCartTotal();
        include 'app/views/cart/index.php';
    }
    
    public function add($product_id, $quantity = 1)
    {
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
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            $_SESSION['success'] = "Sản phẩm đã được xóa khỏi giỏ hàng!";
        }
        
        header('Location: /webbanhang/Cart');
    }
    
    public function clear()
    {
        $_SESSION['cart'] = [];
        $_SESSION['success'] = "Giỏ hàng đã được xóa!";
        header('Location: /webbanhang/Cart');
    }
    
    public function checkout()
    {
        $cart_items = $this->getCartItems();
        $total = $this->getCartTotal();
        
        if (empty($cart_items)) {
            $_SESSION['error'] = "Giỏ hàng của bạn đang trống!";
            header('Location: /webbanhang/Cart');
            return;
        }
        
        include 'app/views/cart/checkout.php';
    }
    
    public function placeOrder()
    {
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
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';
        
        // Validate input
        $errors = [];
        if (empty($name)) {
            $errors[] = "Vui lòng nhập họ tên!";
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
                'phone' => $phone,
                'address' => $address
            ];
            header('Location: /webbanhang/Cart/checkout');
            return;
        }
        
        // Create the order
        $order_id = $this->orderModel->createOrder($name, $phone, $address, $cart_items);
        
        if ($order_id) {
            // Clear the cart after successful order
            $_SESSION['cart'] = [];
            
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
        $orders = $this->orderModel->getOrders();
        include 'app/views/cart/orders.php';
    }
    
    public function orderDetails($order_id)
    {
        $order = $this->orderModel->getOrderById($order_id);
        $order_details = $this->orderModel->getOrderDetails($order_id);
        
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
            }
        }
        return $count;
    }
}
?>