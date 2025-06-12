<?php 
require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php'; 
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Danh sách sản phẩm</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></li>
    </ol>
</nav>

<div class="card purple-card mb-5">
    <div class="card-body p-4">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-5 mb-4 mb-md-0 text-center">
                <?php if (!empty($product->image)): ?>
                    <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                         class="img-fluid rounded shadow" style="max-height: 400px; object-fit: contain;">
                <?php else: ?>
                    <img src="https://via.placeholder.com/600x400?text=No+Image" class="img-fluid rounded shadow" alt="No Image">
                <?php endif; ?>
            </div>
            
            <!-- Product Details -->
            <div class="col-md-7">
                <h1 class="h2 mb-3"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h1>
                
                <?php if (!empty($product->category_name)): ?>
                <div class="mb-3">
                    <span class="badge" style="background-color: var(--purple-secondary);">
                        <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
                <?php endif; ?>
                
                <div class="d-flex align-items-center mb-3">
                    <div class="me-3">
                        <?php for($i=1; $i<=4; $i++): ?>
                            <i class="bi bi-star-fill text-warning"></i>
                        <?php endfor; ?>
                        <i class="bi bi-star-half text-warning"></i>
                        <small class="text-muted">(4.5)</small>
                    </div>
                    <span class="text-muted">|</span>
                    <div class="ms-3">
                        <i class="bi bi-eye"></i> 123 lượt xem
                    </div>
                </div>
                  <div class="price-display mb-4">
                    <span class="price-current"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</span>
                </div>
                
                <div class="mb-4">
    <h5>Mô tả chi tiết:</h5>
    <div class="text-muted">
        <?php echo $product->description; ?>
    </div>
</div>
                  <?php if (SessionHelper::isCustomer()): ?>
                <div class="customer-actions mb-4">
                    <form action="/webbanhang/Cart/add/<?php echo $product->id; ?>" method="post" class="d-flex align-items-center flex-wrap gap-3">
                        <div class="quantity-selector">
                            <label class="form-label mb-2">Số lượng:</label>
                            <div class="input-group quantity-group">
                                <button type="button" class="btn btn-outline-purple" onclick="decrementQty()">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="10">
                                <button type="button" class="btn btn-outline-purple" onclick="incrementQty()">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-cart-large">
                                <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
                            </button>
                            <button type="button" class="btn btn-wishlist-large">
                                <i class="bi bi-heart me-2"></i> Yêu thích
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
                  <div class="border-top pt-3 mt-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="/webbanhang/Product" class="btn btn-back">
                            <i class="bi bi-arrow-left me-2"></i> Quay lại danh sách
                        </a>
                        <?php if (SessionHelper::isAdmin()): ?>
                        <div class="admin-actions-detail">
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-edit-detail">
                                <i class="bi bi-pencil me-2"></i> Sửa sản phẩm
                            </a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                               class="btn btn-delete-detail">
                                <i class="bi bi-trash me-2"></i> Xóa sản phẩm
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional product details -->
<div class="card purple-card">
    <div class="card-header bg-white">
        <ul class="nav nav-tabs card-header-tabs" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs" 
                        type="button" role="tab" aria-selected="true">Thông số kỹ thuật</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" 
                        type="button" role="tab" aria-selected="false">Đánh giá</button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="productTabsContent">
            <!-- Specifications Tab -->
            <div class="tab-pane fade show active" id="specs" role="tabpanel" aria-labelledby="specs-tab">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">ID Sản phẩm:</th>
                                <td><?php echo $product->id; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Tên sản phẩm:</th>
                                <td><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Danh mục:</th>
                                <td><?php echo !empty($product->category_name) ? 
                                    htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8') : 
                                    '<em>Không có danh mục</em>'; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Giá bán:</th>
                                <td><?php echo number_format($product->price, 0, ',', '.'); ?> đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                <div class="text-center py-5">
                    <i class="bi bi-chat-square-text" style="font-size: 3rem; color: var(--purple-secondary);"></i>
                    <h4 class="mt-3">Chưa có đánh giá nào</h4>
                    <p class="text-muted">Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                    <button class="btn btn-purple mt-2">
                        <i class="bi bi-star"></i> Viết đánh giá
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Related products -->
<h3 class="mt-5 mb-4 purple-title">Sản phẩm tương tự</h3>

