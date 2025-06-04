<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
    <?php
    // Load Database if not already loaded
    if (!class_exists('Database')) {
        require_once('app/config/database.php');
    }
    // Load UserModel if not already loaded
    if (!class_exists('UserModel')) {
        require_once('app/models/UserModel.php');
    }
    ?>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <h1>Quản lý người dùng</h1>
        
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
        
        <div class="mb-3">
            <a href="/webbanhang/User/pending" class="btn btn-primary">
                Người dùng chờ duyệt
                <?php 
                $userModel = new UserModel((new Database())->getConnection());
                $pendingCount = $userModel->getPendingUsersCount(); 
                if ($pendingCount > 0): 
                ?>
                <span class="badge bg-danger"><?= $pendingCount ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>                            <th>ID</th>
                            <th>Tên đăng nhập</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php if ($user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php elseif ($user['role'] == 'staff'): ?>
                                            <span class="badge bg-warning">Nhân viên</span>
                                        <?php else: ?>                                            <span class="badge bg-info">Khách hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($user['status'] == 'pending'): ?>
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                        <?php elseif ($user['status'] == 'approved'): ?>
                                            <span class="badge bg-success">Đã duyệt</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Từ chối</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <a href="/webbanhang/User/edit/<?= $user['id'] ?>" class="btn btn-sm btn-primary">Sửa</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có người dùng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
