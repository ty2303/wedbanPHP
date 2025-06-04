<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/middleware/AuthMiddleware.php');
require_once('app/helpers/SessionHelper.php');


class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index()
    {
        // Chỉ admin và staff mới có thể xem danh sách danh mục
        AuthMiddleware::requireStaff();
        
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add()
    {
        // Chỉ admin và staff mới có thể thêm danh mục
        AuthMiddleware::requireStaff();
        
        include 'app/views/category/add.php';
    }

    public function save()
    {
        // Chỉ admin và staff mới có thể thêm danh mục
        AuthMiddleware::requireStaff();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $result = $this->categoryModel->addCategory($name, $description);
            if (is_array($result)) {
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                header('Location: /webbanhang/Category');
            }
        }
    }

    public function edit($id)
    {
        // Chỉ admin và staff mới có thể sửa danh mục
        AuthMiddleware::requireStaff();
        
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            echo "Không tìm thấy danh mục.";
        }
    }

    public function update()
    {
        // Chỉ admin và staff mới có thể cập nhật danh mục
        AuthMiddleware::requireStaff();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $edit = $this->categoryModel->updateCategory($id, $name, $description);
            if ($edit) {
                header('Location: /webbanhang/Category');
            } else {
                echo "Đã xảy ra lỗi khi lưu danh mục.";
            }
        }
    }

    public function delete($id)
    {
        // Chỉ admin và staff mới có thể xóa danh mục
        AuthMiddleware::requireStaff();
        
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /webbanhang/Category');
        } else {
            echo "Đã xảy ra lỗi khi xóa danh mục. Các sản phẩm trong danh mục này sẽ được giữ nguyên và chuyển thành không có danh mục.";
        }
    }
}
?>