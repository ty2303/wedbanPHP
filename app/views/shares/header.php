
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/webbanhang/public/css/purple-theme.css">
  
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-purple mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/webbanhang">
                <div class="d-flex align-items-center">
                    <span class="bg-white text-purple p-2 me-2 rounded-circle">
                        <i class="bi bi-shop"></i>
                    </span>
                    Quản lý sản phẩm
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
                    <?php
                    // After initializing session if not already done
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }
                    // Calculate cart count
                    $cartCount = 0;
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $quantity) {
                            $cartCount += $quantity;
                        }
                    }
                    ?>
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
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">