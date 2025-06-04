<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="purple-title">Quản lý Voucher</h1>
    <a href="/webbanhang/Voucher/add" class="btn btn-purple">
        <i class="bi bi-plus-circle"></i> Thêm voucher mới
    </a>
</div>

<?php if (empty($vouchers)): ?>
    <div class="alert alert-purple">
        <i class="bi bi-info-circle"></i> Chưa có voucher nào. Hãy thêm voucher mới!
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table purple-table">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Tên voucher</th>
                    <th>Loại giảm</th>
                    <th>Giá trị</th>
                    <th>Đơn tối thiểu</th>
                    <th>Áp dụng cho</th>
                    <th>Sử dụng</th>
                    <th>Trạng thái</th>
                    <th>Hạn sử dụng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vouchers as $voucher): ?>
                <tr>
                    <td>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($voucher->code, ENT_QUOTES, 'UTF-8'); ?></span>
                    </td>
                    <td>
                        <strong><?php echo htmlspecialchars($voucher->name, ENT_QUOTES, 'UTF-8'); ?></strong>
                        <?php if (!empty($voucher->description)): ?>
                            <br><small class="text-muted"><?php echo htmlspecialchars($voucher->description, ENT_QUOTES, 'UTF-8'); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($voucher->discount_type == 'percentage'): ?>
                            <span class="badge bg-success">Phần trăm</span>
                        <?php else: ?>
                            <span class="badge bg-info">Cố định</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($voucher->discount_type == 'percentage'): ?>
                            <?php echo $voucher->discount_value; ?>%
                            <?php if ($voucher->max_discount_amount): ?>
                                <br><small class="text-muted">Tối đa: <?php echo number_format($voucher->max_discount_amount, 0, ',', '.'); ?>đ</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo number_format($voucher->discount_value, 0, ',', '.'); ?>đ
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($voucher->min_order_amount > 0): ?>
                            <?php echo number_format($voucher->min_order_amount, 0, ',', '.'); ?>đ
                        <?php else: ?>
                            <span class="text-muted">Không</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        switch($voucher->applies_to) {
                            case 'all_products':
                                echo '<span class="badge bg-secondary">Tất cả SP</span>';
                                break;
                            case 'specific_products':
                                echo '<span class="badge bg-warning">SP cụ thể</span>';
                                break;
                            case 'specific_categories':
                                echo '<span class="badge bg-info">Danh mục</span>';
                                break;
                        }
                        ?>
                    </td>
                    <td>
                        <?php if ($voucher->usage_limit): ?>
                            <?php echo $voucher->used_count; ?>/<?php echo $voucher->usage_limit; ?>
                        <?php else: ?>
                            <?php echo $voucher->used_count; ?>/∞
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                        $now = date('Y-m-d H:i:s');
                        if (!$voucher->is_active) {
                            echo '<span class="badge bg-danger">Tắt</span>';
                        } elseif ($now < $voucher->start_date) {
                            echo '<span class="badge bg-warning">Chưa bắt đầu</span>';
                        } elseif ($now > $voucher->end_date) {
                            echo '<span class="badge bg-danger">Hết hạn</span>';
                        } else {
                            echo '<span class="badge bg-success">Đang hoạt động</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <small>
                            Từ: <?php echo date('d/m/Y H:i', strtotime($voucher->start_date)); ?><br>
                            Đến: <?php echo date('d/m/Y H:i', strtotime($voucher->end_date)); ?>
                        </small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="/webbanhang/Voucher/edit/<?php echo $voucher->id; ?>" 
                               class="btn btn-purple btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="/webbanhang/Voucher/delete/<?php echo $voucher->id; ?>" 
                               onclick="return confirm('Bạn có chắc chắn muốn xóa voucher này?');" 
                               class="btn btn-outline-danger btn-sm">
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

<?php include 'app/views/shares/footer.php'; ?>
