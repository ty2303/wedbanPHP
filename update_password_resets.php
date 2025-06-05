<?php
// filepath: c:\laragon\www\webbanhang\update_password_resets.php
require_once('app/config/database.php');

try {
    $db = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['database']}", 
        $config['db']['username'], 
        $config['db']['password']
    );
    
    $sql = "CREATE TABLE IF NOT EXISTS password_resets (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL,
        token VARCHAR(64) NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY unique_email (email),
        UNIQUE KEY unique_token (token)
    )";
    
    $db->exec($sql);
    
    echo "Tạo bảng password_resets thành công!";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
