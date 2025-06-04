<?php
class VoucherModel
{
    private $conn;
    private $table_name = "vouchers";
    private $usage_table = "voucher_usage";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getVouchers()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getVoucherById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function getVoucherByCode($code)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE code = :code AND is_active = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function addVoucher($data)
    {
        $errors = [];
        
        // Validation
        if (empty($data['code'])) {
            $errors['code'] = 'Mã voucher không được để trống';
        } else {
            // Check if voucher code already exists
            $existing = $this->getVoucherByCode($data['code']);
            if ($existing) {
                $errors['code'] = 'Mã voucher đã tồn tại';
            }
        }
        
        if (empty($data['name'])) {
            $errors['name'] = 'Tên voucher không được để trống';
        }
        
        if (!is_numeric($data['discount_value']) || $data['discount_value'] <= 0) {
            $errors['discount_value'] = 'Giá trị giảm giá không hợp lệ';
        }
        
        if ($data['discount_type'] == 'percentage' && $data['discount_value'] > 100) {
            $errors['discount_value'] = 'Phần trăm giảm giá không được vượt quá 100%';
        }
        
        if (empty($data['start_date'])) {
            $errors['start_date'] = 'Ngày bắt đầu không được để trống';
        }
        
        if (empty($data['end_date'])) {
            $errors['end_date'] = 'Ngày kết thúc không được để trống';
        }
        
        if (!empty($data['start_date']) && !empty($data['end_date'])) {
            if (strtotime($data['start_date']) >= strtotime($data['end_date'])) {
                $errors['end_date'] = 'Ngày kết thúc phải sau ngày bắt đầu';
            }
        }
        
        if (count($errors) > 0) {
            return $errors;
        }
        
        $query = "INSERT INTO " . $this->table_name . " 
                  (code, name, description, discount_type, discount_value, min_order_amount, 
                   max_discount_amount, applies_to, product_ids, category_ids, usage_limit, 
                   start_date, end_date, is_active) 
                  VALUES (:code, :name, :description, :discount_type, :discount_value, 
                          :min_order_amount, :max_discount_amount, :applies_to, :product_ids, 
                          :category_ids, :usage_limit, :start_date, :end_date, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $data['code']);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':min_order_amount', $data['min_order_amount']);
        $stmt->bindParam(':max_discount_amount', $data['max_discount_amount']);
        $stmt->bindParam(':applies_to', $data['applies_to']);
        $stmt->bindParam(':product_ids', $data['product_ids']);
        $stmt->bindParam(':category_ids', $data['category_ids']);
        $stmt->bindParam(':usage_limit', $data['usage_limit']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function updateVoucher($id, $data)
    {
        $query = "UPDATE " . $this->table_name . " 
                  SET name=:name, description=:description, discount_type=:discount_type, 
                      discount_value=:discount_value, min_order_amount=:min_order_amount, 
                      max_discount_amount=:max_discount_amount, applies_to=:applies_to, 
                      product_ids=:product_ids, category_ids=:category_ids, usage_limit=:usage_limit, 
                      start_date=:start_date, end_date=:end_date, is_active=:is_active 
                  WHERE id=:id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':discount_type', $data['discount_type']);
        $stmt->bindParam(':discount_value', $data['discount_value']);
        $stmt->bindParam(':min_order_amount', $data['min_order_amount']);
        $stmt->bindParam(':max_discount_amount', $data['max_discount_amount']);
        $stmt->bindParam(':applies_to', $data['applies_to']);
        $stmt->bindParam(':product_ids', $data['product_ids']);
        $stmt->bindParam(':category_ids', $data['category_ids']);
        $stmt->bindParam(':usage_limit', $data['usage_limit']);
        $stmt->bindParam(':start_date', $data['start_date']);
        $stmt->bindParam(':end_date', $data['end_date']);
        $stmt->bindParam(':is_active', $data['is_active']);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteVoucher($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function validateVoucher($code, $cartTotal, $productIds = [])
    {
        $voucher = $this->getVoucherByCode($code);
        
        if (!$voucher) {
            return ['valid' => false, 'message' => 'Mã voucher không tồn tại hoặc đã bị vô hiệu hóa'];
        }
        
        // Check if voucher is active
        if (!$voucher->is_active) {
            return ['valid' => false, 'message' => 'Mã voucher đã bị vô hiệu hóa'];
        }
        
        // Check date validity
        $now = date('Y-m-d H:i:s');
        if ($now < $voucher->start_date) {
            return ['valid' => false, 'message' => 'Mã voucher chưa có hiệu lực'];
        }
        
        if ($now > $voucher->end_date) {
            return ['valid' => false, 'message' => 'Mã voucher đã hết hạn'];
        }
        
        // Check usage limit
        if ($voucher->usage_limit !== null && $voucher->used_count >= $voucher->usage_limit) {
            return ['valid' => false, 'message' => 'Mã voucher đã hết lượt sử dụng'];
        }
        
        // Check minimum order amount
        if ($cartTotal < $voucher->min_order_amount) {
            return ['valid' => false, 'message' => 'Đơn hàng chưa đạt giá trị tối thiểu ' . number_format($voucher->min_order_amount, 0, ',', '.') . ' đ'];
        }
        
        // Check product/category restrictions
        if ($voucher->applies_to == 'specific_products' && !empty($voucher->product_ids)) {
            $allowedProducts = json_decode($voucher->product_ids, true);
            $hasValidProduct = false;
            foreach ($productIds as $productId) {
                if (in_array($productId, $allowedProducts)) {
                    $hasValidProduct = true;
                    break;
                }
            }
            if (!$hasValidProduct) {
                return ['valid' => false, 'message' => 'Mã voucher không áp dụng cho các sản phẩm trong giỏ hàng'];
            }
        }
        
        if ($voucher->applies_to == 'specific_categories' && !empty($voucher->category_ids)) {
            // Get product categories from cart
            require_once('app/models/ProductModel.php');
            $productModel = new ProductModel($this->conn);
            $allowedCategories = json_decode($voucher->category_ids, true);
            $hasValidCategory = false;
            
            foreach ($productIds as $productId) {
                $product = $productModel->getProductById($productId);
                if ($product && in_array($product->category_id, $allowedCategories)) {
                    $hasValidCategory = true;
                    break;
                }
            }
            
            if (!$hasValidCategory) {
                return ['valid' => false, 'message' => 'Mã voucher không áp dụng cho danh mục sản phẩm trong giỏ hàng'];
            }
        }
        
        return ['valid' => true, 'voucher' => $voucher];
    }
    
    public function calculateDiscount($voucher, $cartTotal)
    {
        if ($voucher->discount_type == 'percentage') {
            $discount = ($cartTotal * $voucher->discount_value) / 100;
            
            // Apply maximum discount limit
            if ($voucher->max_discount_amount !== null && $discount > $voucher->max_discount_amount) {
                $discount = $voucher->max_discount_amount;
            }
        } else {
            $discount = $voucher->discount_value;
        }
        
        // Discount cannot exceed cart total
        if ($discount > $cartTotal) {
            $discount = $cartTotal;
        }
        
        return $discount;
    }
    
    public function recordVoucherUsage($voucherId, $orderId, $userId, $discountAmount)
    {
        try {
            $this->conn->beginTransaction();
            
            // Record usage
            $query = "INSERT INTO " . $this->usage_table . " 
                      (voucher_id, order_id, user_id, discount_amount) 
                      VALUES (:voucher_id, :order_id, :user_id, :discount_amount)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':voucher_id', $voucherId);
            $stmt->bindParam(':order_id', $orderId);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':discount_amount', $discountAmount);
            $stmt->execute();
            
            // Update used count
            $updateQuery = "UPDATE " . $this->table_name . " 
                           SET used_count = used_count + 1 
                           WHERE id = :voucher_id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':voucher_id', $voucherId);
            $updateStmt->execute();
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
    
    public function getVoucherUsageStats($voucherId)
    {
        $query = "SELECT COUNT(*) as usage_count, SUM(discount_amount) as total_discount 
                  FROM " . $this->usage_table . " 
                  WHERE voucher_id = :voucher_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':voucher_id', $voucherId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
}
?>
