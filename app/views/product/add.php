<?php include 'app/views/shares/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="purple-title">Thêm sản phẩm mới</h1>
</div>

<?php if (!empty($errors)): ?>
<div class="alert alert-purple">
    <ul class="mb-0">
        <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="card purple-card mb-4">
    <div class="card-body p-4">
        <form method="POST" action="/webbanhang/Product/save" onsubmit="return validateForm();" class="purple-form needs-validation" enctype="multipart/form-data" novalidate>
            <div class="mb-4">
                <label for="name" class="form-label">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="form-text">Tên sản phẩm phải có từ 5 đến 100 ký tự</div>
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            
            <div class="mb-4">
                <label for="price" class="form-label">Giá:</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    <span class="input-group-text">đ</span>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="image" class="form-label">Hình ảnh sản phẩm:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Chọn ảnh định dạng JPG, PNG hoặc GIF</div>
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="form-label">Danh mục:</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="/webbanhang/Product" class="btn btn-outline-purple me-md-2">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-purple">
                    <i class="bi bi-plus-circle"></i> Thêm sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Include CKEditor from CDN -->
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
<script>
    // Initialize CKEditor on the description field
    CKEDITOR.replace('description', {
        language: 'vi',
        toolbar: [
            ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'],
            ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'],
            ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
            ['Link', 'Unlink'],
            ['Image', 'Table'],
            ['Format', 'FontSize'],
            ['TextColor', 'BGColor'],
            ['Maximize', 'Source']
        ],
        height: 300
    });

    // Update form validation to work with CKEditor
    function validateForm() {
        let name = document.getElementById('name').value;
        let price = document.getElementById('price').value;
        let description = CKEDITOR.instances.description.getData();
        let errors = [];
        
        if (name.length < 5 || name.length > 100) {
            errors.push('Tên sản phẩm phải có từ 5 đến 100 ký tự.');
        }
        
        if (price <= 0 || isNaN(price)) {
            errors.push('Giá phải là một số dương lớn hơn 0.');
        }
        
        if (description.trim() === '') {
            errors.push('Mô tả không được để trống.');
        }
        
        if (errors.length > 0) {
            alert(errors.join('\n'));
            return false;
        }
        
        return true;
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>