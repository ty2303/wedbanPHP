<?php
require_once('app/config/database.php');
require_once('app/models/UserModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/middleware/AuthMiddleware.php');

class UserController
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    public function index()
    {
        // Chỉ admin mới có thể truy cập
        AuthMiddleware::requireAdmin();
        
        $users = $this->userModel->getAllUsers();
        
        include 'app/views/user/list.php';
    }
    
    public function edit($id = null)
    {
        // Chỉ admin mới có thể truy cập
        AuthMiddleware::requireAdmin();
        
        if (!$id) {
            SessionHelper::setFlash('error', 'ID người dùng không hợp lệ!');
            header('Location: /webbanhang/User');
            exit;
        }
        
        $user = $this->userModel->getUserById($id);
        
        if (!$user) {
            SessionHelper::setFlash('error', 'Không tìm thấy người dùng!');
            header('Location: /webbanhang/User');
            exit;
        }
        
        $errors = [];
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $role = $_POST['role'] ?? '';
            
            if (empty($role)) {
                $errors[] = "Vai trò không được để trống";
            } elseif (!in_array($role, [UserModel::ROLE_ADMIN, UserModel::ROLE_STAFF, UserModel::ROLE_CUSTOMER])) {
                $errors[] = "Vai trò không hợp lệ";
            } else {
                if ($this->userModel->changeUserRole($id, $role)) {
                    SessionHelper::setFlash('success', 'Cập nhật vai trò thành công!');
                    header('Location: /webbanhang/User');
                    exit;
                } else {
                    $errors[] = "Đã xảy ra lỗi khi cập nhật vai trò";
                }
            }
        }
        
        include 'app/views/user/edit.php';
    }
    
    // Thêm phương thức quản lý người dùng đang chờ duyệt
    public function pending()
    {
        // Chỉ admin mới có thể truy cập
        AuthMiddleware::requireAdmin();
        
        // Lấy danh sách người dùng đang chờ duyệt
        $pendingUsers = $this->userModel->getPendingUsers();
        
        // Xử lý phê duyệt/từ chối người dùng
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['approve']) && !empty($_POST['user_id'])) {
                $userId = $_POST['user_id'];
                if ($this->userModel->approveUser($userId)) {
                    SessionHelper::setFlash('success', 'Đã phê duyệt người dùng thành công!');
                } else {
                    SessionHelper::setFlash('error', 'Đã xảy ra lỗi khi phê duyệt người dùng!');
                }
                header('Location: /webbanhang/User/pending');
                exit;
            } else if (isset($_POST['reject']) && !empty($_POST['user_id'])) {
                $userId = $_POST['user_id'];
                if ($this->userModel->rejectUser($userId)) {
                    SessionHelper::setFlash('success', 'Đã từ chối người dùng thành công!');
                } else {
                    SessionHelper::setFlash('error', 'Đã xảy ra lỗi khi từ chối người dùng!');
                }
                header('Location: /webbanhang/User/pending');
                exit;
            }
        }
        
        include 'app/views/user/pending.php';
    }
}
