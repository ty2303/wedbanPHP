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
                        <?php if (isset($_SESSION['reset_password_email'])): ?>
                            <div class="alert alert-success">
                                Mã xác nhận đã được gửi đến email: <strong><?= $_SESSION['reset_password_email'] ?></strong>. Vui lòng kiểm tra email và nhập mã xác nhận cùng mật khẩu mới.
                            </div>
                            <form action="/webbanhang/Auth/updatePassword" method="post" class="mt-4">
                                <div class="mb-3">
                                    <label for="token" class="form-label">Mã xác nhận</label>
                                    <input type="text" class="form-control" id="token" name="token" required maxlength="6" minlength="6" pattern="[0-9]+" placeholder="Nhập mã 6 số được gửi đến email của bạn">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                    <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                                <a href="/webbanhang/Auth/forgotPassword?reset=1" class="btn btn-link">Quay lại nhập email</a>
                            </form>
                        <?php else: ?>
                            <?php if (SessionHelper::hasFlash('error')): ?>
                                <div class="alert alert-danger">
                                    <?= SessionHelper::getFlash('error') ?>
                                </div>
                            <?php endif; ?>
                            <form action="/webbanhang/Auth/sendResetLink" method="post" <?php if (isset($_SESSION['reset_password_email'])) echo 'style="display:none"'; ?>>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required value="<?= isset($_SESSION['reset_password_email']) ? $_SESSION['reset_password_email'] : '' ?>">
                                    <div class="form-text">
                                        Nhập email của bạn để nhận mã xác nhận đặt lại mật khẩu
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Gửi mã xác nhận</button>
                                <a href="/webbanhang/Auth/login" class="btn btn-link">Quay lại đăng nhập</a>
                            </form>
                            <?php if (isset($_SESSION['reset_password_email'])): ?>
                                <div class="alert alert-success mt-3">
                                    Mã xác nhận đã được gửi đến email: <strong><?= $_SESSION['reset_password_email'] ?></strong>. Vui lòng kiểm tra email và nhập mã xác nhận cùng mật khẩu mới.
                                </div>
                                <form action="/webbanhang/Auth/updatePassword" method="post" class="mt-4">
                                    <div class="mb-3">
                                        <label for="token" class="form-label">Mã xác nhận</label>
                                        <input type="text" class="form-control" id="token" name="token" required maxlength="6" minlength="6" pattern="[0-9]+" placeholder="Nhập mã 6 số được gửi đến email của bạn">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu mới</label>
                                        <input type="password" class="form-control" id="password" name="password" required minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                        <div class="form-text">Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường và số</div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirm" class="form-label">Xác nhận mật khẩu mới</label>
                                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Đặt lại mật khẩu</button>
                                    <a href="/webbanhang/Auth/forgotPassword?reset=1" class="btn btn-link">Quay lại nhập email</a>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
