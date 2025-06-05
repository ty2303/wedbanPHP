# 🛒 TITI Shop - Hệ thống E-commerce PHP (Version 2.0)

Hệ thống bán hàng online hoàn chỉnh với **quản lý vai trò phân quyền UI**, **hệ thống voucher**, **quản lý đơn hàng nâng cao** và **giao diện gradient hiện đại**.

## ✨ Tính năng chính

### 🔐 Hệ thống người dùng & Phân quyền UI
- **3 vai trò:** Admin, Staff, Customer
- **Phân quyền hiển thị nút:**
  - **Admin:** Chỉ hiển thị nút "Sửa" và "Xóa" trên sản phẩm
  - **Customer:** Chỉ hiển thị nút "Thêm vào giỏ hàng"
  - **Staff:** Có quyền quản lý sản phẩm và đơn hàng
- **Middleware bảo mật:** Admin không thể truy cập Cart URLs
- **Đăng ký tự động:** Customer được duyệt ngay, Staff cần Admin duyệt
- **Quản lý tài khoản:** Profile, đổi mật khẩu, upload avatar

### 🛍️ Quản lý sản phẩm (UI nâng cấp)
- **CRUD sản phẩm:** Thêm, sửa, xóa sản phẩm
- **Enhanced Product Cards:** Gradient design, hover effects, animations
- **Responsive design:** Tối ưu cho desktop và mobile
- **Category badges:** Gradient styling với colors
- **Interactive buttons:** Loading states và success feedback
- **Quản lý danh mục:** Phân loại sản phẩm
- **Upload hình ảnh:** Tự động resize và tối ưu

### 🛒 Hệ thống mua hàng & Voucher
- **Giỏ hàng nâng cao:** AJAX loading states, UX mượt mà
- **Hệ thống voucher hoàn chỉnh:**
  - `WELCOME10` - Giảm 10% (tối đa 50k) cho đơn từ 100k
  - `FREESHIP` - Giảm 30k phí ship cho đơn từ 200k  
  - `SUMMER20` - Giảm 20% (tối đa 100k) cho đơn từ 500k
  - `NEWUSER50` - Giảm 50k cho khách hàng mới, đơn từ 150k
  - `VIP15` - Giảm 15% (tối đa 200k) cho thành viên VIP
- **Enhanced checkout:** Gradient price display, interactive controls
- **Theo dõi lịch sử:** Chi tiết sử dụng voucher

### 📦 Quản lý đơn hàng nâng cao
- **8 trạng thái đơn hàng:** pending, confirmed, processing, packed, shipped, delivered, cancelled, returned
- **Theo dõi lịch sử:** Chi tiết từng thay đổi trạng thái với timestamp
- **Mã vận đơn:** Tracking number cho đơn hàng
- **Ghi chú Admin:** Notes chi tiết cho từng đơn hàng
- **Dự kiến giao hàng:** Estimated delivery date
- **Quản lý đơn hàng:** Theo dõi trạng thái, lịch sử mua hàng

### 📊 Báo cáo & Thống kê
- **Doanh thu:** Theo ngày, tháng, năm
- **Sản phẩm bán chạy:** Top sản phẩm
- **Thống kê voucher:** Sử dụng và hiệu quả
- **Dashboard:** Tổng quan hệ thống với metrics

### 🎨 Giao diện (Enhanced UI/UX)
- **Modern Gradient Design:** Purple-blue gradient themes
- **Responsive Design:** Bootstrap 5 với breakpoints tối ưu
- **Hover Animations:** Smooth transitions và scale effects
- **Shadow Effects:** Depth và visual hierarchy
- **Interactive Elements:** Loading states, success feedback
- **Icons:** Bootstrap Icons với color schemes
- **Mobile-first:** Touch-friendly interface

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

4. **Nhập thông tin Database:**
   - Host: `localhost`
   - Database: `webbanhang` (sẽ được tạo tự động)
   - Username: `root`
   - Password: (để trống nếu XAMPP/LARAGON)

5. **Hoàn thành!**
   - Hệ thống tự động tạo database từ `master_setup.sql`
   - Import 23 sản phẩm mẫu + 5 vouchers
   - Tạo 7 tài khoản test (3 Admin + 2 Staff + 2 Customer)
   - Tạo 3 đơn hàng mẫu với trạng thái khác nhau
   - Truy cập: `http://localhost/webbanhang`

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

### Admin Accounts (3)
1. **Username:** `admin` | **Password:** `admin123` | **Email:** `admin@titishop.com`
2. **Username:** `Admin1` | **Password:** `Admin@123` | **Email:** `admin1@titishop.com`  
3. **Username:** `Admin2` | **Password:** `Admin@123` | **Email:** `admin2@titishop.com`

