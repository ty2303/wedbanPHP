<?php
// Ensure user is admin
if (!SessionHelper::isAdmin()) {
    header('Location: /webbanhang');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Người dùng chờ duyệt - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <h1>Người dùng chờ duyệt</h1>
        
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
        
        <div class="card">
            <div class="card-body">
                <?php if (empty($pendingUsers)): ?>
                    <div class="alert alert-info">
                        Không có người dùng nào đang chờ duyệt.
                    </div>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên đăng nhập</th>
                                <th>Email</th>
                                <th>Vai trò</th>
                                <th>Ngày đăng ký</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingUsers as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td>
                                        <?php if ($user['role'] == 'admin'): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php elseif ($user['role'] == 'staff'): ?>
                                            <span class="badge bg-warning">Nhân viên</span>
                                        <?php else: ?>
                                            <span class="badge bg-info">Khách hàng</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                                    <td>
                                        <div class="d-flex">
                                            <form method="POST" action="/webbanhang/User/pending">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="approve" class="btn btn-sm btn-success me-1">Phê duyệt</button>
                                            </form>
                                            <form method="POST" action="/webbanhang/User/pending">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="reject" class="btn btn-sm btn-danger">Từ chối</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                
                <div class="mt-3">
                    <a href="/webbanhang/User" class="btn btn-primary">Quay lại danh sách người dùng</a>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
