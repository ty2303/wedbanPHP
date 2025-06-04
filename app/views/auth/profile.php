<?php
require_once('app/helpers/SessionHelper.php');
SessionHelper::start();

// Redirect if not logged in
if (!SessionHelper::isLoggedIn()) {
    header('Location: /webbanhang/Auth/login');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    <title>Hồ sơ người dùng - TITI Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <div class="container py-4">
        <div class="profile-container purple-card mx-auto" style="max-width: 800px;">
            <div class="profile-header">
                <h2><i class="bi bi-person-circle"></i> Hồ sơ người dùng</h2>
            </div>
            
            <div class="avatar-container">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="/webbanhang/public/uploads/<?php echo $user['avatar']; ?>" alt="Avatar" class="avatar">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="Default Avatar" class="avatar">
                <?php endif; ?>
            </div>
            
            <div class="user-info text-center mb-4">
                <h3><?php echo htmlspecialchars($user['username']); ?></h3>
                <p class="text-muted"><i class="bi bi-calendar-check"></i> Thành viên từ: <?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
            </div>
            
            <?php if (SessionHelper::hasFlash('success')): ?>
                <div class="success-message">
                    <?php echo SessionHelper::getFlash('success'); ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($errors)): ?>
                <div class="error-message">
                    <?php foreach($errors as $error): ?>
                        <div><?php echo $error; ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
              <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                        <i class="bi bi-person"></i> Thông tin cá nhân
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">
                        <i class="bi bi-key"></i> Đổi mật khẩu
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="profileTabsContent">
                <!-- Thông tin cá nhân -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <form action="/webbanhang/Auth/profile" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="update_profile" value="1">
                        
                        <div class="form-group">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                            <small class="form-text text-muted">Tên đăng nhập không thể thay đổi.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="age" class="form-label">Tuổi</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="number" id="age" name="age" class="form-control" min="1" max="120" value="<?php echo htmlspecialchars($user['age'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="avatar" class="form-label">Thay đổi ảnh đại diện</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-image"></i></span>
                                <input type="file" id="avatar" name="avatar" class="form-control">
                            </div>
                            <small class="form-text text-muted">Để trống nếu không muốn thay đổi.</small>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Đổi mật khẩu -->
                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                    <form action="/webbanhang/Auth/profile" method="post">
                        <input type="hidden" name="change_password" value="1">
                        
                        <div class="form-group">
                            <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" id="current_password" name="current_password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password" class="form-label">Mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" id="new_password" name="new_password" class="form-control" required>
                            </div>
                            <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự.</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-key"></i> Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
              <div class="mt-4 text-center">
                <a href="/webbanhang/" class="btn btn-secondary">
                    <i class="bi bi-house-door"></i> Quay lại trang chủ
                </a>
                <a href="/webbanhang/Auth/logout" class="btn btn-outline-danger ms-2">
                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
    
    <?php include 'app/views/shares/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