<div class="row g-4">
    <?php if (empty($relatedProducts)): ?>
        <div class="col-12">
            <div class="alert alert-purple">
                <i class="bi bi-info-circle"></i> Không có sản phẩm tương tự trong cùng danh mục.
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($relatedProducts as $relatedProduct): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <?php if (!empty($relatedProduct->image)): ?>
                    <a href="/webbanhang/Product/show/<?php echo $relatedProduct->id; ?>">
                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($relatedProduct->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($relatedProduct->name, ENT_QUOTES, 'UTF-8'); ?>"
                             style="height: 200px; object-fit: contain;">
                    </a>
                <?php else: ?>
                    <a href="/webbanhang/Product/show/<?php echo $relatedProduct->id; ?>">
                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                    </a>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/webbanhang/Product/show/<?php echo $relatedProduct->id; ?>" class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($relatedProduct->name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h5>
                    <p class="card-text text-muted">
                        <?php 
                            $desc = htmlspecialchars($relatedProduct->description, ENT_QUOTES, 'UTF-8');
                            echo strlen($desc) > 50 ? substr($desc, 0, 50).'...' : $desc; 
                        ?>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h6 mb-0"><?php echo number_format($relatedProduct->price, 0, ',', '.'); ?> đ</span>
                        <div>
                            <i class="bi bi-star-fill text-warning"></i>
                            <small class="text-muted">(4.0)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function incrementQty() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
    }
}

function decrementQty() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

// Enhanced cart functionality
document.addEventListener('DOMContentLoaded', function() {
    const cartForm = document.querySelector('form[action*="/Cart/add/"]');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
              const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;

            // Add loading state
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i> Đang thêm...';
            submitBtn.disabled = true;

            // Make AJAX request to add to cart
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success feedback
                    submitBtn.innerHTML = '<i class="bi bi-check me-2"></i> Đã thêm vào giỏ!';
                    submitBtn.classList.remove('btn-cart-large');
                    submitBtn.classList.add('btn-success');
                    
                    // Reset after 2 seconds
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.classList.remove('btn-success');
                        submitBtn.classList.add('btn-cart-large');
                        submitBtn.disabled = false;
                    }, 2000);
                } else {
                    // Show error feedback
                    alert(data.message);
                    if (data.message.includes('đăng nhập')) {
                        window.location.href = '/webbanhang/Auth/login';
                    }
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
              // Add loading state
            submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i> Đang thêm...';
            submitBtn.disabled = true;
        });
    }
});
</script>

<style>
/* Enhanced Product Detail Styles */
.price-display {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    padding: 20px;
    border-radius: 15px;
    border-left: 5px solid var(--purple-primary);
}

.price-current {
    font-size: 2.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.customer-actions {
    background: linear-gradient(135deg, #f8f9fa, #ffffff);
    padding: 25px;
    border-radius: 20px;
    border: 2px solid #e9ecef;
}

.quantity-selector {
    flex: 0 0 auto;
}

.quantity-group {
    width: 140px;
}

.quantity-group .btn {
    border: 2px solid var(--purple-primary);
    color: var(--purple-primary);
    width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-group .btn:hover {
    background: var(--purple-primary);
    color: white;
}

.quantity-group .form-control {
    border: 2px solid var(--purple-primary);
    font-weight: 600;
    font-size: 1.1rem;
}

.action-buttons {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.btn-cart-large {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
    border: none;
    padding: 15px 30px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-cart-large:hover {
    background: linear-gradient(135deg, #38a169, #2f855a);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(72, 187, 120, 0.4);
    color: white;
}

.btn-wishlist-large {
    background: linear-gradient(135deg, #e53e3e, #c53030);
    color: white;
    border: none;
    padding: 15px 25px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-wishlist-large:hover {
    background: linear-gradient(135deg, #c53030, #9c2626);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(197, 48, 48, 0.4);
    color: white;
}

.btn-back {
    background: linear-gradient(135deg, #718096, #4a5568);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: linear-gradient(135deg, #4a5568, #2d3748);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(74, 85, 104, 0.3);
    color: white;
}

.admin-actions-detail {
    display: flex;
    gap: 10px;
}

.btn-edit-detail {
    background: linear-gradient(135deg, var(--purple-primary), var(--purple-secondary));
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-edit-detail:hover {
    background: linear-gradient(135deg, var(--purple-secondary), var(--purple-primary));
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139, 69, 190, 0.3);
    color: white;
}

.btn-delete-detail {
    background: linear-gradient(135deg, #e53e3e, #c53030);
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-delete-detail:hover {
    background: linear-gradient(135deg, #c53030, #9c2626);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(197, 48, 48, 0.3);
    color: white;
}

/* Enhanced card styles for product image */
.card.purple-card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card.purple-card .card-body {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
}

/* Enhanced related products */
.card.h-100 {
    border: none;
    border-radius: 15px;
    transition: all 0.3s ease;
    overflow: hidden;
}

.card.h-100:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.card.h-100 .card-img-top {
    transition: transform 0.3s ease;
}

.card.h-100:hover .card-img-top {
    transform: scale(1.05);
}

/* Loading animation */
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .price-current {
        font-size: 2rem;
    }
    
    .customer-actions {
        padding: 20px;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-cart-large,
    .btn-wishlist-large {
        width: 100%;
        padding: 12px 20px;
        font-size: 1rem;
    }
    
    .admin-actions-detail {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-edit-detail,
    .btn-delete-detail {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 576px) {
    .quantity-selector .form-label {
        font-size: 0.9rem;
    }
    
    .quantity-group {
        width: 120px;
    }
    
    .quantity-group .btn {
        width: 40px;
    }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>