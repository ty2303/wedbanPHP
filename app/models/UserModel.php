<?php
class UserModel
{
    private $conn;
    private $table = 'users';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $email, $password, $avatar = null, $age = null)
    {
        // Validate input
        $errors = [];
        if (empty($username)) {
            $errors[] = "Tên đăng nhập không được để trống";
        }
        if (empty($email)) {
            $errors[] = "Email không được để trống";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email không hợp lệ";
        }
        if (empty($password)) {
            $errors[] = "Mật khẩu không được để trống";
        } elseif (strlen($password) < 6) {
            $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
        }

        if (!empty($errors)) {
            return $errors;
        }

        // Check if username already exists
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            return ["Tên đăng nhập đã tồn tại"];
        }

        // Check if email already exists
        $stmt = $this->conn->prepare("SELECT id FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            return ["Email đã tồn tại"];
        }

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user
        $query = "INSERT INTO {$this->table} (username, email, password, avatar, age) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$username, $email, $hashed_password, $avatar, $age])) {
            return true;
        }
        
        return ["Đã xảy ra lỗi khi đăng ký"];
    }

    public function login($username, $password)
    {
        if (empty($username) || empty($password)) {
            return ["Tên đăng nhập và mật khẩu không được để trống"];
        }

        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $user['password'])) {
                // Remove password from array
                unset($user['password']);
                return $user;
            }
        }
        
        return ["Tên đăng nhập hoặc mật khẩu không đúng"];
    }

    public function getUserById($id)
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, avatar, age, created_at FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $email, $avatar = null, $age = null)
    {
        $query = "UPDATE {$this->table} SET email = ?, avatar = ?, age = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$email, $avatar, $age, $id]);
    }

    public function changePassword($id, $current_password, $new_password)
    {
        // Validate input
        if (empty($current_password) || empty($new_password)) {
            return ["Mật khẩu hiện tại và mật khẩu mới không được để trống"];
        }

        if (strlen($new_password) < 6) {
            return ["Mật khẩu mới phải có ít nhất 6 ký tự"];
        }

        // Get current user
        $stmt = $this->conn->prepare("SELECT password FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!password_verify($current_password, $user['password'])) {
            return ["Mật khẩu hiện tại không đúng"];
        }

        // Hash new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $query = "UPDATE {$this->table} SET password = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$hashed_password, $id])) {
            return true;
        }
        
        return ["Đã xảy ra lỗi khi đổi mật khẩu"];
    }
}
?>