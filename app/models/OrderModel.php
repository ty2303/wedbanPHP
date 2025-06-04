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
    
    public function createOrder($name, $email, $phone, $address, $cart_items, $user_id = null)
    {
        try {
            $this->conn->beginTransaction();
            
            // First, create the order
            $query = "INSERT INTO " . $this->table_order . " (user_id, name, email, phone, address, total_amount) 
                      VALUES (:user_id, :name, :email, :phone, :address, :total_amount)";
            $stmt = $this->conn->prepare($query);
            
            $name = htmlspecialchars(strip_tags($name));
            $email = htmlspecialchars(strip_tags($email));
            $phone = htmlspecialchars(strip_tags($phone));
            $address = htmlspecialchars(strip_tags($address));
            
            // Calculate total amount
            $total_amount = 0;
            foreach ($cart_items as $item) {
                $total_amount += $item['product']->price * $item['quantity'];
            }
            
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':total_amount', $total_amount);
            
            $stmt->execute();
            $order_id = $this->conn->lastInsertId();
            
            // Then, create the order details for each cart item
            foreach ($cart_items as $item) {
                $query = "INSERT INTO " . $this->table_order_details . " 
                          (order_id, product_id, quantity, price) 
                          VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->conn->prepare($query);
                
                $stmt->bindParam(':order_id', $order_id);
                $stmt->bindParam(':product_id', $item['product']->id);
                $stmt->bindParam(':quantity', $item['quantity']);
                $stmt->bindParam(':price', $item['product']->price);
                
                $stmt->execute();
            }
            
            $this->conn->commit();
            return $order_id;
        } catch (Exception $e) {
            $this->conn->rollBack();
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
                  (SELECT COUNT(*) FROM " . $this->table_order_details . " WHERE order_id = o.id) as item_count,
                  (SELECT SUM(price * quantity) FROM " . $this->table_order_details . " WHERE order_id = o.id) as total_amount
                  FROM " . $this->table_order . " o
                  ORDER BY o.created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    /**
     * Lấy danh sách đơn hàng của một người dùng cụ thể
     */
    public function getUserOrders($user_id)
    {
        $query = "SELECT * FROM " . $this->table_order . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>