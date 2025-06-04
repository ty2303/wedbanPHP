<?php
class OrderModel
{
    private $conn;
    private $table_order = "orders";
    private $table_order_details = "order_items";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
      public function createOrder($name, $email, $phone, $address, $cart_items, $user_id = null, $voucher_data = null)
    {
        try {
            $this->conn->beginTransaction();
            
            // Calculate subtotal
            $subtotal = 0;
            foreach ($cart_items as $item) {
                $subtotal += $item['product']->price * $item['quantity'];
            }
            
            // Calculate discount and total
            $discount_amount = 0;
            $voucher_id = null;
            $voucher_code = null;
            
            if ($voucher_data) {
                $discount_amount = $voucher_data['discount_amount'];
                $voucher_id = $voucher_data['voucher_id'];
                $voucher_code = $voucher_data['voucher_code'];
            }
            
            $total_amount = $subtotal - $discount_amount;
            
            // First, create the order
            $query = "INSERT INTO " . $this->table_order . " 
                      (user_id, name, email, phone, address, subtotal, discount_amount, total_amount, voucher_id, voucher_code) 
                      VALUES (:user_id, :name, :email, :phone, :address, :subtotal, :discount_amount, :total_amount, :voucher_id, :voucher_code)";
            $stmt = $this->conn->prepare($query);
            
            $name = htmlspecialchars(strip_tags($name));
            $email = htmlspecialchars(strip_tags($email));
            $phone = htmlspecialchars(strip_tags($phone));
            $address = htmlspecialchars(strip_tags($address));
            
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':subtotal', $subtotal);
            $stmt->bindParam(':discount_amount', $discount_amount);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':voucher_id', $voucher_id);
            $stmt->bindParam(':voucher_code', $voucher_code);
            
            $stmt->execute();
            $order_id = $this->conn->lastInsertId();
              // Then, create the order details for each cart item
            foreach ($cart_items as $item) {
                $query = "INSERT INTO " . $this->table_order_details . " 
                          (order_id, product_id, product_name, quantity, price) 
                          VALUES (:order_id, :product_id, :product_name, :quantity, :price)";
                $stmt = $this->conn->prepare($query);
                
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $item['product']->id);
                $stmt->bindParam(':product_name', $item['product']->name);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':price', $item['product']->price);
                
                $stmt->execute();
            }
            
            $this->conn->commit();
            return $order_id;        } catch (Exception $e) {
            $this->conn->rollBack();
            // Log the error for debugging
            error_log("Order creation error: " . $e->getMessage());
            error_log("SQL State: " . $e->getCode());
            return false;
        }
    }
    
    public function getOrderById($id)
    {
        $query = "SELECT * FROM " . $this->table_order . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
      public function getOrderDetails($order_id)
    {
        // Thay đổi từ INNER JOIN thành LEFT JOIN để lấy thông tin đơn hàng ngay cả khi sản phẩm đã bị xóa
        $query = "SELECT od.*, p.name, p.image FROM " . $this->table_order_details . " od
                  LEFT JOIN products p ON od.product_id = p.id
                  WHERE od.order_id = :order_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
      public function getOrders()
    {
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM " . $this->table_order_details . " WHERE order_id = o.id) as item_count
                  FROM " . $this->table_order . " o
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }    /**
     * Lấy danh sách đơn hàng của một người dùng cụ thể
     */
    public function getUserOrders($user_id)
    {
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM " . $this->table_order_details . " WHERE order_id = o.id) as item_count
                  FROM " . $this->table_order . " o
                  WHERE o.user_id = :user_id 
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
?>