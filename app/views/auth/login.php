
<?php
require_once('app/helpers/SessionHelper.php');
SessionHelper::start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Đăng nhập - TITI Shop</title>
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
            <h2>Đăng nhập</h2>
            <p class="text-muted">Đăng nhập để tiếp tục mua sắm</p>
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
        
        <form action="/webbanhang/Auth/login" method="post">
            <div class="form-group">
                <label for="username" class="form-label">Tên đăng nhập</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" id="username" name="username" class="form-control" required 
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
            </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </div>
            
            <div class="text-center mt-4">
                <p>Chưa có tài khoản? <a href="/webbanhang/Auth/register">Đăng ký ngay</a></p>
                <p><a href="/webbanhang/Auth/forgotPassword">Quên mật khẩu?</a></p>
                <p><a href="/webbanhang/"><i class="bi bi-house-door"></i> Quay lại trang chủ</a></p>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