**Quyền Admin:** Toàn quyền hệ thống, không thể truy cập Cart URLs

### Staff Accounts (2)
1. **Username:** `Staff1` | **Password:** `Staff@123` | **Email:** `staff1@titishop.com`
2. **Username:** `Staff2` | **Password:** `Staff@123` | **Email:** `staff2@titishop.com`

**Quyền Staff:** Quản lý sản phẩm, đơn hàng, voucher, xem báo cáo

### Customer Accounts (2)
1. **Username:** `Customer1` | **Password:** `Customer@123` | **Email:** `customer1@titishop.com`
2. **Username:** `Customer2` | **Password:** `Customer@123` | **Email:** `customer2@titishop.com`

**Quyền Customer:** Mua hàng, quản lý đơn hàng cá nhân

### Demo Users
Sau khi cài đặt, bạn có thể đăng ký tài khoản mới:
- **Customer:** Được duyệt tự động
- **Staff:** Cần Admin duyệt

## 🎟️ Vouchers có sẵn

### Active Vouchers (5)
1. **`WELCOME10`** - Giảm 10% (tối đa 50.000đ) cho đơn từ 100.000đ
2. **`FREESHIP`** - Giảm 30.000đ phí ship cho đơn từ 200.000đ  
3. **`SUMMER20`** - Giảm 20% (tối đa 100.000đ) cho đơn từ 500.000đ
4. **`NEWUSER50`** - Giảm 50.000đ cho khách hàng mới, đơn từ 150.000đ
5. **`VIP15`** - Giảm 15% (tối đa 200.000đ) cho thành viên VIP từ 300.000đ

**Tất cả vouchers đều có hiệu lực từ 01/01/2024 đến 31/12/2024**

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

## 💾 Cấu trúc Database

### Bảng chính
- **`users`** - Người dùng với vai trò (admin, staff, customer)
- **`categories`** - Danh mục sản phẩm
- **`products`** - Sản phẩm với thông tin chi tiết
- **`orders`** - Đơn hàng (với voucher, trạng thái nâng cao)
- **`order_items`** - Chi tiết đơn hàng
- **`vouchers`** - Mã giảm giá với nhiều loại chiết khấu
- **`voucher_usage`** - Lịch sử sử dụng voucher
- **`order_status_history`** - Lịch sử thay đổi trạng thái đơn hàng
- **`permissions`** - Quyền hạn hệ thống
- **`role_permissions`** - Phân quyền theo vai trò

### Dữ liệu mẫu
- **23 sản phẩm** công nghệ (điện thoại, laptop, tablet, phụ kiện, đồng hồ)
- **5 vouchers** với các loại giảm giá khác nhau
- **7 tài khoản** test (3 Admin + 2 Staff + 2 Customer)
- **3 đơn hàng** mẫu với trạng thái khác nhau

## 🔒 Bảo mật & Phân quyền

### Role-based Access Control
- **Middleware protection:** Phân quyền theo controller
- **UI separation:** Nút hiển thị theo vai trò
- **Session management:** Secure session handling
- **Input validation:** XSS và SQL injection protection

### Admin Restrictions
- Admin không thể truy cập Cart URLs (`/Cart/*`)
- Redirect tự động đến trang quản lý phù hợp
- Middleware chặn các actions: add, checkout, placeOrder, update, remove, clear, applyVoucher, removeVoucher

## 📝 Changelog

### Version 2.0 (Current) - June 2025
- ✅ **Enhanced UI/UX:** Gradient design, hover effects, animations
- ✅ **Role-based button visibility:** Admin vs Customer UI separation  
- ✅ **Voucher system:** Complete voucher management with 5 types
- ✅ **Advanced order management:** 8 status levels, tracking, history
- ✅ **Admin Cart restrictions:** Middleware protection for admin access
- ✅ **Master database setup:** Single SQL file for complete installation
- ✅ **Enhanced installer:** Auto setup with detailed feature info
- ✅ **Responsive design:** Mobile-first approach with breakpoints
- ✅ **AJAX cart functionality:** Loading states, success feedback
- ✅ **Sample data:** 23 products + 5 vouchers + test orders

### Version 1.0 - Initial Release
- ✅ Basic MVC structure
- ✅ User management with roles
- ✅ Product & Category management
- ✅ Basic cart & checkout
- ✅ Simple order management
- ✅ Staff approval system
- ✅ Revenue reporting

## 📄 License

MIT License - Được phép sử dụng cho mục đích thương mại và phi thương mại.

---

**🚀 Chúc bạn sử dụng hệ thống thành công!**

*Nếu gặp vấn đề, vui lòng tạo issue hoặc liên hệ support.*
- Đăng xuất
- Quản lý hồ sơ cá nhân
