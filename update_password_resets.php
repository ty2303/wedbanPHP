<?php
// filepath: c:\laragon\www\webbanhang\update_password_resets.php
require_once('app/config/database.php');

$database = new Database();
$db = $database->getConnection();

try {
      // Drop table if exists to recreate with new schema
    $db->exec("DROP TABLE IF EXISTS password_resets");
      $sql = "CREATE TABLE password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(6) NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_email (email)
    )";
    
    $db->exec($sql);
    
    echo "Tạo bảng password_resets thành công!";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
