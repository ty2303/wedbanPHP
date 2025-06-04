<?php
require_once('app/config/database.php');
require_once('app/models/UserModel.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/middleware/AuthMiddleware.php');

class AuthController
{
    private $userModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->userModel = new UserModel($this->db);
    }

    public function login()
    {
        // Check if user is already logged in
        AuthMiddleware::requireGuest();
        
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $result = $this->userModel->login($username, $password);
            
            if (is_array($result) && isset($result[0])) {
                // Login failed, set error message
                $errors = $result;
            } else {
                // Login successful, set session and redirect
                SessionHelper::setUser($result);
                SessionHelper::setFlash('success', 'Đăng nhập thành công!');
                header('Location: /webbanhang/');
                exit;
            }
        }
        
        include 'app/views/auth/login.php';
    }    public function register()
    {
        // Check if user is already logged in
        AuthMiddleware::requireGuest();
        
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $age = $_POST['age'] ?? null;
            
            // Validate password confirmation
            if ($password !== $confirm_password) {
                $errors[] = "Mật khẩu xác nhận không khớp";
            } else {
                // Process avatar upload if exists
                $avatar = null;
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'public/uploads/';
                    $temp_name = $_FILES['avatar']['tmp_name'];
                    $name = basename($_FILES['avatar']['name']);
                    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    
                    // Check file extension
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($file_ext, $allowed_exts)) {
                        $new_name = time() . '_' . $name;
                        if (move_uploaded_file($temp_name, $upload_dir . $new_name)) {
                            $avatar = $new_name;
                        } else {
                            $errors[] = "Không thể tải lên ảnh đại diện";
                        }
                    } else {
                        $errors[] = "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif)";
                    }
                }
                
                if (empty($errors)) {
                    $result = $this->userModel->register($username, $email, $password, $avatar, $age);
                    
                    if ($result === true) {
                        // Registration successful, redirect to login
                        SessionHelper::setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                        header('Location: /webbanhang/Auth/login');
                        exit;
                    } else {
                        // Registration failed, set error message
                        $errors = $result;
                    }
                }
            }
        }
        
        include 'app/views/auth/register.php';
    }

    public function logout()
    {
        SessionHelper::logout();
        SessionHelper::setFlash('success', 'Đăng xuất thành công!');
        header('Location: /webbanhang/Auth/login');
        exit;
    }    public function profile()
    {
        // Check if user is logged in
        AuthMiddleware::requireLogin();
        
        $user = $this->userModel->getUserById(SessionHelper::getUserId());
        if (!$user) {
            SessionHelper::logout();
            header('Location: /webbanhang/Auth/login');
            exit;
        }
        
        $errors = [];
        $success = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_profile'])) {
                $email = $_POST['email'] ?? '';
                $age = $_POST['age'] ?? null;
                
                // Process avatar upload if exists
                $avatar = $user['avatar']; // Keep existing avatar by default
                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                    $upload_dir = 'public/uploads/';
                    $temp_name = $_FILES['avatar']['tmp_name'];
                    $name = basename($_FILES['avatar']['name']);
                    $file_ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    
                    // Check file extension
                    $allowed_exts = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($file_ext, $allowed_exts)) {
                        $new_name = time() . '_' . $name;
                        if (move_uploaded_file($temp_name, $upload_dir . $new_name)) {
                            $avatar = $new_name;
                        } else {
                            $errors[] = "Không thể tải lên ảnh đại diện";
                        }
                    } else {
                        $errors[] = "Chỉ chấp nhận file ảnh (jpg, jpeg, png, gif)";
                    }
                }
                
                if (empty($errors)) {
                    if ($this->userModel->updateProfile(SessionHelper::getUserId(), $email, $avatar, $age)) {
                        $success = "Cập nhật thông tin thành công";
                        $user = $this->userModel->getUserById(SessionHelper::getUserId()); // Refresh user data
                    } else {
                        $errors[] = "Đã xảy ra lỗi khi cập nhật thông tin";
                    }
                }
            } elseif (isset($_POST['change_password'])) {
                $current_password = $_POST['current_password'] ?? '';
                $new_password = $_POST['new_password'] ?? '';
                $confirm_password = $_POST['confirm_password'] ?? '';
                
                if ($new_password !== $confirm_password) {
                    $errors[] = "Mật khẩu xác nhận không khớp";
                } else {
                    $result = $this->userModel->changePassword(SessionHelper::getUserId(), $current_password, $new_password);
                    
                    if ($result === true) {
                        $success = "Đổi mật khẩu thành công";
                    } else {
                        $errors = $result;
                    }
                }
            }
        }
        
        include 'app/views/auth/profile.php';
    }
}
?>
