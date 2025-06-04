<?php
/**
 * ========================================
 * WEBBANHANG SETUP INSTALLER
 * ========================================
 * File setup tự động cho hệ thống TITI Shop
 * Chạy file này để cài đặt hoàn chỉnh database và cấu hình
 * 
 * Cách sử dụng:
 * 1. Copy toàn bộ thư mục webbanhang vào htdocs/www
 * 2. Truy cập: http://localhost/webbanhang/setup_installer.php
 * 3. Nhập thông tin database
 * 4. Click "Cài đặt"
 * ========================================
 */

session_start();

// Cấu hình
$setup_file = __DIR__ . '/database/complete_setup.sql';
$config_file = __DIR__ . '/app/config/database.php';

// Kiểm tra setup đã hoàn thành chưa
if (file_exists(__DIR__ . '/.setup_complete')) {
    header('Location: /webbanhang/');
    exit;
}

$errors = [];
$success = false;
$step = $_GET['step'] ?? 1;

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_name = $_POST['db_name'] ?? 'webbanhang';
    $db_user = $_POST['db_user'] ?? 'root';
    $db_pass = $_POST['db_pass'] ?? '';
    
    try {
        // Test kết nối database
        $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Đọc và thực thi file SQL
        if (file_exists($setup_file)) {
            $sql = file_get_contents($setup_file);
            
            // Thực thi từng câu lệnh
            $statements = explode(';', $sql);
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && $statement !== '--') {
                    $pdo->exec($statement);
                }
            }
            
            // Tạo file config database
            $config_content = "<?php
class Database
{
    private \$host = '$db_host';
    private \$db_name = '$db_name';
    private \$username = '$db_user';
    private \$password = '$db_pass';
    private \$conn;

    public function getConnection()
    {
        \$this->conn = null;
        try {
            \$this->conn = new PDO(\"mysql:host=\" . \$this->host . \";dbname=\" . \$this->db_name . \";charset=utf8mb4\", \$this->username, \$this->password);
            \$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException \$e) {
            echo \"Connection error: \" . \$e->getMessage();
        }
        return \$this->conn;
    }
}
?>";
            
            // Tạo thư mục config nếu chưa có
            if (!file_exists(dirname($config_file))) {
                mkdir(dirname($config_file), 0755, true);
            }
            
            file_put_contents($config_file, $config_content);
            
            // Tạo thư mục uploads nếu chưa có
            $upload_dir = __DIR__ . '/public/uploads';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Đánh dấu setup hoàn thành
            file_put_contents(__DIR__ . '/.setup_complete', date('Y-m-d H:i:s'));
            
