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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $age = $_POST['age'] ?? null;
            $role = $_POST['role'] ?? 'customer';
            
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
                }                if (empty($errors)) {
                    // Nếu đăng ký là staff, set status là pending
                    $status = ($role === 'staff') ? 'pending' : 'approved';
                    $result = $this->userModel->register($username, $email, $password, $avatar, $age, $role, $status);
                    
                    if ($result === true) {
                        // Thông báo khác nhau cho staff và customer
                        if ($role === 'staff') {
                            SessionHelper::setFlash('success', 'Đăng ký thành công! Vui lòng chờ Admin phê duyệt tài khoản.');
                        } else {
                            SessionHelper::setFlash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
                        }
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
    public function forgotPassword()
    {
        require 'app/views/auth/forgot-password.php';
    }    public function sendResetLink()
    {
        if (!isset($_POST['email'])) {
            SessionHelper::setFlash('error', 'Vui lòng nhập email');
            header('Location: /webbanhang/Auth/forgotPassword');
            exit;
        }

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        error_log("Send Reset Link - Email: " . $email);

        // Xóa session cũ nếu có
        if (isset($_SESSION['reset_password_email'])) {
            unset($_SESSION['reset_password_email']);
        }

        $token = $this->userModel->createPasswordResetToken($email);
        if ($token) {
            require_once('app/helpers/EmailHelper.php');
            if (EmailHelper::sendPasswordResetEmail($email, $token)) {
                // Lưu email vào session
                $_SESSION['reset_password_email'] = $email;
                $_SESSION['reset_password_time'] = time();

                SessionHelper::setFlash('success', 'Mã xác nhận đã được gửi đến email của bạn. Vui lòng kiểm tra email và nhập mã.');
                header('Location: /webbanhang/Auth/forgotPassword');
                exit;
            } else {
                SessionHelper::setFlash('error', 'Không thể gửi email. Vui lòng thử lại sau');
                header('Location: /webbanhang/Auth/forgotPassword');
                exit;
            }
        } else {
            SessionHelper::setFlash('error', 'Email không tồn tại trong hệ thống');
            header('Location: /webbanhang/Auth/forgotPassword');
            exit;
        }
    }    public function resetPassword()
    {
        // Kiểm tra xem người dùng đã yêu cầu reset password chưa
        if (!isset($_SESSION['reset_password_email']) || !isset($_SESSION['reset_password_time'])) {
            error_log("resetPassword - Session missing: reset_password_email or reset_password_time");
            SessionHelper::setFlash('error', 'Vui lòng nhập email để nhận mã xác nhận');
            header('Location: /webbanhang/Auth/forgotPassword');
            exit;
        }

        // Kiểm tra thời gian timeout (30 phút)
        if (time() - $_SESSION['reset_password_time'] > 1800) {
            error_log("resetPassword - Timeout exceeded");
            unset($_SESSION['reset_password_email']);
            unset($_SESSION['reset_password_time']);
            SessionHelper::setFlash('error', 'Mã xác nhận đã hết hạn. Vui lòng yêu cầu mã mới.');
            header('Location: /webbanhang/Auth/forgotPassword');
            exit;
        }

        error_log("resetPassword - Session valid, displaying form");
        // Hiển thị form nhập mã và mật khẩu mới
        include 'app/views/auth/reset-password.php';
    }    public function updatePassword()
    {
        if (!isset($_POST['token']) || !isset($_POST['password']) || !isset($_POST['password_confirm']) || !isset($_SESSION['reset_password_email'])) {
            error_log("Update Password - Missing required fields");
            SessionHelper::setFlash('error', 'Dữ liệu không hợp lệ');
            header('Location: /webbanhang/Auth/forgotPassword');
            return;
        }
        
        $email = $_SESSION['reset_password_email'];
        $token = trim($_POST['token']);
        error_log("Update Password - Verifying code: " . $token . " for email: " . $email);

        if ($_POST['password'] !== $_POST['password_confirm']) {
            SessionHelper::setFlash('error', 'Mật khẩu xác nhận không khớp');
            header('Location: /webbanhang/Auth/resetPassword?token=' . $_POST['token']);
            return;
        }

        $tokenData = $this->userModel->verifyPasswordResetToken($_POST['token']);
        
        if (!$tokenData) {
            SessionHelper::setFlash('error', 'Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn');
            header('Location: /webbanhang/Auth/forgotPassword');
            return;
        }        if ($this->userModel->resetPassword($tokenData['email'], $_POST['password'])) {
            SessionHelper::setFlash('success', 'Mật khẩu đã được đặt lại thành công');
            // Xóa email khỏi session sau khi đặt lại mật khẩu thành công
            unset($_SESSION['reset_password_email']);
            header('Location: /webbanhang/Auth/login');
        } else {
            SessionHelper::setFlash('error', 'Không thể đặt lại mật khẩu. Vui lòng thử lại');
            header('Location: /webbanhang/Auth/resetPassword');
        }
    }
}
?>
