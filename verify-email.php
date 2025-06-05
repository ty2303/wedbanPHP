<?php
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/UserModel.php';

$db = new PDO(
    "mysql:host={$config['db']['host']};dbname={$config['db']['database']}", 
    $config['db']['username'], 
    $config['db']['password']
);

$userModel = new UserModel($db);

$code = $_GET['code'] ?? '';

if (empty($code)) {
    die("Mã xác thực không hợp lệ!");
}

if ($userModel->verifyEmail($code)) {
    echo "Email của bạn đã được xác thực thành công! Bạn có thể đóng trang này và đăng nhập.";
} else {
    echo "Mã xác thực không hợp lệ hoặc đã hết hạn!";
}
