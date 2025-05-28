<?php include 'app/views/shares/header.php'; ?>

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
                
                <div class="h3 mb-4 text-primary">
                    <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                </div>
                
                <div class="mb-4">
    <h5>Mô tả chi tiết:</h5>
    <div class="text-muted">
        <?php echo $product->description; ?>
    </div>
</div>
                
                <div class="d-flex flex-wrap mb-4 gap-2">
    <form action="/webbanhang/Cart/add/<?php echo $product->id; ?>" method="post" class="d-flex align-items-center">
        <div class="input-group me-2" style="width: 130px;">
            <button type="button" class="btn btn-outline-secondary" onclick="decrementQty()">-</button>
            <input type="number" class="form-control text-center" name="quantity" id="quantity" value="1" min="1" max="10">
            <button type="button" class="btn btn-outline-secondary" onclick="incrementQty()">+</button>
        </div>
        <button type="submit" class="btn btn-purple d-flex align-items-center">
            <i class="bi bi-cart-plus me-2"></i> Thêm vào giỏ hàng
        </button>
    </form>
    <button class="btn btn-outline-purple d-flex align-items-center">
        <i class="bi bi-heart me-2"></i> Yêu thích
    </button>
</div>
                
                <div class="border-top pt-3 mt-4">
                    <div class="d-flex justify-content-between">
                        <a href="/webbanhang/Product" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Quay lại danh sách
                        </a>
                        <div>
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-purple me-2">
                                <i class="bi bi-pencil"></i> Sửa
                            </a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                               class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Xóa
                            </a>
                        </div>
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
</script>

<?php include 'app/views/shares/footer.php'; ?>