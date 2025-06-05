<?php
// Script tạo tài khoản admin mới với hash mật khẩu chuẩn
require_once('app/config/database.php');

// Kết nối database
$db = (new Database())->getConnection();

// Thông tin tài khoản admin mới
$username = 'superadmin';
$email = 'superadmin@titishop.com';
$password = '123456'; // Mật khẩu đơn giản để dễ nhớ
$role = 'admin';

// Tạo hash mật khẩu với PHP
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Kiểm tra xem tài khoản đã tồn tại chưa
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        // Nếu đã tồn tại, cập nhật mật khẩu
        $query = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$hashed_password, $username]);
        echo "<h2>Đã cập nhật mật khẩu cho tài khoản $username</h2>";
    } else {
        // Nếu chưa tồn tại, tạo tài khoản mới
        $query = "INSERT INTO users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'approved')";
        $stmt = $db->prepare($query);
        $stmt->execute([$username, $email, $hashed_password, $role]);
        echo "<h2>Đã tạo tài khoản admin mới thành công!</h2>";
    }
    
    echo "<div style='background-color: #e8f5e9; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3>Thông tin đăng nhập:</h3>";
    echo "<p><strong>Username:</strong> $username</p>";
    echo "<p><strong>Password:</strong> $password</p>";
    echo "<p><strong>Role:</strong> $role</p>";
    echo "</div>";
    
    echo "<p>Đây là hash được tạo với PHP password_hash(): <code>$hashed_password</code></p>";
    
    echo "<p><a href='/webbanhang/Auth/login' style='display: inline-block; background-color: #4CAF50; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>Đi đến trang đăng nhập</a></p>";
    
} catch (PDOException $e) {
    echo "<h2>Lỗi!</h2>";
    echo "<p>Không thể tạo tài khoản: " . $e->getMessage() . "</p>";
}
?>
