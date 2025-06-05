<?php
class OrderModel
{
    private $conn;
    private $table_order = "orders";
    private $table_order_details = "order_items";
    private $table_status_history = "order_status_history";
    
    // Định nghĩa các trạng thái đơn hàng
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PACKED = 'packed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_RETURNED = 'returned';
    
    // Mapping tên tiếng Việt cho trạng thái
    public static $statusLabels = [
        'pending' => 'Chờ xử lý',
        'confirmed' => 'Đã xác nhận',
        'processing' => 'Đang xử lý',
        'packed' => 'Đã đóng gói',
        'shipped' => 'Đã gửi hàng',
        'delivered' => 'Đã giao hàng',
        'cancelled' => 'Đã hủy',
        'returned' => 'Đã trả hàng'
    ];
    
    // Mapping màu sắc cho trạng thái
    public static $statusColors = [
        'pending' => 'warning',
        'confirmed' => 'info',
        'processing' => 'primary',
        'packed' => 'secondary',
        'shipped' => 'dark',
        'delivered' => 'success',
        'cancelled' => 'danger',
        'returned' => 'danger'
    ];
    
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
    
    /**
     * Cập nhật trạng thái đơn hàng
     */    public function updateOrderStatus($order_id, $status, $notes = '', $admin_id = null, $tracking_number = '', $estimated_delivery = null)
    {
        try {
            $this->conn->beginTransaction();
            
            // Cập nhật trạng thái đơn hàng
            $update_fields = "status = :status";
            $params = [
                ':order_id' => $order_id,
                ':status' => $status
            ];
            
            if (!empty($tracking_number)) {
                $update_fields .= ", tracking_number = :tracking_number";
                $params[':tracking_number'] = $tracking_number;
            }
            
            if (!empty($estimated_delivery)) {
                $update_fields .= ", estimated_delivery = :estimated_delivery";
                $params[':estimated_delivery'] = $estimated_delivery;
            }
            
            if (!empty($notes)) {
                $update_fields .= ", admin_notes = :admin_notes";
                $params[':admin_notes'] = $notes;
            }
            
            $query = "UPDATE " . $this->table_order . " SET " . $update_fields . " WHERE id = :order_id";
            $stmt = $this->conn->prepare($query);
            
            // Bind tất cả parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $result = $stmt->execute();
            
            if (!$result) {
                throw new Exception("Failed to update order status");
            }
            
            // Thêm vào lịch sử trạng thái
            $this->addStatusHistory($order_id, $status, $notes, $admin_id);
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Order status update error: " . $e->getMessage());
            return false;
        }
    }
      /**
     * Thêm lịch sử thay đổi trạng thái
     */
    private function addStatusHistory($order_id, $status, $notes = '', $admin_id = null)
    {
        $query = "INSERT INTO " . $this->table_status_history . " 
                  (order_id, status, notes, changed_by) 
                  VALUES (:order_id, :status, :notes, :changed_by)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':order_id', $order_id);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':notes', $notes);
        $stmt->bindValue(':changed_by', $admin_id);
        
        return $stmt->execute();
    }
    
    /**
     * Lấy lịch sử trạng thái đơn hàng
     */
    public function getOrderStatusHistory($order_id)
    {        $query = "SELECT h.*, u.username as changed_by_name 
                  FROM " . $this->table_status_history . " h
                  LEFT JOIN users u ON h.changed_by = u.id
                  WHERE h.order_id = :order_id
                  ORDER BY h.changed_at ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Lấy đơn hàng theo trạng thái
     */
    public function getOrdersByStatus($status = null)
    {
        $where_clause = "";
        if ($status) {
            $where_clause = "WHERE o.status = :status";
        }
        
        $query = "SELECT o.*, 
                  (SELECT COUNT(*) FROM " . $this->table_order_details . " WHERE order_id = o.id) as item_count
                  FROM " . $this->table_order . " o
                  $where_clause
                  ORDER BY o.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($status) {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Thống kê đơn hàng theo trạng thái
     */    public function getOrderStatusStats()
    {
        $query = "SELECT status, COUNT(*) as count 
                  FROM " . $this->table_order . " 
                  GROUP BY status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $stats = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $stats[] = [
                'status' => $row['status'],
                'count' => $row['count']
            ];
        }
        
        return $stats;
    }
    
    /**
     * Kiểm tra xem có thể thay đổi từ trạng thái này sang trạng thái khác không
     */
    public function canChangeStatus($current_status, $new_status)
    {
        // Định nghĩa các chuyển đổi trạng thái hợp lệ
        $allowed_transitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['processing', 'cancelled'],
            'processing' => ['packed', 'cancelled'],
            'packed' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'returned'],
            'delivered' => ['returned'],
            'cancelled' => [], // Không thể thay đổi từ cancelled
            'returned' => []   // Không thể thay đổi từ returned
        ];
        
        return in_array($new_status, $allowed_transitions[$current_status] ?? []);
    }
}
?>