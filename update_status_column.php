<?php
require_once('app/config/database.php');

try {
    $db = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['database']}", 
        $config['db']['username'], 
        $config['db']['password']
    );
    
    $sql = "ALTER TABLE users MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'suspended') DEFAULT 'pending'";
    $db->exec($sql);
    
    echo "Cập nhật cột status thành công!";
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?>
