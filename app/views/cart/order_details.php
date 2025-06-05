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

    <div class="row">
        <!-- Cột trái: Thông tin đơn hàng và sản phẩm -->
        <div class="col-lg-8">
            <div class="card purple-card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Chi tiết đơn hàng #<?php echo $order->id; ?></h4>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-<?php echo OrderModel::$statusColors[$order->status]; ?> me-2">
                            <?php echo OrderModel::$statusLabels[$order->status]; ?>
                        </span>
                        <?php if (!empty($order->tracking_number)): ?>
                        <span class="badge bg-info">
                            <i class="bi bi-truck"></i> <?php echo $order->tracking_number; ?>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <h5 class="text-muted">Thông tin khách hàng</h5>
                            <p class="mb-1"><strong>Họ tên:</strong> <?php echo htmlspecialchars($order->name, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($order->email, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order->phone, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="mb-1"><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></p>
                            <?php if (!empty($order->estimated_delivery)): ?>
                            <p class="mb-1"><strong>Dự kiến giao:</strong> <?php echo date('d/m/Y', strtotime($order->estimated_delivery)); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted">Địa chỉ giao hàng</h5>
                            <p><?php echo htmlspecialchars($order->address, ENT_QUOTES, 'UTF-8'); ?></p>
                            
                            <?php if (!empty($order->admin_notes)): ?>
                            <h5 class="text-muted mt-3">Ghi chú từ cửa hàng</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                <?php echo htmlspecialchars($order->admin_notes, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                            <?php endif; ?>
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
                    <tfoot>                        <tr>
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
        </div>

        <!-- Cột phải: Timeline trạng thái và form cập nhật -->
        <div class="col-lg-4">
            <!-- Timeline trạng thái đơn hàng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="bi bi-clock-history"></i> Lịch sử đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($status_history as $index => $history): 
                            $is_current = ($history->status === $order->status);
                            $icon_class = $is_current ? 'bi-circle-fill' : 'bi-circle';
                            $color_class = OrderModel::$statusColors[$history->status];
                        ?>
                        <div class="timeline-item <?php echo $is_current ? 'current' : ''; ?>">
                            <div class="timeline-marker">
                                <i class="bi <?php echo $icon_class; ?> text-<?php echo $color_class; ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1"><?php echo OrderModel::$statusLabels[$history->status]; ?></h6>
                                <p class="text-muted small mb-1">
                                    <?php echo date('d/m/Y H:i', strtotime($history->changed_at)); ?>
                                </p>
                                <?php if (!empty($history->notes)): ?>
                                <p class="small mb-1"><?php echo htmlspecialchars($history->notes); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($history->changed_by_name)): ?>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-person"></i> <?php echo htmlspecialchars($history->changed_by_name); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Form cập nhật trạng thái (chỉ admin/staff) -->
            <?php if (SessionHelper::isAdmin() || SessionHelper::isStaff()): ?>
            <div class="card">
                <div class="card-header">
                    <h5><i class="bi bi-gear"></i> Cập nhật đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/webbanhang/Cart/updateOrderStatus/<?php echo $order->id; ?>">                        <div class="mb-3">
                            <label for="status" class="form-label">Chọn trạng thái đơn hàng</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">-- Chọn trạng thái --</option>
                                <?php foreach (OrderModel::$statusLabels as $status => $label): ?>
                                <option value="<?php echo $status; ?>" 
                                        <?php echo ($status === $order->status) ? 'selected' : ''; ?>
                                        class="status-option status-<?php echo $status; ?>">
                                    <?php echo $label; ?>
                                    <?php if ($status === $order->status): ?>
                                        (Hiện tại)
                                    <?php endif; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i>
                                    Trạng thái hiện tại: <strong><?php echo OrderModel::$statusLabels[$order->status]; ?></strong>
                                </small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estimated_delivery" class="form-label">Ngày giao dự kiến</label>
                            <input type="date" class="form-control" id="estimated_delivery" name="estimated_delivery" 
                                   value="<?php echo $order->estimated_delivery; ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Thêm ghi chú về việc cập nhật trạng thái..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="text-center mt-4">        <a href="/webbanhang/Cart/orders" class="btn btn-outline-purple me-2">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
        <a href="/webbanhang/Product" class="btn btn-purple">
            <i class="bi bi-bag me-1"></i> Tiếp tục mua sắm
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('status');
    const currentStatus = '<?php echo $order->status; ?>';
    
    if (statusSelect) {
        // Highlight current status option
        statusSelect.addEventListener('focus', function() {
            const currentOption = this.querySelector(`option[value="${currentStatus}"]`);
            if (currentOption) {
                currentOption.style.fontWeight = 'bold';
                currentOption.style.backgroundColor = '#e6e0ff';
            }
        });
        
        // Add confirmation for status change
        statusSelect.addEventListener('change', function() {
            const selectedStatus = this.value;
            const selectedLabel = this.options[this.selectedIndex].text;
            
            if (selectedStatus && selectedStatus !== currentStatus) {
                const currentLabel = '<?php echo OrderModel::$statusLabels[$order->status]; ?>';
                
                // Show confirmation message
                const confirmMsg = `Bạn có chắc muốn thay đổi trạng thái từ "${currentLabel}" sang "${selectedLabel.replace(' (Hiện tại)', '')}"?`;
                
                if (!confirm(confirmMsg)) {
                    this.value = currentStatus; // Reset to current status
                }
            }
        });
        
        // Form submission confirmation
        const statusForm = statusSelect.closest('form');
        if (statusForm) {
            statusForm.addEventListener('submit', function(e) {
                const selectedStatus = statusSelect.value;
                const notes = document.getElementById('notes').value;
                
                if (!selectedStatus) {
                    e.preventDefault();
                    alert('Vui lòng chọn trạng thái!');
                    return;
                }
                
                if (selectedStatus === currentStatus && !notes.trim()) {
                    e.preventDefault();
                    alert('Vui lòng thêm ghi chú khi giữ nguyên trạng thái!');
                    return;
                }
            });
        }
    }
});
</script>

<?php include 'app/views/shares/footer.php'; ?>