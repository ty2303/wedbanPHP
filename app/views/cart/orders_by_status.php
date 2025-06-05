<?php include 'app/views/shares/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Header với thống kê -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-box-seam"></i> Quản lý đơn hàng</h2>
                <div class="btn-group">
                    <a href="/webbanhang/Cart/orders" class="btn btn-outline-secondary">
                        <i class="bi bi-list"></i> Tất cả đơn hàng
                    </a>
                </div>
            </div>

            <!-- Thống kê theo trạng thái -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5><i class="bi bi-bar-chart"></i> Thống kê theo trạng thái</h5>
                        </div>                        <div class="card-body">
                            <?php 
                            // Chuyển đổi stats array thành associative array để dễ truy cập
                            $statsAssoc = [];
                            if (!empty($stats)) {
                                foreach ($stats as $stat) {
                                    $statsAssoc[$stat['status']] = $stat['count'];
                                }
                            }
                            ?>
                            <div class="row">
                                <?php foreach (OrderModel::$statusLabels as $status => $label): 
                                    $count = $statsAssoc[$status] ?? 0;
                                    $color = OrderModel::$statusColors[$status];
                                    $active = ($current_status === $status) ? 'border-primary' : '';
                                ?>
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <a href="/webbanhang/Cart/ordersByStatus/<?php echo $status; ?>" class="text-decoration-none">
                                        <div class="card text-center h-100 <?php echo $active; ?>">
                                            <div class="card-body">
                                                <span class="badge bg-<?php echo $color; ?> fs-6 mb-2"><?php echo $count; ?></span>
                                                <h6 class="card-title"><?php echo $label; ?></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bộ lọc trạng thái -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5>
                            <i class="bi bi-funnel"></i> 
                            <?php if ($current_status): ?>
                                Đơn hàng: <?php echo OrderModel::$statusLabels[$current_status]; ?>
                            <?php else: ?>
                                Tất cả đơn hàng
                            <?php endif; ?>
                        </h5>
                        
                        <div class="btn-group">
                            <a href="/webbanhang/Cart/ordersByStatus" 
                               class="btn btn-sm <?php echo !$current_status ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                                Tất cả
                            </a>
                            <?php foreach (OrderModel::$statusLabels as $status => $label): ?>
                            <a href="/webbanhang/Cart/ordersByStatus/<?php echo $status; ?>" 
                               class="btn btn-sm <?php echo $current_status === $status ? 'btn-primary' : 'btn-outline-secondary'; ?>">
                                <?php echo $label; ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php if (empty($orders)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-inbox display-1 text-muted"></i>
                            <h4 class="text-muted mt-3">Không có đơn hàng nào</h4>
                            <p class="text-muted">
                                <?php if ($current_status): ?>
                                    Không có đơn hàng nào ở trạng thái "<?php echo OrderModel::$statusLabels[$current_status]; ?>"
                                <?php else: ?>
                                    Chưa có đơn hàng nào trong hệ thống
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Khách hàng</th>
                                        <th>Điện thoại</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Số sản phẩm</th>
                                        <th>Ngày đặt</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><strong>#<?php echo $order->id; ?></strong></td>
                                        <td>
                                            <div>
                                                <strong><?php echo htmlspecialchars($order->name); ?></strong>
                                                <br>
                                                <small class="text-muted"><?php echo htmlspecialchars($order->email); ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($order->phone); ?></td>
                                        <td>
                                            <?php if ($order->discount_amount > 0): ?>
                                                <div class="text-decoration-line-through text-muted small">
                                                    <?php echo number_format($order->subtotal, 0, ',', '.'); ?> đ
                                                </div>
                                            <?php endif; ?>
                                            <strong class="text-primary">
                                                <?php echo number_format($order->total_amount, 0, ',', '.'); ?> đ
                                            </strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo OrderModel::$statusColors[$order->status]; ?>">
                                                <?php echo OrderModel::$statusLabels[$order->status]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo $order->item_count; ?></span>
                                        </td>
                                        <td>
                                            <div>
                                                <?php echo date('d/m/Y', strtotime($order->created_at)); ?>
                                                <br>
                                                <small class="text-muted"><?php echo date('H:i', strtotime($order->created_at)); ?></small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="/webbanhang/Cart/orderDetails/<?php echo $order->id; ?>" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Chi tiết
                                                </a>
                                                <?php if (in_array($order->status, ['pending', 'confirmed', 'processing'])): ?>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" 
                                                            type="button" data-bs-toggle="dropdown">
                                                        <i class="bi bi-gear"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <?php if ($order->status === 'pending'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'confirmed')">
                                                            <i class="bi bi-check-circle text-info"></i> Xác nhận
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')">
                                                            <i class="bi bi-x-circle text-danger"></i> Hủy đơn
                                                        </a></li>
                                                        <?php elseif ($order->status === 'confirmed'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'processing')">
                                                            <i class="bi bi-arrow-clockwise text-primary"></i> Bắt đầu xử lý
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')">
                                                            <i class="bi bi-x-circle text-danger"></i> Hủy đơn
                                                        </a></li>
                                                        <?php elseif ($order->status === 'processing'): ?>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'packed')">
                                                            <i class="bi bi-box-seam text-secondary"></i> Đóng gói xong
                                                        </a></li>
                                                        <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $order->id; ?>, 'cancelled')">
                                                            <i class="bi bi-x-circle text-danger"></i> Hủy đơn
                                                        </a></li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
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
        </div>
    </div>
</div>

<script>
function updateStatus(orderId, status) {
    if (confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng này?')) {
        // Tạo form ẩn để submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/webbanhang/Cart/updateOrderStatus/' + orderId;
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        
        form.appendChild(statusInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
