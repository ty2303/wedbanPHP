<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/webbanhang/Product" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="/webbanhang/Cart/orders" class="text-decoration-none">Đơn hàng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết đơn hàng #<?php echo $order->id; ?></li>
            </ol>
        </nav>
    </div>
    
    <div class="card purple-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Chi tiết đơn hàng #<?php echo $order->id; ?></h4>
            <span class="badge bg-success">Đã tiếp nhận</span>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5 class="text-muted">Thông tin khách hàng</h5>
                    <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
                <div class="col-md-6">
                    <h5 class="text-muted">Địa chỉ giao hàng</h5>
                    <p><?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
            
            <h5 class="text-muted mb-3">Sản phẩm đã đặt</h5>
            <div class="table-responsive mb-3">
                <table class="table table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-end">Giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $order_total = 0;
                        foreach ($order_details as $item): 
                            $subtotal = $item->price * $item->quantity;
                            $order_total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($item->image)): ?>
                                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($item->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                             class="me-3" alt="<?php echo htmlspecialchars($item->name ?? 'Sản phẩm đã xóa', ENT_QUOTES, 'UTF-8'); ?>"
                                             style="width: 50px; height: 50px; object-fit: contain;">
                                    <?php else: ?>
                                        <img src="https://via.placeholder.com/50x50?text=No+Image" class="me-3" alt="No Image">
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item->name)): ?>
                                        <a href="/webbanhang/Product/show/<?php echo $item->product_id; ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Sản phẩm đã xóa (ID: <?php echo $item->product_id; ?>)</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-end"><?php echo number_format($item->price, 0, ',', '.'); ?> đ</td>
                            <td class="text-center"><?php echo $item->quantity; ?></td>
                            <td class="text-end"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                        </tr>
                        <?php endforeach; ?>                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tạm tính:</strong></td>
                            <td class="text-end"><?php echo number_format($order->subtotal ?? $order_total, 0, ',', '.'); ?> đ</td>
                        </tr>
                        <?php if (!empty($order->voucher_code) && $order->discount_amount > 0): ?>
                        <tr>
                            <td colspan="3" class="text-end text-success">
                                <strong>
                                    <i class="bi bi-ticket-perforated me-1"></i>
                                    Giảm giá (<?php echo htmlspecialchars($order->voucher_code); ?>):
                                </strong>
                            </td>
                            <td class="text-end text-success">-<?php echo number_format($order->discount_amount, 0, ',', '.'); ?> đ</td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Phí vận chuyển:</strong></td>
                            <td class="text-end">Miễn phí</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                            <td class="text-end"><strong><?php echo number_format($order->total_amount, 0, ',', '.'); ?> đ</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="alert alert-light border">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        <span class="fw-bold">Phương thức thanh toán:</span> Thanh toán khi nhận hàng (COD)
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-light border">
                        <i class="bi bi-calendar-fill text-primary me-2"></i>
                        <span class="fw-bold">Ngày đặt hàng:</span> <?php echo date('d/m/Y H:i:s', strtotime($order->created_at)); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center">
        <a href="/webbanhang/Cart/orders" class="btn btn-outline-purple me-2">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
        <a href="/webbanhang/Product" class="btn btn-purple">
            <i class="bi bi-bag me-1"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>