<?php
require_once('app/helpers/SessionHelper.php');
SessionHelper::start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Đăng ký - TITI Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body class="d-flex justify-content-center align-items-center bg-light" style="min-height: 100vh;">
    <div class="auth-container">
        <div class="auth-header">
            <div class="auth-logo">
                <a href="/webbanhang/">
                    <img src="/webbanhang/public/logo/a.png" alt="TITI Shop" height="60">
                </a>
            </div>
            <h2>Đăng ký tài khoản</h2>
            <p class="text-muted">Tạo tài khoản mới để mua sắm dễ dàng hơn</p>
        </div>
        
        <?php if (SessionHelper::hasFlash('success')): ?>
            <div class="success-message">
                <?php echo SessionHelper::getFlash('success'); ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <?php foreach($errors as $error): ?>
                    <div><?php echo $error; ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
          <form action="/webbanhang/Auth/register" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" id="username" name="username" class="form-control" required 
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" id="email" name="email" class="form-control" required 
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự.</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="age" class="form-label">Tuổi</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                    <input type="number" id="age" name="age" class="form-control" min="1" max="120"
                        value="<?php echo isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''; ?>">
                </div>
            </div>
              <div class="form-group">
                <label for="avatar" class="form-label">Ảnh đại diện</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-image"></i></span>
                    <input type="file" id="avatar" name="avatar" class="form-control">
                </div>
                <small class="form-text text-muted">Chấp nhận file JPG, JPEG, PNG, GIF.</small>
            </div>

            <div class="form-group">
                <label for="role" class="form-label">Vai trò</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                    <select id="role" name="role" class="form-control" required>
                        <option value="customer">Khách hàng</option>
                        <option value="staff">Nhân viên</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
            </div>
            
            <div class="text-center mt-4">
                <p>Đã có tài khoản? <a href="/webbanhang/Auth/login">Đăng nhập</a></p>
                <p><a href="/webbanhang/"><i class="bi bi-house-door"></i> Quay lại trang chủ</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
