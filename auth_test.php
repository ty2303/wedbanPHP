<?php
// Script kiểm tra hệ thống xác thực
require_once('app/config/database.php');
require_once('app/models/UserModel.php');

// Hàm để kiểm tra và hiển thị thông tin user
function getUserInfo($username) {
    $db = (new Database())->getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    return null;
}

// Hàm kiểm tra hash mật khẩu
function testPasswordHash($password, $hash) {
    $result = password_verify($password, $hash);
    return $result;
}

// Hàm tạo mật khẩu mới cho user
function resetUserPassword($username, $newPassword) {
    $db = (new Database())->getConnection();
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("UPDATE users SET password = ? WHERE username = ?");
    return $stmt->execute([$hash, $username]);
}

// Style
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kiểm tra hệ thống xác thực</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #333; }
        .section { margin-bottom: 30px; border-bottom: 1px solid #eee; padding-bottom: 20px; }
        .info { background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .success { background: #e8f5e9; color: #2e7d32; padding: 10px; border-radius: 4px; }
        .error { background: #ffebee; color: #c62828; padding: 10px; border-radius: 4px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .user-info { margin-bottom: 20px; }
        table { border-collapse: collapse; width: 100%; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        button, .button { background: #4CAF50; color: white; border: none; padding: 8px 16px; cursor: pointer; border-radius: 4px; text-decoration: none; display: inline-block; }
        button:hover, .button:hover { background: #388E3C; }
        input[type="text"], input[type="password"] { padding: 8px; width: 200px; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Kiểm tra hệ thống xác thực TITI Shop</h1>';

// Kiểm tra tài khoản admin
$adminUser = getUserInfo('admin');
$admin1User = getUserInfo('Admin1');
$superAdminUser = getUserInfo('superadmin');

echo '<div class="section">
    <h2>1. Kiểm tra tài khoản admin trong database</h2>';

// Hiển thị thông tin admin
if ($adminUser) {
    echo '<div class="user-info">
        <h3>Thông tin tài khoản "admin":</h3>
        <table>
            <tr><th>ID</th><td>'.$adminUser['id'].'</td></tr>
            <tr><th>Username</th><td>'.$adminUser['username'].'</td></tr>
            <tr><th>Email</th><td>'.$adminUser['email'].'</td></tr>
            <tr><th>Role</th><td>'.$adminUser['role'].'</td></tr>
            <tr><th>Status</th><td>'.$adminUser['status'].'</td></tr>
            <tr><th>Password Hash</th><td><code>'.$adminUser['password'].'</code></td></tr>
        </table>
        
        <h4>Kiểm tra mật khẩu:</h4>
        <p>Test với "admin123": '.
        (testPasswordHash('admin123', $adminUser['password']) ? 
            '<span class="success">✓ Đúng</span>' : 
            '<span class="error">✗ Sai</span>')
        .'</p>
    </div>';
} else {
    echo '<p class="error">Không tìm thấy tài khoản "admin" trong database!</p>';
}

// Hiển thị thông tin Admin1
if ($admin1User) {
    echo '<div class="user-info">
        <h3>Thông tin tài khoản "Admin1":</h3>
        <table>
            <tr><th>ID</th><td>'.$admin1User['id'].'</td></tr>
            <tr><th>Username</th><td>'.$admin1User['username'].'</td></tr>
            <tr><th>Email</th><td>'.$admin1User['email'].'</td></tr>
            <tr><th>Role</th><td>'.$admin1User['role'].'</td></tr>
            <tr><th>Status</th><td>'.$admin1User['status'].'</td></tr>
            <tr><th>Password Hash</th><td><code>'.$admin1User['password'].'</code></td></tr>
        </table>
        
        <h4>Kiểm tra mật khẩu:</h4>
        <p>Test với "Admin@123": '.
        (testPasswordHash('Admin@123', $admin1User['password']) ? 
            '<span class="success">✓ Đúng</span>' : 
            '<span class="error">✗ Sai</span>')
        .'</p>
    </div>';
} else {
    echo '<p class="error">Không tìm thấy tài khoản "Admin1" trong database!</p>';
}

// Hiển thị thông tin superadmin
if ($superAdminUser) {
    echo '<div class="user-info">
        <h3>Thông tin tài khoản "superadmin":</h3>
        <table>
            <tr><th>ID</th><td>'.$superAdminUser['id'].'</td></tr>
            <tr><th>Username</th><td>'.$superAdminUser['username'].'</td></tr>
            <tr><th>Email</th><td>'.$superAdminUser['email'].'</td></tr>
            <tr><th>Role</th><td>'.$superAdminUser['role'].'</td></tr>
            <tr><th>Status</th><td>'.$superAdminUser['status'].'</td></tr>
            <tr><th>Password Hash</th><td><code>'.$superAdminUser['password'].'</code></td></tr>
        </table>
        
        <h4>Kiểm tra mật khẩu:</h4>
        <p>Test với "123456": '.
        (testPasswordHash('123456', $superAdminUser['password']) ? 
            '<span class="success">✓ Đúng</span>' : 
            '<span class="error">✗ Sai</span>')
        .'</p>
    </div>';
} else {
    echo '<p>Tài khoản "superadmin" chưa được tạo. <a href="create_new_admin.php" class="button">Tạo tài khoản superadmin</a></p>';
}

echo '</div>';

// Form kiểm tra đăng nhập
echo '<div class="section">
    <h2>2. Kiểm tra đăng nhập thủ công</h2>
    <form method="post">
        <div style="margin-bottom: 10px;">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="'.htmlspecialchars($_POST['username'] ?? '').'">
        </div>
        <div style="margin-bottom: 10px;">
            <label for="password">Password:</label>
            <input type="text" id="password" name="password" value="'.htmlspecialchars($_POST['password'] ?? '').'">
        </div>
        <button type="submit" name="test_login">Kiểm tra đăng nhập</button>
    </form>';

// Kiểm tra đăng nhập thủ công
if (isset($_POST['test_login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $user = getUserInfo($username);
    
    if ($user) {
        echo '<div class="info">';
        echo '<h3>Kết quả kiểm tra đăng nhập:</h3>';
        echo '<p>Tài khoản '.$username.' tồn tại trong database.</p>';
        
        $loginResult = testPasswordHash($password, $user['password']);
        
        if ($loginResult) {
            echo '<p class="success">✓ Mật khẩu chính xác! Đăng nhập thành công.</p>';
        } else {
            echo '<p class="error">✗ Mật khẩu không đúng!</p>';
            echo '<p>Hash trong database: <code>'.$user['password'].'</code></p>';
            echo '<p>Hash mới tạo cho "'.$password.'": <code>'.password_hash($password, PASSWORD_DEFAULT).'</code></p>';
        }
        
        echo '</div>';
    } else {
        echo '<p class="error">Không tìm thấy tài khoản "'.$username.'" trong database!</p>';
    }
}

echo '</div>';

// Form reset mật khẩu
echo '<div class="section">
    <h2>3. Reset mật khẩu khẩn cấp</h2>
    <form method="post">
        <div style="margin-bottom: 10px;">
            <label for="reset_username">Username:</label>
            <input type="text" id="reset_username" name="reset_username" required>
        </div>
        <div style="margin-bottom: 10px;">
            <label for="new_password">Mật khẩu mới:</label>
            <input type="text" id="new_password" name="new_password" value="123456" required>
        </div>
        <button type="submit" name="reset_password">Reset mật khẩu</button>
    </form>';

// Xử lý reset mật khẩu
if (isset($_POST['reset_password']) && !empty($_POST['reset_username']) && !empty($_POST['new_password'])) {
    $resetUsername = $_POST['reset_username'];
    $newPassword = $_POST['new_password'];
    
    $user = getUserInfo($resetUsername);
    
    if ($user) {
        $resetResult = resetUserPassword($resetUsername, $newPassword);
        
        if ($resetResult) {
            echo '<div class="success">';
            echo '<h3>✓ Đã reset mật khẩu thành công!</h3>';
            echo '<p>Tài khoản: '.$resetUsername.'</p>';
            echo '<p>Mật khẩu mới: '.$newPassword.'</p>';
            echo '<p><a href="/webbanhang/Auth/login" class="button">Đi đến trang đăng nhập</a></p>';
            echo '</div>';
        } else {
            echo '<p class="error">Không thể reset mật khẩu!</p>';
        }
    } else {
        echo '<p class="error">Không tìm thấy tài khoản "'.$resetUsername.'" trong database!</p>';
    }
}

echo '</div>';

// Hướng dẫn fix lỗi
echo '<div class="section">
    <h2>4. Hướng dẫn khắc phục</h2>
    <p>Nếu bạn vẫn gặp vấn đề với đăng nhập, thử các cách sau:</p>
    <ol>
        <li>Chạy file SQL để sửa tất cả hash mật khẩu: <code>database/fix_passwords.sql</code></li>
        <li>Tạo tài khoản admin mới: <a href="create_new_admin.php" class="button">Tạo tài khoản superadmin</a></li>
        <li>Reset mật khẩu cho tài khoản hiện có với form bên trên</li>
        <li>Đăng ký tài khoản mới trên web</li>
    </ol>
    <p><strong>Lưu ý:</strong> Nếu bạn tạo tài khoản bằng SQL, hash mật khẩu phải được tạo bằng PHP <code>password_hash()</code> function.</p>
</div>';

echo '</div>
</body>
</html>';
?>
