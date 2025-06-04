<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết lập phân quyền - TITI Shop</title>
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>
    
    <main class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1 class="h3 mb-0">Thiết lập hệ thống phân quyền</h1>
            </div>
            <div class="card-body">
                <p>Chào mừng bạn đến với trang thiết lập phân quyền cho TITI Shop. Công cụ này sẽ giúp bạn thiết lập hệ thống phân quyền với 3 vai trò: Admin, Staff và Customer.</p>
                
                <div class="alert alert-info">
                    <h4>Thông tin các vai trò:</h4>
                    <ul>
                        <li><strong>Admin:</strong> Có quyền quản lý toàn bộ hệ thống, bao gồm quản lý người dùng, sản phẩm, danh mục, đơn hàng và xem báo cáo.</li>
                        <li><strong>Staff:</strong> Có quyền quản lý sản phẩm, danh mục, xử lý đơn hàng và xem báo cáo.</li>
                        <li><strong>Customer:</strong> Có quyền mua hàng và xem lịch sử đơn hàng của mình.</li>
                    </ul>
                </div>
                
                <p>Bấm vào nút dưới đây để bắt đầu thiết lập phân quyền:</p>
                
                <a href="/webbanhang/Setup/setupRoles" class="btn btn-primary">Thiết lập phân quyền</a>
            </div>
        </div>
    </main>
    
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>
