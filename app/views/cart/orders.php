
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <h1 class="purple-title mb-5 text-center">Quản lý đơn hàng</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-purple mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo $_SESSION['success']; ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <?php if (empty($orders)): ?>
        <div class="alert alert-purple">
            <i class="bi bi-info-circle"></i> Chưa có đơn hàng nào.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table purple-table">                <thead>
                    <tr>                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Số điện thoại</th>
                        <th>Tạm tính</th>
                        <th>Giảm giá</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Số sản phẩm</th>
                        <th>Ngày đặt</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>                        <td><?php echo $order->id; ?></td>
                        <td><?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo number_format($order->subtotal ?? $order->total_amount, 0, ',', '.'); ?> đ</td>
                        <td>
                            <?php if (!empty($order->voucher_code) && $order->discount_amount > 0): ?>
                                <span class="text-success">
                                    <i class="bi bi-ticket-perforated"></i>
                                    <?php echo number_format($order->discount_amount, 0, ',', '.'); ?> đ
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>                        <td><?php echo number_format($order->total_amount, 0, ',', '.'); ?> đ</td>
                        <td>
                            <?php if (isset($order->status)): ?>
                                <span class="badge bg-<?php echo OrderModel::$statusColors[$order->status]; ?>">
                                    <?php echo OrderModel::$statusLabels[$order->status]; ?>
                                </span>
                            <?php else: ?>
                                <span class="badge bg-warning">Chờ xử lý</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $order->item_count; ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td>
                            <a href="/webbanhang/Cart/orderDetails/<?php echo $order->id; ?>" class="btn btn-purple btn-sm">
                                <i class="bi bi-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>