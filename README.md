# Categoies

﻿# Categories

# Hướng dẫn sử dụng Categories

Categories là một ứng dụng PHP sử dụng cơ sở dữ liệu PostgreSQL để quản lý thông tin danh mục. Dưới đây là hướng dẫn chi tiết để cài đặt và sử dụng chương trình Categories trên môi trường XAMPP.

## Yêu cầu

- XAMPP đã được cài đặt trên máy tính của bạn. Bạn có thể tải và cài đặt XAMPP từ trang chủ của Apache Friends: https://www.apachefriends.org/

## Cài đặt

Bước 1: Sao chép mã nguồn

1. Tải xuống mã nguồn của chương trình Categories.
2. Giải nén tệp tin mã nguồn vào thư mục "htdocs" trong thư mục cài đặt XAMPP. Thư mục "htdocs" thường nằm trong đường dẫn: "C:\xampp\htdocs".

Bước 2: Cấu hình cơ sở dữ liệu PostgreSQL

1. Mở trình duyệt và truy cập vào công cụ quản lý cơ sở dữ liệu PostgreSQL, chẳng hạn như PgAdmin.
2. Tạo một cơ sở dữ liệu mới với tên "categories".
3. Trong cơ sở dữ liệu "categories", tạo một bảng mới với cấu trúc sau:

```sql
CREATE TABLE categories (
    id serial PRIMARY KEY,
    name varchar(50) NOT NULL,
    parent_id integer,
    CONSTRAINT check_parent_not_self CHECK (parent_id <> id)
);
```

## Sử dụng

1. Khởi động XAMPP và đảm bảo rằng Apache và PostgreSQL đang hoạt động.
2. Mở trình duyệt và truy cập vào địa chỉ: http://localhost/categories
3. Trang chủ của chương trình Categories sẽ xuất hiện. Bạn có thể sử dụng các chức năng sau:

   - Thêm danh mục mới: Nhập tên danh mục và chọn danh mục cha (nếu có) để thêm một danh mục mới vào cơ sở dữ liệu.
   - Chỉnh sửa danh mục: Nhấp vào nút "Chỉnh sửa" bên cạnh một danh mục để chỉnh sửa thông tin của danh mục đó.
   - Xoá danh mục: Nhấp vào nút "Xoá" bên cạnh một danh mục để xoá danh mục đó và tất cả các danh mục con.
   - Sao chép thông tin danh mục: Nhấp vào nút "Sao chép" bên cạnh một danh mục để tạo một bản sao của danh mục đó.
   - Hiển thị thông tin chi tiết: Nhấp vào tên danh mục để xem thông tin chi tiết về danh mục đó.
   - Tìm kiếm: Sử dụng hộp tìm kiếm để tìm kiếm danh mục theo tên.

4. Sử dụng các chức năng trên để quản lý và tương tác với danh mục trong chương trình Categories.

## Lưu ý

- Để kết nối chương trình Categories với cơ sở dữ liệu PostgreSQL, hãy chỉnh sửa thông tin kết nối PostgreSQL trong tệp tin "category.php" trong thư mục app/model mã nguồn của chương trình Categories.

Đó là hướng dẫn sử dụng chương trình Categories trên môi trường XAMPP với PostgreSQL. Nếu bạn có bất kỳ câu hỏi hoặc gặp vấn đề trong quá trình sử dụng, hãy liên hệ với người phát triển để được hỗ trợ.
