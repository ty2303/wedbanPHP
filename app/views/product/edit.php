<?php include 'app/views/shares/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="purple-title">Sửa sản phẩm</h1>
</div>

<div class="card purple-card">
    <div class="card-body p-4">
        <form method="POST" action="/webbanhang/Product/update" class="purple-form needs-validation" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="id" value="<?php echo $product->id; ?>">
            
            <div class="mb-4">
                <label for="name" class="form-label">Tên sản phẩm:</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                <div class="form-text">Tên sản phẩm phải có từ 10 đến 100 ký tự</div>
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $product->description; ?></textarea>
            </div>
            
            <div class="mb-4">
                <label for="price" class="form-label">Giá:</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="price" name="price" 
                           value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" step="0.01" required>
                    <span class="input-group-text">đ</span>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="image" class="form-label">Hình ảnh sản phẩm:</label>
                <?php if (!empty($product->image)): ?>
                    <div class="mb-3 text-center">
                        <img src="/webbanhang/public/uploads/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="img-thumbnail" style="max-height: 200px;">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <div class="form-text">Chọn ảnh mới để thay đổi (định dạng JPG, PNG hoặc GIF)</div>
            </div>
            
            <div class="mb-4">
                <label for="category_id" class="form-label">Danh mục:</label>
                <select class="form-select" id="category_id" name="category_id">
                    <option value="">-- Không có danh mục --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>" <?php echo ($product->category_id == $category->id) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Chọn "Không có danh mục" nếu bạn muốn bỏ phân loại danh mục cho sản phẩm này.</div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="/webbanhang/Product" class="btn btn-outline-purple me-md-2">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-purple">
                    <i class="bi bi-save"></i> Lưu thay đổi
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

    // Form validation that works with CKEditor
    (function() {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function(form) {
            form.addEventListener('submit', function(event) {
                // Update CKEditor content to the textarea before validation
                for(var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
                
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                
                // Check if description is empty after stripping tags
                var description = CKEDITOR.instances.description.getData();
                if (description.trim() === '') {
                    event.preventDefault();
                    alert('Mô tả không được để trống.');
                }
                
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

<?php include 'app/views/shares/footer.php'; ?>