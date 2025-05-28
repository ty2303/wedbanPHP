<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="text-center mb-5">
        <div class="mb-4">
            <div style="width: 100px; height: 100px; background-color: var(--purple-light); border-radius: 50%; display: inline-flex; justify-content: center; align-items: center;">
                <i class="bi bi-check-lg text-success" style="font-size: 3rem;"></i>
            </div>
        </div>
        <h1 class="purple-title mb-3">Đặt hàng thành công!</h1>
        <p class="text-muted">Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.</p>
    </div>
    
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card purple-card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Đơn hàng #<?php echo $order->id; ?></h5>
                        <span class="badge bg-success">Đã tiếp nhận</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-muted mb-2">Thông tin khách hàng:</h6>
                            <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Địa chỉ giao hàng:</h6>
                            <p class="mb-0"><?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                    
                    <div class="table-responsive mb-3">
                        <table class="table">
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
                                                <span><?php echo htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Sản phẩm đã xóa (ID: <?php echo $item->product_id; ?>)</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="text-end"><?php echo number_format($item->price, 0, ',', '.'); ?> đ</td>
                                    <td class="text-center"><?php echo $item->quantity; ?></td>
                                    <td class="text-end"><?php echo number_format($subtotal, 0, ',', '.'); ?> đ</td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end"><strong><?php echo number_format($order_total, 0, ',', '.'); ?> đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="alert alert-light border">
                        <i class="bi bi-info-circle-fill text-primary me-2"></i>
                        Phương thức thanh toán: <strong>Thanh toán khi nhận hàng (COD)</strong>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <a href="/webbanhang/Product" class="btn btn-purple">
                    <i class="bi bi-bag me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>