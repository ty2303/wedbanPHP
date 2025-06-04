<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/webbanhang/Cart" class="text-decoration-none">Giỏ hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
            </ol>
        </nav>
    </div>
    
    <h1 class="purple-title mb-5 text-center">Thanh toán</h1>
    
    <?php if (isset($_SESSION['checkout_errors'])): ?>
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                <?php foreach ($_SESSION['checkout_errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
            <?php unset($_SESSION['checkout_errors']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="row g-5">
        <div class="col-md-5 order-md-last">
            <div class="card purple-card h-100">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Đơn hàng của bạn</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush mb-3">
                        <?php foreach ($cart_items as $item): ?>
                        <li class="list-group-item d-flex justify-content-between lh-sm py-3">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($item['product']->name, ENT_QUOTES, 'UTF-8'); ?></h6>
                                <small class="text-muted"><?php echo $item['quantity']; ?> x <?php echo number_format($item['product']->price, 0, ',', '.'); ?> đ</small>
                            </div>
                            <span class="text-muted"><?php echo number_format($item['subtotal'], 0, ',', '.'); ?> đ</span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Tạm tính</span>
                        <span><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</span>
                    </div>
                    <?php if (isset($_SESSION['applied_voucher'])): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-success">
                            <i class="bi bi-ticket-perforated me-1"></i>
                            Giảm giá (<?php echo htmlspecialchars($_SESSION['applied_voucher']['code']); ?>)
                        </span>
                        <span class="text-success">-<?php echo number_format($discount, 0, ',', '.'); ?> đ</span>
                    </div>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>Phí vận chuyển</span>
                        <span class="text-success">Miễn phí</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-0">
                        <span class="fw-bold">Tổng cộng</span>
                        <span class="fw-bold fs-4"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card purple-card">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Thông tin thanh toán</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/Cart/placeOrder" class="purple-form needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?php echo isset($_SESSION['checkout_data']['name']) ? $_SESSION['checkout_data']['name'] : ''; ?>" required>
                            <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_SESSION['checkout_data']['email']) ? $_SESSION['checkout_data']['email'] : ''; ?>" required>
                            <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?php echo isset($_SESSION['checkout_data']['phone']) ? $_SESSION['checkout_data']['phone'] : ''; ?>" 
                                   pattern="[0-9]{10,11}" required>
                            <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ (10-11 số)</div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required><?php echo isset($_SESSION['checkout_data']['address']) ? $_SESSION['checkout_data']['address'] : ''; ?></textarea>
                            <div class="invalid-feedback">Vui lòng nhập địa chỉ giao hàng</div>
                        </div>
                        
                        <div class="mb-4">
                            <h5>Phương thức thanh toán</h5>
                            <div class="form-check mb-2">
                                <input type="radio" class="form-check-input" id="payment-cod" name="payment_method" value="cod" checked required>
                                <label class="form-check-label" for="payment-cod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                            <a href="/webbanhang/Cart" class="btn btn-outline-purple">
                                <i class="bi bi-arrow-left me-1"></i> Quay lại giỏ hàng
                            </a>
                            <button type="submit" class="btn btn-purple btn-lg px-5">
                                <i class="bi bi-check-circle me-1"></i> Đặt hàng
                            </button>
                        </div>
                    </form>
                    <?php unset($_SESSION['checkout_data']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include 'app/views/shares/footer.php'; ?>