<?php 
require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php'; 
?>

<div class="row">
    <!-- Sidebar Filter -->
    <div class="col-lg-3 mb-4">
        <div class="card purple-card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lọc sản phẩm</h5>
                <button class="btn btn-sm d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterSidebar">
                    <i class="bi bi-funnel"></i>
                </button>
            </div>
            <div class="collapse show" id="filterSidebar">
                <div class="card-body">
                    <form id="filterForm" action="/webbanhang/Product" method="GET">
                        <!-- Search -->
                        <div class="mb-4">
                            <label for="searchProduct" class="form-label">Tìm kiếm</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="searchProduct" name="search" 
                                       placeholder="Tên sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button class="btn btn-purple" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-4">
                            <label class="form-label">Khoảng giá</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" placeholder="Từ" 
                                           value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" placeholder="Đến" 
                                           value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <label class="form-label">Danh mục</label>
                            <div class="category-list">
                                <?php
                                // Get categories from database
                                require_once('app/models/CategoryModel.php');
                                $categoryModel = new CategoryModel((new Database())->getConnection());
                                $categories = $categoryModel->getCategories();
                                
                                $selectedCategories = isset($_GET['categories']) ? $_GET['categories'] : [];

                                foreach ($categories as $category): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" 
                                           id="category<?php echo $category->id; ?>" value="<?php echo $category->id; ?>"
                                           <?php echo in_array($category->id, $selectedCategories) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="category<?php echo $category->id; ?>">
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Apply/Reset Filters -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-purple">
                                <i class="bi bi-funnel-fill me-1"></i> Áp dụng bộ lọc
                            </button>
                            <a href="/webbanhang/Product" class="btn btn-outline-purple">
                                <i class="bi bi-x-circle me-1"></i> Xóa bộ lọc
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Listing -->
    <div class="col-lg-9">
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
        <?php else: ?>            <!-- Card View -->
            <div id="cardView" class="row g-4">
                <?php foreach ($products as $product): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 product-card-enhanced">
                        <div class="card-image-container">
                            <?php if (!empty($product->image)): ?>
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                    <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         class="card-img-top product-image" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                                </a>
                            <?php else: ?>
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top product-image" alt="No Image">
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($product->category_name)): ?>
                            <div class="category-badge">
                                <span class="badge-category">
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="product-overlay">
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-overlay">
                                    <i class="bi bi-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                        
                        <div class="card-body-enhanced">
                            <h5 class="product-title">
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="product-link">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            
                            <p class="product-description">
                                <?php 
                                    $desc = strip_tags($product->description);
                                    echo strlen($desc) > 80 ? substr($desc, 0, 80).'...' : $desc; 
                                ?>
                            </p>
                            
                            <div class="product-rating">
                                <?php for($i=1; $i<=4; $i++): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php endfor; ?>
                                <i class="bi bi-star-half"></i>
                                <span class="rating-text">(4.5)</span>
                            </div>
                            
                            <div class="price-section">
                                <span class="price-current"><?php echo number_format($product->price, 0, ',', '.'); ?> đ</span>
                            </div>
                        </div>                        
                        <div class="card-footer-enhanced">
                            <?php if (SessionHelper::isCustomer()): ?>
                            <button class="btn btn-cart add-to-cart-btn" data-product-id="<?php echo $product->id; ?>" 
                                    data-url="/webbanhang/Cart/add/<?php echo $product->id; ?>?stay_on_page=true">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                            </button>
                            <?php endif; ?>
                            
                            <?php if (SessionHelper::isAdmin()): ?>
                            <div class="admin-actions">
                                <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-edit">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                   class="btn btn-delete">
                                    <i class="bi bi-trash"></i> Xóa
                                </a>
                            </div>
                            <?php endif; ?>
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
                                ?>                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if (SessionHelper::isCustomer()): ?>
                                    <a href="/webbanhang/Cart/add/<?php echo $product->id; ?>?stay_on_page=true" 
                                       class="btn btn-success add-to-cart-btn" data-product-id="<?php echo $product->id; ?>">
                                        <i class="bi bi-cart-plus"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if (SessionHelper::isAdmin()): ?>
                                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-purple">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');" 
                                       class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

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
        });        // Handle toast notifications for "Add to Cart" buttons
        $('.add-to-cart-btn').click(function(e) {
            e.preventDefault();
            
            const productId = $(this).data('product-id');
            const url = $(this).data('url') || $(this).attr('href');
            
            // Add loading state
            const btn = $(this);
            const originalText = btn.html();
            btn.html('<i class="bi bi-arrow-repeat spin"></i> Đang thêm...').prop('disabled', true);
            
            // Make an AJAX request to add the product to cart
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Show the toast notification
                    const toast = new bootstrap.Toast(document.getElementById('cartToast'));
                    toast.show();
                    
                    // Reset button
                    btn.html('<i class="bi bi-check"></i> Đã thêm').removeClass('btn-cart').addClass('btn-success');
                    
                    // Update the cart count in the navbar
                    setTimeout(function() {
                        btn.html(originalText).removeClass('btn-success').addClass('btn-cart').prop('disabled', false);
                    }, 2000);
                },
                error: function() {
                    // Reset button on error
                    btn.html(originalText).prop('disabled', false);
                    // If AJAX fails, fall back to regular link
                    window.location.href = url;
                }
            });
        });
    });
