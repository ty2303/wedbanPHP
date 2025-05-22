<?php include 'app/views/shares/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="purple-title">Thêm danh mục mới</h1>
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
        <form method="POST" action="/webbanhang/Category/save" class="purple-form needs-validation" novalidate>
            <div class="mb-4">
                <label for="name" class="form-label">Tên danh mục:</label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="invalid-feedback">Vui lòng nhập tên danh mục.</div>
            </div>
            
            <div class="mb-4">
                <label for="description" class="form-label">Mô tả:</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                <a href="/webbanhang/Category" class="btn btn-outline-purple me-md-2">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <button type="submit" class="btn btn-purple">
                    <i class="bi bi-plus-circle"></i> Thêm danh mục
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>

<?php include 'app/views/shares/footer.php'; ?>