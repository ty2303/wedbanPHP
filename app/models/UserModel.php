<?php
class UserModel
{
    private $conn;
    private $table = 'users';
    
    // Định nghĩa các vai trò
    const ROLE_ADMIN = 'admin';
    const ROLE_STAFF = 'staff';
    const ROLE_CUSTOMER = 'customer';

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function register($username, $email, $password, $avatar = null, $age = null, $role = self::ROLE_CUSTOMER)
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
        $query = "INSERT INTO {$this->table} (username, email, password, avatar, age, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute([$username, $email, $hashed_password, $avatar, $age, $role])) {
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

    // Thêm phương thức kiểm tra vai trò của người dùng
    public function getUserRole($userId)
    {
        $stmt = $this->conn->prepare("SELECT role FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? $result['role'] : null;
    }
    
    // Thêm phương thức thay đổi vai trò cho người dùng
    public function changeUserRole($userId, $newRole)
    {
        if (!in_array($newRole, [self::ROLE_ADMIN, self::ROLE_STAFF, self::ROLE_CUSTOMER])) {
            return false;
        }
        
        $query = "UPDATE {$this->table} SET role = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$newRole, $userId]);
    }
      // Thêm phương thức lấy danh sách người dùng (dành cho Admin)
    public function getAllUsers()
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, status, avatar, age, created_at FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Phương thức đếm số lượng người dùng đang chờ duyệt
    public function getPendingUsersCount()
    {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE status = 'pending'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ? (int)$result['count'] : 0;
    }
      // Phương thức lấy danh sách người dùng đang chờ duyệt
    public function getPendingUsers()
    {
        $stmt = $this->conn->prepare("SELECT id, username, email, role, status, avatar, age, created_at FROM {$this->table} WHERE status = 'pending' ORDER BY created_at ASC");
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Phương thức phê duyệt người dùng
    public function approveUser($userId)
    {
        $query = "UPDATE {$this->table} SET status = 'approved' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$userId]);
    }
    
    // Phương thức từ chối người dùng
    public function rejectUser($userId)
    {
        $query = "UPDATE {$this->table} SET status = 'rejected' WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([$userId]);
    }
}
?>