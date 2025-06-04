<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu vai trò - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h3 mb-0">Hệ thống phân quyền TITI Shop</h1>
                    </div>
                    <div class="card-body">
                        <h2 class="h4 mb-4">Chúng tôi đã triển khai hệ thống phân quyền với 3 vai trò:</h2>
                        
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-danger">
                                    <div class="card-header bg-danger text-white">
                                        <h5 class="mb-0"><i class="bi bi-shield-lock me-2"></i>Admin</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý người dùng</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý sản phẩm</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý danh mục</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý đơn hàng</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Xem báo cáo</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Phân quyền người dùng</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-warning">
                                    <div class="card-header bg-warning text-dark">
                                        <h5 class="mb-0"><i class="bi bi-people me-2"></i>Staff</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý sản phẩm</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý danh mục</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý đơn hàng</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Xem báo cáo</li>
                                            <li><i class="bi bi-x-circle text-danger me-2"></i>Không thể quản lý người dùng</li>
                                            <li><i class="bi bi-x-circle text-danger me-2"></i>Không thể phân quyền</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-4">
                                <div class="card h-100 border-info">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0"><i class="bi bi-person me-2"></i>Customer</h5>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-unstyled">
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Xem sản phẩm</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Đặt hàng</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Quản lý giỏ hàng</li>
                                            <li><i class="bi bi-check-circle text-success me-2"></i>Xem đơn hàng của mình</li>
                                            <li><i class="bi bi-x-circle text-danger me-2"></i>Không thể thêm/sửa/xóa sản phẩm</li>
                                            <li><i class="bi bi-x-circle text-danger me-2"></i>Không thể xem báo cáo</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3">
                            <p class="mb-0">Mặc định, khi đăng ký mới, người dùng sẽ được gán vai trò <strong>Customer</strong>. Chỉ <strong>Admin</strong> mới có thể thay đổi vai trò của người dùng.</p>
                        </div>
                        
                        <div class="mt-4 text-center">
                            <a href="/webbanhang/" class="btn btn-primary">Quay lại trang chủ</a>
                            <?php if (SessionHelper::isAdmin()): ?>
                            <a href="/webbanhang/User" class="btn btn-outline-primary ms-2">Quản lý người dùng</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
