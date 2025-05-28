<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="purple-title">Danh sách sản phẩm</h1>
    <a href="/webbanhang/Product/add" class="btn btn-purple">
        <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
    </a>
</div>

<!-- View toggle buttons -->
<div class="d-flex justify-content-center mb-4">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-outline-purple active" id="cardViewBtn">
            <i class="bi bi-grid"></i> Card View
        </button>
        <button type="button" class="btn btn-outline-purple" id="tableViewBtn">
            <i class="bi bi-table"></i> Table View
        </button>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-purple">
        <i class="bi bi-info-circle"></i> Chưa có sản phẩm nào. Hãy thêm sản phẩm mới!
    </div>
<?php else: ?>
    <!-- Card View -->
    <div id="cardView" class="row g-4">
        <?php foreach ($products as $product): ?>
        <div class="col-12 col-md-6 col-lg-4 col-xl-3">
            <div class="card h-100 shadow-sm" style="max-width: 320px; margin: 0 auto;">
                <?php if (!empty($product->image)): ?>
                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="card-img-top" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                             style="height: 200px; object-fit: contain;">
                    </a>
                <?php else: ?>
                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No Image">
                    </a>
                <?php endif; ?>
                
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-decoration-none text-dark">
                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </h5>
                    <p class="card-text description-box"><?php echo $product->description; ?></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 mb-0"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</span>
                        <div>
                            <?php for($i=1; $i<=4; $i++): ?>
                                <i class="bi bi-star-fill text-warning"></i>
                            <?php endfor; ?>
                            <i class="bi bi-star-half text-warning"></i>
                            <small class="text-muted">(4.5)</small>
                        </div>
                    </div>
                    <?php if (!empty($product->category_name)): ?>
                    <div class="mt-2">
                        <span class="badge" style="background-color: var(--purple-secondary);">
                            <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between mb-2">
                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-primary btn-sm">
                            <i class="bi bi-eye"></i> Xem
                        </a>
                        <a href="/webbanhang/Cart/add/<?php echo $product->id; ?>?stay_on_page=true" 
                           class="btn btn-success btn-sm add-to-cart-btn" data-product-id="<?php echo $product->id; ?>">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </a>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-purple btn-sm flex-grow-1 mx-1">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                           class="btn btn-outline-purple btn-sm flex-grow-1 mx-1">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Table View (DataTables) -->
    <div id="tableView" class="d-none">
        <table id="productTable" class="table table-striped table-hover">
            <thead class="table-purple">
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product->id; ?></td>
                    <td>
                        <?php if (!empty($product->image)): ?>
                            <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                 class="img-thumbnail" width="50" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/50x50?text=No+Image" class="img-thumbnail" width="50" alt="No Image">
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <?php if (!empty($product->category_name)): ?>
                            <span class="badge" style="background-color: var(--purple-secondary);">
                                <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                            </span>
                        <?php else: ?>
                            <em class="text-muted">Không có</em>
                        <?php endif; ?>
                    </td>
                    <td><?php echo number_format($product->price, 0, ',', '.'); ?> đ</td>
                    <td>
                        <?php 
                            $desc = strip_tags($product->description);
                            echo strlen($desc) > 50 ? substr($desc, 0, 50).'...' : $desc; 
                        ?>
                    </td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="/webbanhang/Cart/add/<?php echo $product->id; ?>?stay_on_page=true" 
                               class="btn btn-success add-to-cart-btn" data-product-id="<?php echo $product->id; ?>">
                                <i class="bi bi-cart-plus"></i>
                            </a>
                            <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-purple">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                               class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Toast notification for cart -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="cartToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="bi bi-cart-check-fill me-2"></i> Sản phẩm đã được thêm vào giỏ hàng!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#productTable').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ sản phẩm",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ sản phẩm",
                infoEmpty: "Không có sản phẩm nào",
                infoFiltered: "(lọc từ _MAX_ sản phẩm)",
                zeroRecords: "Không tìm thấy sản phẩm nào",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                }
            },
            responsive: true
        });

        // Toggle view buttons
        $('#cardViewBtn').click(function() {
            $(this).addClass('active');
            $('#tableViewBtn').removeClass('active');
            $('#cardView').removeClass('d-none');
            $('#tableView').addClass('d-none');
        });

        $('#tableViewBtn').click(function() {
            $(this).addClass('active');
            $('#cardViewBtn').removeClass('active');
            $('#tableView').removeClass('d-none');
            $('#cardView').addClass('d-none');
        });

        // Handle toast notifications for "Add to Cart" buttons
        $('.add-to-cart-btn').click(function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            const url = $(this).attr('href');
            
            // Make an AJAX request to add the product to cart
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Show the toast notification
                    const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                    toast.show();
                    
                    // Update the cart count in the navbar
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                },
                error: function() {
                    // If AJAX fails, fall back to regular link
                    window.location.href = url;
                }
            });
        });
    });
</script>

<style>
    .table-purple thead {
        background: linear-gradient(to right, var(--purple-primary), var(--purple-secondary));
        color: white;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: var(--purple-secondary);
        border-color: var(--purple-primary);
        color: white !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--purple-light);
        border-color: var(--purple-secondary);
        color: var(--purple-primary) !important;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>