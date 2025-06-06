
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <h1 class="purple-title mb-5 text-center">Giỏ hàng của bạn</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-purple mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cart_items)): ?>
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-cart-x" style="font-size: 5rem; color: var(--purple-secondary);"></i>
            </div>
            <h2 class="mb-3">Giỏ hàng của bạn đang trống!</h2>
            <p class="text-muted mb-4">Hãy thêm một số sản phẩm vào giỏ hàng của bạn.</p>
            <a href="/webbanhang/Product" class="btn btn-purple">
                <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
            </a>
        </div>
    <?php else: ?>
        <div class="card purple-card mb-4">
            <div class="card-body">
                <form action="/webbanhang/Cart/update" method="post">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" width="55%">Sản phẩm</th>
                                    <th scope="col" class="text-center" width="15%">Giá</th>
                                    <th scope="col" class="text-center" width="15%">Số lượng</th>
                                    <th scope="col" class="text-end" width="15%">Tạm tính</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($item['product']->image)): ?>
                                                <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($item['product']->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                                     alt="<?php echo htmlspecialchars($item['product']->name, ENT_QUOTES, 'UTF-8'); ?>" 
                                                     class="rounded me-3" style="width: 80px; height: 80px; object-fit: contain;">
                                            <?php else: ?>
                                                <img src="https://via.placeholder.com/80x80?text=No+Image" 
                                                     alt="No Image" class="rounded me-3">
                                            <?php endif; ?>
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="/webbanhang/Product/show/<?php echo $item['product']->id; ?>" class="text-decoration-none text-dark">
                                                        <?php echo htmlspecialchars($item['product']->name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </a>
                                                </h5>
                                                <a href="/webbanhang/Cart/remove/<?php echo $item['product']->id; ?>" 
                                                   class="text-danger text-decoration-none small">
                                                    <i class="bi bi-trash"></i> Xóa
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <?php echo number_format($item['product']->price, 0, ',', '.'); ?> đ
                                    </td>
                                    <td class="text-center">
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary qty-btn" onclick="decrementQty(<?php echo $item['product']->id; ?>)">-</button>
                                            <input type="number" class="form-control text-center" 
                                                   name="quantities[<?php echo $item['product']->id; ?>]" 
                                                   value="<?php echo $item['quantity']; ?>" min="1">
                                            <button type="button" class="btn btn-outline-secondary qty-btn" onclick="incrementQty(<?php echo $item['product']->id; ?>)">+</button>
                                        </div>
                                    </td>
                                    <td class="text-end fw-bold">
                                        <?php echo number_format($item['subtotal'], 0, ',', '.'); ?> đ
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-3">
                        <a href="/webbanhang/Cart/clear" class="btn btn-outline-danger me-2">
                            <i class="bi bi-trash me-1"></i> Xóa giỏ hàng
                        </a>
                        <button type="submit" class="btn btn-purple">
                            <i class="bi bi-arrow-repeat me-1"></i> Cập nhật giỏ hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
          <div class="row">
            <div class="col-md-6 ms-auto">
                <!-- Voucher Section -->
                <div class="card purple-card mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['applied_voucher'])): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
                                <div>
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong><?php echo htmlspecialchars($_SESSION['applied_voucher']['code'], ENT_QUOTES, 'UTF-8'); ?></strong>
                                    <br>
                                    <small><?php echo htmlspecialchars($_SESSION['applied_voucher']['name'], ENT_QUOTES, 'UTF-8'); ?></small>
                                    <br>
                                    <small class="text-success">Giảm: <?php echo number_format($_SESSION['applied_voucher']['discount'], 0, ',', '.'); ?> đ</small>
                                </div>
                                <a href="/webbanhang/Cart/removeVoucher" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-x"></i>
                                </a>
                            </div>
                        <?php else: ?>
                            <form id="voucher-form" class="d-flex">
                                <input type="text" class="form-control me-2" id="voucher_code" name="voucher_code" 
                                       placeholder="Nhập mã giảm giá" style="text-transform: uppercase;">
                                <button type="submit" class="btn btn-purple">Áp dụng</button>
                            </form>
                            <div id="voucher-message" class="mt-2"></div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Cart Total Section -->
                <div class="card purple-card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">Tổng giỏ hàng</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</span>
                        </div>
                        <?php if ($discount > 0): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-success">Giảm giá</span>
                            <span class="text-success">-<?php echo number_format($discount, 0, ',', '.'); ?> đ</span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span>Phí vận chuyển</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold fs-5">Tổng cộng</span>
                            <span class="fw-bold fs-5"><?php echo number_format($total, 0, ',', '.'); ?> đ</span>
                        </div>
                        <div class="d-grid mt-4">
                            <a href="/webbanhang/Cart/checkout" class="btn btn-purple btn-lg">
                                <i class="bi bi-credit-card me-2"></i> Tiến hành thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function incrementQty(productId) {
    const input = document.querySelector(`input[name="quantities[${productId}]"]`);
    input.value = parseInt(input.value) + 1;
}

function decrementQty(productId) {
    const input = document.querySelector(`input[name="quantities[${productId}]"]`);
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Voucher form handling
document.addEventListener('DOMContentLoaded', function() {
    const voucherForm = document.getElementById('voucher-form');
    const voucherMessage = document.getElementById('voucher-message');
    
    if (voucherForm) {
        voucherForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            const voucherCode = document.getElementById('voucher_code').value.trim().toUpperCase();
            const cartTotal = <?php echo $subtotal; ?>;
            
            if (!voucherCode) {
                showVoucherMessage('Vui lòng nhập mã voucher!', 'danger');
                return;
            }
            
            formData.append('voucher_code', voucherCode);
            formData.append('cart_total', cartTotal);
            
            // Show loading state
            const submitBtn = voucherForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang xử lý...';
            submitBtn.disabled = true;
            
            fetch('/webbanhang/Cart/applyVoucher', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showVoucherMessage(data.message, 'success');
                    // Reload page after a short delay to show updated cart
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showVoucherMessage(data.message, 'danger');
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showVoucherMessage('Có lỗi xảy ra. Vui lòng thử lại!', 'danger');
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
    
    function showVoucherMessage(message, type) {
        if (voucherMessage) {
            voucherMessage.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>`;
        }
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>