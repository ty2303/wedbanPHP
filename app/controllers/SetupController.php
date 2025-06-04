<?php
require_once('app/config/database.php');
require_once('app/helpers/SessionHelper.php');
require_once('app/middleware/AuthMiddleware.php');

class SetupController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }
    
    public function index()
    {
        // Chỉ cho phép truy cập nếu chưa thiết lập phân quyền hoặc là admin
        $checkColumnQuery = "SHOW COLUMNS FROM users LIKE 'role'";
        $columnExists = $this->db->query($checkColumnQuery)->rowCount() > 0;
        
        if ($columnExists && !SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error', 'Bạn không có quyền truy cập trang này!');
            header('Location: /webbanhang/');
            exit;
        }
        
        include 'app/views/setup/index.php';
    }

    public function setupRoles()
    {
        // Chỉ cho phép truy cập nếu chưa thiết lập phân quyền hoặc là admin
        $checkColumnQuery = "SHOW COLUMNS FROM users LIKE 'role'";
        $columnExists = $this->db->query($checkColumnQuery)->rowCount() > 0;
        
        if ($columnExists && !SessionHelper::isAdmin()) {
            SessionHelper::setFlash('error', 'Bạn không có quyền truy cập trang này!');
            header('Location: /webbanhang/');
            exit;
        }

        if (!$columnExists) {
            // Thêm cột role vào bảng users
            $addColumnQuery = "ALTER TABLE users ADD COLUMN role ENUM('admin', 'staff', 'customer') NOT NULL DEFAULT 'customer'";
            $this->db->exec($addColumnQuery);
            
            // Cập nhật người dùng đầu tiên làm admin
            $updateFirstUserQuery = "UPDATE users SET role = 'admin' WHERE id = 1";
            $this->db->exec($updateFirstUserQuery);
            
            SessionHelper::setFlash('success', 'Đã thiết lập thành công hệ thống phân quyền!');
        } else {
            SessionHelper::setFlash('info', 'Hệ thống phân quyền đã được thiết lập trước đó!');
        }
        
        header('Location: /webbanhang/');
        exit;
    }

    public function roles()
    {
        include 'app/views/setup/roles.php';
    }
}