</script>

<style>
    /* Enhanced Product Cards */
    .product-card-enhanced {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        background: #fff;
        position: relative;
    }
    
    .product-card-enhanced:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .card-image-container {
        position: relative;
        overflow: hidden;
        height: 250px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card-enhanced:hover .product-image {
        transform: scale(1.1);
    }
    
    .category-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        z-index: 2;
    }
    
    .badge-category {
        background: linear-gradient(135deg, var(--purple-primary), var(--purple-secondary));
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card-enhanced:hover .product-overlay {
        opacity: 1;
    }
    
    .btn-overlay {
        background: rgba(255,255,255,0.9);
        color: var(--purple-primary);
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }
    
    .btn-overlay:hover {
        background: white;
        color: var(--purple-primary);
        transform: scale(1.05);
    }
    
    .card-body-enhanced {
        padding: 25px;
        background: white;
    }
    
    .product-title {
        margin-bottom: 12px;
        font-size: 1.1rem;
        line-height: 1.4;
    }
    
    .product-link {
        color: #2d3748;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }
    
    .product-link:hover {
        color: var(--purple-primary);
    }
    
    .product-description {
        color: #718096;
        font-size: 0.9rem;
        line-height: 1.6;
        margin-bottom: 15px;
        min-height: 48px;
    }
    
    .product-rating {
        margin-bottom: 15px;
    }
    
    .product-rating i {
        color: #fbbf24;
        margin-right: 2px;
        font-size: 0.9rem;
    }
    
    .rating-text {
        color: #718096;
        font-size: 0.85rem;
        margin-left: 8px;
    }
    
    .price-section {
        margin-bottom: 20px;
    }
    
    .price-current {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--purple-primary);
        background: linear-gradient(135deg, var(--purple-primary), var(--purple-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .card-footer-enhanced {
        padding: 20px 25px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn-cart {
        width: 100%;
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .btn-cart:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(72, 187, 120, 0.3);
        color: white;
    }
    
    .btn-cart:active {
        transform: translateY(0);
    }
    
    .admin-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-edit {
        flex: 1;
        background: linear-gradient(135deg, var(--purple-primary), var(--purple-secondary));
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-edit:hover {
        background: linear-gradient(135deg, var(--purple-secondary), var(--purple-primary));
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(139, 69, 190, 0.3);
        color: white;
    }
    
    .btn-delete {
        flex: 1;
        background: linear-gradient(135deg, #e53e3e, #c53030);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #c53030, #9c2626);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(197, 48, 48, 0.3);
        color: white;
    }
    
    /* Spinning animation for loading state */
    .spin {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-image-container {
            height: 200px;
        }
        
        .card-body-enhanced {
            padding: 20px;
        }
        
        .card-footer-enhanced {
            padding: 15px 20px;
        }
        
        .price-current {
            font-size: 1.3rem;
        }
    }
    
    /* Table styles */
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
    
    /* Additional styles for sidebar */
    .category-list {
        max-height: 200px;
        overflow-y: auto;
    }
    
    @media (max-width: 991px) {
        #filterSidebar {
            margin-bottom: 1rem;
        }
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>