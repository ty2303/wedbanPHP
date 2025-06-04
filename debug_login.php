<?php
require_once('app/config/database.php');
require_once('app/models/UserModel.php');

// Hàm để test mật khẩu người dùng
function testPassword($username, $password) {
    $db = (new Database())->getConnection();
    $userModel = new UserModel($db);
    
    // Lấy thông tin user từ database
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    
    echo "<h2>Kiểm tra tài khoản: $username</h2>";
    
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>✅ Tìm thấy tài khoản: $username</p>";
        
        // Kiểm tra hash password
        echo "<p>Hash mật khẩu trong DB: " . htmlspecialchars($user['password']) . "</p>";
        
        // Kiểm tra với password_verify
        $result = password_verify($password, $user['password']);
        echo "<p>" . ($result ? "✅ Mật khẩu đúng" : "❌ Mật khẩu sai") . "</p>";
        
        // Tạo hash mới để so sánh
        $newHash = password_hash($password, PASSWORD_DEFAULT);
        echo "<p>Hash mới tạo từ mật khẩu đã nhập: " . $newHash . "</p>";
        
        // Debug thêm
        echo "<p>Chi tiết tài khoản:</p>";
        echo "<ul>";
        foreach ($user as $key => $value) {
            if ($key != 'password') {
                echo "<li>$key: " . htmlspecialchars($value) . "</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>❌ Không tìm thấy tài khoản: $username</p>";
    }
}

// Hiển thị header
echo "<!DOCTYPE html>
<html lang='vi'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Debug Đăng nhập</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #333; }
        .debug-section { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-left: 4px solid #0066cc; }
        .hash { word-break: break-all; background: #eee; padding: 5px; }
        .success { color: green; }
        .error { color: red; }
        code { background: #f0f0f0; padding: 2px 4px; }
    </style>
</head>
<body>
    <h1>Debug Đăng Nhập TITI Shop</h1>";

// Form test tài khoản
echo "<div class='debug-section'>
    <h2>Kiểm tra Tài khoản</h2>
    <form method='post'>
        <div>
            <label for='username'>Tên đăng nhập:</label>
            <input type='text' name='username' id='username' value='" . (isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '') . "'>
        </div>
        <div style='margin-top: 10px;'>
            <label for='password'>Mật khẩu:</label>
            <input type='text' name='password' id='password' value='" . (isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '') . "'>
        </div>
        <div style='margin-top: 10px;'>
            <button type='submit' name='test'>Kiểm tra</button>
        </div>
    </form>
</div>";

// Test khi có form submit
if (isset($_POST['test']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    echo "<div class='debug-section'>";
    testPassword($_POST['username'], $_POST['password']);
    echo "</div>";
}

// Liệt kê tài khoản admin
echo "<div class='debug-section'>
    <h2>Danh sách tài khoản Admin</h2>";
    
$db = (new Database())->getConnection();
$stmt = $db->query("SELECT id, username, email, role, status FROM users WHERE role = 'admin'");
$admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($admins) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Status</th>
            <th>Hành động</th>
        </tr>";
    
    foreach ($admins as $admin) {
        echo "<tr>
            <td>{$admin['id']}</td>
            <td>{$admin['username']}</td>
            <td>{$admin['email']}</td>
            <td>{$admin['status']}</td>
            <td>
                <form method='post' style='display: inline;'>
                    <input type='hidden' name='username' value='{$admin['username']}'>
                    <input type='hidden' name='password' value='admin123'>
                    <button type='submit' name='test'>Test 'admin123'</button>
                </form>
                &nbsp;
                <form method='post' style='display: inline;'>
                    <input type='hidden' name='username' value='{$admin['username']}'>
                    <input type='hidden' name='password' value='Admin@123'>
                    <button type='submit' name='test'>Test 'Admin@123'</button>
                </form>
            </td>
        </tr>";
    }
    
    echo "</table>";
} else {
    echo "<p>Không tìm thấy tài khoản admin nào</p>";
}

echo "</div>";

// Hướng dẫn sửa lỗi
echo "<div class='debug-section'>
    <h2>Hướng dẫn sửa lỗi đăng nhập</h2>
    <p>Nếu có vấn đề với hash mật khẩu, hãy chạy lệnh SQL sau để sửa:</p>
    <pre><code>UPDATE users SET password = '" . password_hash('admin123', PASSWORD_DEFAULT) . "' WHERE username = 'admin';</code></pre>
    <pre><code>UPDATE users SET password = '" . password_hash('Admin@123', PASSWORD_DEFAULT) . "' WHERE username IN ('Admin1', 'Admin2');</code></pre>
    <pre><code>UPDATE users SET password = '" . password_hash('Staff@123', PASSWORD_DEFAULT) . "' WHERE username IN ('Staff1', 'Staff2');</code></pre>
    <pre><code>UPDATE users SET password = '" . password_hash('Customer@123', PASSWORD_DEFAULT) . "' WHERE username IN ('Customer1', 'Customer2');</code></pre>
</div>";

echo "</body></html>";
?>