            $success = true;
            
        } else {
            $errors[] = "Không tìm thấy file setup SQL: $setup_file";
        }
        
    } catch (PDOException $e) {
        $errors[] = "Lỗi database: " . $e->getMessage();
    } catch (Exception $e) {
        $errors[] = "Lỗi: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup TITI Shop - Cài đặt hệ thống</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .setup-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1);
            padding: 2rem;
            margin: 2rem auto;
            max-width: 600px;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .setup-logo {
            margin-bottom: 1rem;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
        }
        .step.active {
            background: #667eea;
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .step.pending {
            background: #e9ecef;
            color: #6c757d;
        }
        .feature-list {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .feature-item i {
            color: #28a745;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="setup-container">
            <div class="setup-header">
                <div class="setup-logo">
                    <h1><i class="bi bi-shop text-primary"></i> TITI Shop</h1>
                </div>
                <h2>Cài đặt hệ thống</h2>
                <p class="text-muted">Thiết lập database và cấu hình ban đầu</p>
            </div>            <?php if ($success): ?>
                <!-- Step 3: Success -->
                <div class="step-indicator">
                    <div class="step completed">1</div>
                    <div class="step completed">2</div>
                    <div class="step active">3</div>
                </div>

                <div class="alert alert-success text-center">
                    <h4><i class="bi bi-check-circle"></i> Cài đặt thành công!</h4>
                    <p>Hệ thống TITI Shop đã được cài đặt hoàn chỉnh.</p>
                </div>

                <div class="feature-list">
                    <h5><i class="bi bi-info-circle"></i> Thông tin tài khoản Admin:</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Username:</strong> admin
                        </div>
                        <div class="col-md-6">
                            <strong>Password:</strong> admin123
                        </div>
                    </div>
                </div>

                <div class="feature-list">
                    <h5><i class="bi bi-list-check"></i> Tính năng đã cài đặt:</h5>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Hệ thống người dùng với 3 vai trò: Admin, Staff, Customer</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Quản lý sản phẩm và danh mục</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Hệ thống giỏ hàng và đặt hàng</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Báo cáo doanh thu</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Hệ thống duyệt tài khoản Staff</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span>Dữ liệu mẫu: 20+ sản phẩm công nghệ</span>
                    </div>
                </div>

                <div class="text-center">
                    <a href="/webbanhang/" class="btn btn-primary btn-lg">
                        <i class="bi bi-house"></i> Truy cập website
                    </a>
                </div>

            <?php elseif ($step == 2 || !empty($errors)): ?>
                <!-- Step 2: Database Config -->
                <div class="step-indicator">
                    <div class="step completed">1</div>
                    <div class="step active">2</div>
                    <div class="step pending">3</div>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h6><i class="bi bi-exclamation-triangle"></i> Có lỗi xảy ra:</h6>
                        <?php foreach($errors as $error): ?>
                            <div>• <?php echo htmlspecialchars($error); ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <h5><i class="bi bi-database"></i> Cấu hình Database</h5>
                    
                    <div class="mb-3">
                        <label for="db_host" class="form-label">Database Host</label>
                        <input type="text" class="form-control" id="db_host" name="db_host" 
                               value="<?php echo isset($_POST['db_host']) ? htmlspecialchars($_POST['db_host']) : 'localhost'; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_name" class="form-label">Database Name</label>
                        <input type="text" class="form-control" id="db_name" name="db_name" 
                               value="<?php echo isset($_POST['db_name']) ? htmlspecialchars($_POST['db_name']) : 'webbanhang'; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_user" class="form-label">Database User</label>
                        <input type="text" class="form-control" id="db_user" name="db_user" 
                               value="<?php echo isset($_POST['db_user']) ? htmlspecialchars($_POST['db_user']) : 'root'; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="db_pass" class="form-label">Database Password</label>
                        <input type="password" class="form-control" id="db_pass" name="db_pass" 
                               value="<?php echo isset($_POST['db_pass']) ? htmlspecialchars($_POST['db_pass']) : ''; ?>">
                        <div class="form-text">Để trống nếu không có password (XAMPP/LARAGON mặc định)</div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Chú ý:</strong> Database <code><?php echo isset($_POST['db_name']) ? htmlspecialchars($_POST['db_name']) : 'webbanhang'; ?></code> sẽ được tạo mới hoặc ghi đè nếu đã tồn tại.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-download"></i> Cài đặt Database
                        </button>
                        <a href="?step=1" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </form>

            <?php else: ?>
                <!-- Step 1: Introduction -->
                <div class="step-indicator">
                    <div class="step active">1</div>
                    <div class="step pending">2</div>
                    <div class="step pending">3</div>
                </div>

                <div class="feature-list">
                    <h5><i class="bi bi-info-circle"></i> Hệ thống sẽ cài đặt:</h5>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span><strong>Database hoàn chỉnh</strong> - Tất cả bảng và dữ liệu mẫu</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span><strong>Hệ thống vai trò</strong> - Admin, Staff, Customer</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span><strong>Tài khoản Admin</strong> - Username: admin, Password: admin123</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span><strong>Dữ liệu mẫu</strong> - 20+ sản phẩm công nghệ</span>
                    </div>
                    <div class="feature-item">
                        <i class="bi bi-check"></i>
                        <span><strong>Cấu hình tự động</strong> - Database config</span>
                    </div>
                </div>

                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    <strong>Lưu ý:</strong> Quá trình này sẽ tạo database mới và xóa dữ liệu cũ (nếu có).
                </div>                <div class="text-center">
                    <a href="?step=2" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-right"></i> Tiếp tục
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
