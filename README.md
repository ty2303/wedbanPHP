# 🛒 TITI Shop - Hệ thống E-commerce PHP

Hệ thống bán hàng online hoàn chỉnh với quản lý vai trò và duyệt tài khoản.

## ✨ Tính năng chính

### 🔐 Hệ thống người dùng
- **3 vai trò:** Admin, Staff, Customer
- **Đăng ký tự động:** Customer được duyệt ngay, Staff cần Admin duyệt
- **Quản lý tài khoản:** Profile, đổi mật khẩu, upload avatar

### 🛍️ Quản lý sản phẩm
- **CRUD sản phẩm:** Thêm, sửa, xóa sản phẩm
- **Quản lý danh mục:** Phân loại sản phẩm
- **Upload hình ảnh:** Tự động resize và tối ưu

### 🛒 Hệ thống mua hàng
- **Giỏ hàng:** Thêm/xóa sản phẩm, cập nhật số lượng
- **Đặt hàng:** Form thông tin, xác nhận đơn hàng
- **Quản lý đơn hàng:** Theo dõi trạng thái, lịch sử mua hàng

### 📊 Báo cáo & Thống kê
- **Doanh thu:** Theo ngày, tháng, năm
- **Sản phẩm bán chạy:** Top sản phẩm
- **Dashboard:** Tổng quan hệ thống

### 🎨 Giao diện
- **Responsive Design:** Bootstrap 5
- **Purple Theme:** Thiết kế hiện đại
- **Icons:** Bootstrap Icons
- **UX/UI:** Thân thiện người dùng

## 🚀 Cài đặt nhanh

### Phương pháp 1: Setup tự động (Khuyến nghị)

1. **Download/Clone project:**
   ```bash
   git clone [repo-url] webbanhang
   cd webbanhang
   ```

2. **Copy vào web server:**
   - XAMPP: `C:\xampp\htdocs\webbanhang`
   - LARAGON: `C:\laragon\www\webbanhang`
   - WAMP: `C:\wamp64\www\webbanhang`

3. **Chạy setup tự động:**
   ```
   http://localhost/webbanhang/setup_installer.php
   ```

4. **Nhập thông tin database:**
   - Host: `localhost`
   - Database: `webbanhang` 
   - User: `root`
   - Password: (để trống với XAMPP/LARAGON)

5. **Hoàn thành!** Truy cập: `http://localhost/webbanhang`

### Phương pháp 2: Setup thủ công

1. **Tạo database:**
   ```sql
   CREATE DATABASE webbanhang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Import database:**
   ```bash
   mysql -u root -p webbanhang < database/complete_setup.sql
   ```

3. **Cấu hình database:** Sửa `app/config/database.php`

4. **Tạo thư mục uploads:**
   ```bash
   mkdir public/uploads
   chmod 755 public/uploads
   ```

## 👤 Tài khoản mặc định

### Admin
- **Username:** `admin`
- **Password:** `admin123`
- **Quyền:** Toàn quyền hệ thống

### Demo Users
Sau khi cài đặt, bạn có thể đăng ký tài khoản mới:
- **Customer:** Được duyệt tự động
- **Staff:** Cần Admin duyệt

## 📁 Cấu trúc project

```
webbanhang/
├── 📁 app/
│   ├── 📁 config/         # Cấu hình database
│   ├── 📁 controllers/    # Controllers (MVC)
│   ├── 📁 models/         # Models (MVC)
│   ├── 📁 views/          # Views (MVC)
│   ├── 📁 helpers/        # Helper classes
│   └── 📁 middleware/     # Authentication middleware
├── 📁 database/           # SQL scripts
├── 📁 public/             # Assets, uploads
├── 📄 setup_installer.php # Setup tự động
├── 📄 index.php          # Entry point
└── 📄 README.md          # Hướng dẫn này
```

## 🔧 Cấu hình

### Database Config
File: `app/config/database.php`
```php
private $host = 'localhost';
private $db_name = 'webbanhang';
private $username = 'root';
private $password = '';
```

### Upload Config
- **Thư mục:** `public/uploads/`
- **Định dạng:** JPG, JPEG, PNG, GIF
- **Kích thước:** Tối đa 10MB

## 🎯 Hướng dẫn sử dụng

### Cho Admin
1. **Đăng nhập:** `admin` / `admin123`
2. **Quản lý người dùng:** Duyệt Staff, thay đổi vai trò
3. **Quản lý sản phẩm:** Thêm/sửa/xóa sản phẩm
4. **Xem báo cáo:** Doanh thu, thống kê

### Cho Staff  
1. **Đăng ký:** Chọn "Nhân viên" → Chờ Admin duyệt
2. **Sau khi được duyệt:** Quản lý sản phẩm, đơn hàng
3. **Xem báo cáo:** Doanh thu, thống kê

### Cho Customer
1. **Đăng ký:** Chọn "Khách hàng" → Tự động được duyệt
2. **Mua sắm:** Thêm vào giỏ hàng → Đặt hàng
3. **Theo dõi:** Lịch sử đơn hàng

## 🔒 Phân quyền

| Tính năng | Admin | Staff | Customer |
|-----------|-------|-------|----------|
| Quản lý người dùng | ✅ | ❌ | ❌ |
| Duyệt Staff | ✅ | ❌ | ❌ |
| Quản lý sản phẩm | ✅ | ✅ | ❌ |
| Quản lý danh mục | ✅ | ✅ | ❌ |
| Xem báo cáo | ✅ | ✅ | ❌ |
| Mua hàng | ✅ | ✅ | ✅ |
| Xem đơn hàng | Tất cả | Tất cả | Của mình |

## 🗄️ Database Schema

### Bảng chính
- **users:** Người dùng (role, status)
- **products:** Sản phẩm
- **categories:** Danh mục
- **orders:** Đơn hàng
- **order_items:** Chi tiết đơn hàng

### Dữ liệu mẫu
- **5 danh mục:** Điện thoại, Laptop, Tablet, Phụ kiện, Đồng hồ
- **20+ sản phẩm:** Điện thoại iPhone, Samsung, Xiaomi...
- **Permissions:** Hệ thống phân quyền chi tiết

## 🛠️ Troubleshooting

### Lỗi thường gặp

1. **"Database connection failed"**
   - Kiểm tra thông tin database trong `app/config/database.php`
   - Đảm bảo MySQL đang chạy

2. **"Permission denied" uploads**
   ```bash
   chmod 755 public/uploads/
   ```

3. **Lỗi 404 khi truy cập trang**
   - Kiểm tra URL Rewrite (mod_rewrite)
   - Đảm bảo `.htaccess` tồn tại

4. **Không upload được hình ảnh**
   - Kiểm tra quyền thư mục `public/uploads/`
   - Kiểm tra `php.ini`: `upload_max_filesize`, `post_max_size`

### Reset hệ thống
Chạy lại setup installer: `http://localhost/webbanhang/setup_installer.php`

## 📞 Hỗ trợ

### Thông tin liên hệ
- **Developer:** TITI Team
- **Email:** support@titishop.com
- **Website:** https://titishop.com

### Yêu cầu hệ thống
- **PHP:** 7.4+
- **MySQL:** 5.7+
- **Apache:** mod_rewrite enabled
- **Extensions:** PDO, GD, mbstring

## 📄 License

MIT License - Được phép sử dụng cho mục đích thương mại và phi thương mại.

---

**🚀 Chúc bạn sử dụng hệ thống thành công!**

*Nếu gặp vấn đề, vui lòng tạo issue hoặc liên hệ support.*
- Đăng xuất
- Quản lý hồ sơ cá nhân
