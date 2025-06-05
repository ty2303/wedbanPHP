<?php
// filepath: app/views/auth/forgot-password.php
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Quên mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        <?php if (SessionHelper::hasFlash('success')): ?>
                            <div class="alert alert-success">
                                <?= SessionHelper::getFlash('success') ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (SessionHelper::hasFlash('error')): ?>
                            <div class="alert alert-danger">
                                <?= SessionHelper::getFlash('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="/webbanhang/Auth/sendResetLink" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="form-text">
                                    Nhập email của bạn để nhận link đặt lại mật khẩu
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Gửi link đặt lại mật khẩu</button>
                            <a href="/webbanhang/Auth/login" class="btn btn-link">Quay lại đăng nhập</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
