<?php
require_once('app/helpers/SessionHelper.php');

class AuthMiddleware
{
    /**
     * Kiểm tra nếu người dùng đã đăng nhập
     * 
     * @param string $redirectTo Đường dẫn chuyển hướng nếu chưa đăng nhập
     * @return bool True nếu đã đăng nhập, nếu không sẽ chuyển hướng
     */
    public static function requireLogin($redirectTo = '/webbanhang/Auth/login')
    {
        if (!SessionHelper::isLoggedIn()) {
            SessionHelper::setFlash('error', 'Bạn cần đăng nhập để truy cập trang này!');
            header("Location: $redirectTo");
            exit;
        }
        return true;
    }

    /**
     * Kiểm tra nếu người dùng đã đăng nhập, nếu đã đăng nhập thì chuyển hướng
     * 
     * @param string $redirectTo Đường dẫn chuyển hướng nếu đã đăng nhập
     * @return bool True nếu chưa đăng nhập, nếu không sẽ chuyển hướng
     */
    public static function requireGuest($redirectTo = '/webbanhang/')
    {
        if (SessionHelper::isLoggedIn()) {
            header("Location: $redirectTo");
            exit;
        }
        return true;
    }
}
?>
