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
    
    public function createOrder($name, $phone, $address, $cart_items)
    {
        try {
            $this->conn->beginTransaction();
            
            // First, create the order
            $query = "INSERT INTO " . $this->table_order . " (name, phone, address) 
                      VALUES (:name, :phone, :address)";
            $stmt = $this->conn->prepare($query);
            
            $name = htmlspecialchars(strip_tags($name));
            $phone = htmlspecialchars(strip_tags($phone));
            $address = htmlspecialchars(strip_tags($address));
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            
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
}
?>