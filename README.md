# Hướng dẫn cài đặt và chạy ứng dụng

## Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên
- Web server (Apache/Nginx)
- Laragon/XAMPP/WAMP hoặc tương đương

## Các bước cài đặt

### 1. Cài đặt cơ sở dữ liệu
1. Mở phpMyAdmin hoặc công cụ quản lý MySQL của bạn
2. Tạo một database mới tên là `webbanhang`
3. Chạy các script SQL trong thư mục `database`:
   - `setup.sql`: Tạo cấu trúc cơ sở dữ liệu và bảng

### 2. Cấu hình kết nối cơ sở dữ liệu
1. Mở file `app/config/database.php`
2. Cập nhật thông tin kết nối cơ sở dữ liệu (host, username, password, database name)

### 3. Cấp quyền thư mục
Đảm bảo thư mục `public/uploads` có quyền ghi để lưu trữ hình ảnh sản phẩm và avatar người dùng.

### 4. Chạy ứng dụng
1. Mở trình duyệt và truy cập đường dẫn:
   - `http://localhost/webbanhang/` (nếu sử dụng localhost)
   - hoặc theo domain được cấu hình trên máy chủ web của bạn

## Các chức năng chính

### Quản lý sản phẩm
- Xem danh sách sản phẩm
- Thêm, sửa, xóa sản phẩm
- Tìm kiếm sản phẩm

### Quản lý danh mục
- Xem danh sách danh mục
- Thêm, sửa, xóa danh mục

### Quản lý đơn hàng
- Xem danh sách đơn hàng
- Xem chi tiết đơn hàng
- Cập nhật trạng thái đơn hàng

### Báo cáo
- Xem báo cáo doanh thu
- Xem thống kê sản phẩm bán chạy

### Xác thực người dùng
- Đăng ký tài khoản
- Đăng nhập
- Đăng xuất
- Quản lý hồ sơ cá nhân
