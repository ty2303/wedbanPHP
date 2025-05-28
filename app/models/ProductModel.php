<?php
class ProductModel
{
    private $conn;
    private $table_name = "product";
    
    public function __construct($db)
    {
        $this->conn = $db;
    }
    
    public function getProducts()
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Asegura que el HTML en la descripción no se modifique
        return $result;
    }
    
    public function getProductById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
   
    public function getRelatedProducts($productId, $categoryId, $limit = 3)
    {
        // Return empty array if no category is set
        if ($categoryId === null) {
            return [];
        }
        
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE p.category_id = :category_id AND p.id != :product_id 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $categoryId);
        $stmt->bindParam(':product_id', $productId);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    
    public function addProduct($name, $description, $price, $category_id, $image = null)
    {
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên sản phẩm không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }
        if (!is_numeric($price) || $price < 0) {
            $errors['price'] = 'Giá sản phẩm không hợp lệ';
        }
        if (count($errors) > 0) {
            return $errors;
        }
        
        $query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) 
                  VALUES (:name, :description, :price, :category_id, :image)";
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        // Don't use htmlspecialchars for description as it will be HTML content
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':image', $image);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }   
    
    public function updateProduct($id, $name, $description, $price, $category_id, $image = null)
    {
        if ($image !== null) {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image 
                      WHERE id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET name=:name, description=:description, price=:price, category_id=:category_id 
                      WHERE id=:id";
        }
        
        $stmt = $this->conn->prepare($query);
        $name = htmlspecialchars(strip_tags($name));
        // Don't use htmlspecialchars or strip_tags on description to preserve HTML
        $price = htmlspecialchars(strip_tags($price));
        
        if ($category_id !== null) {
            $category_id = htmlspecialchars(strip_tags($category_id));
        }
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);  // Pass description directly without stripping HTML
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        
        if ($image !== null) {
            $stmt->bindParam(':image', $image);
        }
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function deleteProduct($id)
    {
        $currentProduct = $this->getProductById($id);
        
        $query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if ($stmt->execute()) {
            if ($currentProduct && !empty($currentProduct->image) && file_exists('public/uploads/' . $currentProduct->image)) {
                unlink('public/uploads/' . $currentProduct->image);
            }
            return true;
        }
        return false;
    }
    
    public function getFilteredProducts($search = null, $minPrice = null, $maxPrice = null, $categories = [])
    {
        $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                  FROM " . $this->table_name . " p
                  LEFT JOIN category c ON p.category_id = c.id
                  WHERE 1=1";
        
        $params = [];
        
        // Add search condition
        if (!empty($search)) {
            $query .= " AND p.name LIKE :search";
            $params[':search'] = "%$search%";
        }
        
        // Add minimum price condition
        if (!empty($minPrice)) {
            $query .= " AND p.price >= :min_price";
            $params[':min_price'] = $minPrice;
        }
        
        // Add maximum price condition
        if (!empty($maxPrice)) {
            $query .= " AND p.price <= :max_price";
            $params[':max_price'] = $maxPrice;
        }
        
        // Add category filter
        if (!empty($categories)) {
            // Convert array of categories to string like "1,2,3"
            $categoryList = implode(',', array_map('intval', $categories));
            $query .= " AND p.category_id IN ($categoryList)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind named parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $result;
    }
}
?>