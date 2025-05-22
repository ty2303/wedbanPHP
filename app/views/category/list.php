<?php include 'app/views/shares/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="purple-title text-center mx-auto mb-4">Danh sách danh mục</h1>
</div>

<div class="text-end mb-4">
    <a href="/webbanhang/Category/add" class="btn btn-purple">
        <i class="bi bi-plus-circle"></i> Thêm danh mục mới
    </a>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-purple">
        <i class="bi bi-info-circle"></i> Chưa có danh mục nào. Hãy thêm danh mục mới!
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table purple-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category->id; ?></td>
                    <td><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($category->description, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="/webbanhang/Category/edit/<?php echo $category->id; ?>" class="btn btn-purple btn-sm">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Lưu ý: Điều này có thể ảnh hưởng đến các sản phẩm thuộc danh mục này.');" 
                           class="btn btn-outline-purple btn-sm ms-1">
                            <i class="bi bi-trash"></i> Xóa
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>