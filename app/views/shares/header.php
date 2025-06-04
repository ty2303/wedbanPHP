<?php
// Load SessionHelper if not already loaded
if (!class_exists('SessionHelper')) {
    require_once('app/helpers/SessionHelper.php');
}
SessionHelper::start();

// Calculate cart count
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cartCount += $quantity;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TITI Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <style>
        /* Add padding to body to prevent content from being hidden under fixed navbar */
        body {
            padding-top: 76px; /* Adjust this value based on your navbar height */
        }
        
        /* Optional: Add shadow to navbar when scrolled */
        .navbar-shadow {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-purple fixed-top mb-4" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/webbanhang">
                <div class="d-flex align-items-center">
                    <img src="/webbanhang/public/logo/a.png" alt="TITI Shop" height="40" class="me-2">
                    <span class="d-none d-sm-inline">TITI Shop</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/">
                            <div class="icon-container">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            Danh sách sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/add">
                            <div class="icon-container">
                                <i class="bi bi-bag-plus"></i>
                            </div>
                            Thêm sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Category/">
                            <div class="icon-container">
                                <i class="bi bi-folder2"></i>
                            </div>
                            Quản lý danh mục
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/webbanhang/Cart">
                            <div class="icon-container">
                                <i class="bi bi-cart"></i>
                                <?php if ($cartCount > 0): ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        <?php echo $cartCount; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            Giỏ hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Cart/orders">
                            <div class="icon-container">
                                <i class="bi bi-clipboard-check"></i>
                            </div>
                            Đơn hàng
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Report">
                            <div class="icon-container">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            Báo cáo
                        </a>
                    </li>
                    
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="d-flex align-items-center">
                                    <?php 
                                    $user = SessionHelper::getUser();
                                    if (!empty($user['avatar'])): 
                                    ?>
                                        <img src="/webbanhang/public/uploads/<?php echo $user['avatar']; ?>" alt="Avatar" class="user-avatar">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle me-1"></i>
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars(SessionHelper::get('username')); ?>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="/webbanhang/Auth/profile"><i class="bi bi-person me-2"></i>Hồ sơ</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/webbanhang/Auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Đăng xuất</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Auth/login">
                                <div class="icon-container">
                                    <i class="bi bi-box-arrow-in-right"></i>
                                </div>
                                Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Auth/register">
                                <div class="icon-container">
                                    <i class="bi bi-person-plus"></i>
                                </div>
                                Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <?php if (SessionHelper::hasFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo SessionHelper::getFlash('success'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (SessionHelper::hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo SessionHelper::getFlash('error'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    
    <script>
        // Add shadow effect to navbar when scrolling
        $(window).scroll(function() {
            if ($(this).scrollTop() > 10) {
                $('#mainNavbar').addClass('navbar-shadow');
            } else {
                $('#mainNavbar').removeClass('navbar-shadow');
            }
        });
    </script>
