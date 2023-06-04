# Categoies
## Cài đặt

Bước 1: Sao chép mã nguồn

1. Mở terminal hoặc command prompt trên máy tính của bạn.
2. Chạy lệnh sau để sao chép mã nguồn từ repository GitHub:

```shell
git clone https://github.com/SteveTomorrow/Lession2.git
```

3. Sau khi hoàn tất, thư mục "Lession2" chứa mã nguồn của chương trình Categories sẽ được tạo.

Bước 2: Cấu hình cơ sở dữ liệu PostgreSQL

1. Mở trình duyệt và truy cập vào công cụ quản lý cơ sở dữ liệu PostgreSQL, chẳng hạn như PgAdmin.
2. Tạo một cơ sở dữ liệu mới với tên "categories".
3. Thực thi mã SQL sau để tạo bảng "categories" trong cơ sở dữ liệu "categories":

```sql
CREATE TABLE categories (
    id serial PRIMARY KEY,
    name varchar(50) NOT NULL,
    parent_id integer,
    CONSTRAINT check_parent_not_self CHECK (parent_id <> id)
);
```

4. Thực thi mã SQL sau để tạo hàm kiểm tra xung đột danh mục cha-con:

```sql
CREATE FUNCTION public.check_parent_category() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE
    is_loop BOOLEAN;
BEGIN
    IF NEW.parent_id = NEW.id THEN
        RAISE EXCEPTION 'Invalid parent category selected';
    END IF;

    -- Kiểm tra xem danh mục cha đã là con của danh mục hiện tại chưa (xung đột vòng lặp vô tận)
    WITH RECURSIVE category_path AS (
        SELECT id, parent_id
        FROM categories
        WHERE id = NEW.parent_id
        UNION ALL
        SELECT c.id, c.parent_id
        FROM categories c
        JOIN category_path cp ON c.id = cp.parent_id
    )
    SELECT EXISTS (
        SELECT 1
        FROM category_path
        WHERE id = NEW.id
    ) INTO STRICT is_loop;

    IF is_loop THEN
        RAISE EXCEPTION 'Category loop detected';
    END IF;

    RETURN NEW;
END;
$$;

ALTER FUNCTION public.check_parent_category() OWNER TO postgres;

-- Thiết lập các giá trị mặc định cho bảng
ALTER TABLE public.categories
    SET (default_tablespace = '', default_table_access_method = heap);

-- Thiết lập trigger trước khi chèn vào bảng
CREATE TRIGGER check_parent_category_trigger
    BEFORE INSERT OR UPDATE ON public.categories
    FOR EACH ROW EXECUTE FUNCTION public.check_parent_category();
```

5. Sao chép đoạn dữ liệu dưới đây và thực thi để thêm dữ liệu mẫu vào bảng "categories":

```sql
INSERT INTO public.categories (id, name, parent_id)
VALUES 
    (1, 'Danh mục 1', NULL),
    (2, 'Danh mục 2', 1),
    (3, 'Danh mục 3', 1),
    (4, 'Danh mục 4', 2),
    (5, 'Danh mục 5', 3),
    (6, 'Danh mục 6', 2),
    (7,'Danh mục 7', NULL),
    (8, 'Danh mục 8', 7),
    (9, 'Danh mục 9', 7),
    (10, 'Danh mục 10', 8);
```
## Sử dụng

1. Khởi động XAMPP và đảm bảo rằng Apache và PostgreSQL đang hoạt động.
2. Sao chép thư mục "Lession2" vào thư mục "htdocs" trong thư mục cài đặt XAMPP. Thư mục "htdocs" thường nằm trong đường dẫn: "C:\xampp\htdocs".
3. Mở trình duyệt và truy cập vào địa chỉ: [http://localhost/Lession2](http://localhost/Lession2)
4. Trang chủ của chương trình Categories sẽ xuất hiện. Bạn có thể sử dụng các chức năng sau:

   - Thêm danh mục mới: Nhập tên danh mục và chọn danh mục cha (nếu có) để thêm một danh mục mới vào cơ sở dữ liệu.
   - Chỉnh sửa danh mục: Nhấp vào nút "Chỉnh sửa" bên cạnh một danh mục để chỉnh sửa thông tin của danh mục đó.
   - Xoá danh mục: Nhấp vào nút "Xoá" bên cạnh một danh mục để xoá danh mục đó và tất cả các danh mục con.
   - Sao chép thông tin danh mục: Nhấp vào nút "Sao chép" bên cạnh một danh mục để tạo một bản sao của danh mục đó.
   - Hiển thị thông tin chi tiết: Nhấp vào tên danh mục để xem thông tin chi tiết về danh mục đó.
   - Tìm kiếm: Sử dụng hộp tìm kiếm để tìm kiếm danh mục theo tên.

5. Sử dụng các chức năng trên để quản lý và tương tác với danh mục trong chương trình Categories.

## Lưu ý

- Để kết nối chương trình Categories với cơ sở dữ liệu PostgreSQL, hãy chỉnh sửa thông tin kết nối PostgreSQL trong tệp tin "category.php" trong thư mục "app/model" của mã nguồn của chương trình Categories.

Đó là hướng dẫn sử dụng chương trình Categories trên môi trường XAMPP với PostgreSQL sau khi clone mã nguồn từ GitHub. Nếu bạn có bất kỳ câu hỏi hoặc gặp vấn đề trong quá trình sử dụng, hãy liên hệ với người phát triển để được hỗ trợ.
