<?php

require_once 'app/models/Category.php';

interface ControllerInterface
{
    public function index();
    public function create();
    public function edit($id);
    public function delete($id);
    public function detail($id);
    public function search($keyword);
}

abstract class BaseController implements ControllerInterface
{
    /**
     * Phương thức loadView dùng để tải và hiển thị view
     *
     * @param string $view Tên của view muốn tải và hiển thị
     * @param array $data (Tùy chọn) Dữ liệu truyền vào view dưới dạng một mảng các biến và giá trị tương ứng
     */
    protected function loadView($view, $data = [])
    {
        extract($data);
        require_once "app/views/$view.php";
    }
}

class CategoriesController extends BaseController
{
    /**
     * Hiển thị trang danh sách danh mục
     */
    public function index()
    {
        $categories = Category::getAll();
        // Sắp xếp các danh mục theo id
        usort($categories, function ($a, $b) {
            return $a['id'] - $b['id'];
        });
        $this->loadView('category/index', ['categories' => $categories]);
    }

    /**
     * Xử lý tạo mới danh mục
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category = new Category();
            $category->setName($_POST['name']);
            // Kiểm tra giá trị của trường "parent_id"
            if (!is_numeric($_POST['parent_id'])) {
                $category->setParentId(null); // Gán giá trị null nếu không hợp lệ
            } else {
                $category->setParentId((int)$_POST['parent_id']); // Chuyển đổi thành số nguyên nếu hợp lệ
            }

            $category->save();

            // Chuyển hướng đến trang index để hiển thị danh sách danh mục mới
            header("Location: /categories?success=1");
            exit;
        }

        // Trả về kết quả cho pop-up
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    /**
     * Xử lý chỉnh sửa danh mục
     */
    public function edit($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xử lý khi submit form
            $category = Category::getById($id);
            $category->setId($id); // Gán giá trị id cho đối tượng Category
            $category->setName($_POST['name']);
            $category->setParentId($_POST['parent_id']);

            try {
                $category->update();
                // Gán thông báo thành công vào biến query trong URL
                header("Location: /categories?success=1");
                exit;
            } catch (Exception $e) {
                // Gán thông báo lỗi vào biến query trong URL
                header("Location: /categories?error=1&message=" . urlencode("Sai Danh Mục Cha"));
                exit;
            }
        }

        // Trả về kết quả cho pop-up
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
        exit;
    }

    /**
     * Xóa danh mục và các danh mục con
     */
    public function delete($id)
    {
        $category = Category::getById($id);

        if ($category) {
            // Xóa danh mục
            $category->delete();

            // Chuyển hướng đến trang danh sách danh mục
            header("Location: /categories?success=1");
            exit;
        } else {
            // Hiển thị thông báo lỗi hoặc xử lý khác nếu danh mục không tồn tại
            // ...
        }
    }

    /**
     * Hiển thị chi tiết danh mục
     */
    public function detail($id)
    {
        $category = Category::getById($id);

        // Trả về kết quả cho pop-up
        echo json_encode(['status' => 'success', 'category' => $category]);
        exit;
    }

    /**
     * Tìm kiếm danh mục dựa trên từ khóa
     */
    public function search($keyword)
    {
        $categories = Category::getAll();
        // Sắp xếp các danh mục theo id
        usort($categories, function ($a, $b) {
            return $a['id'] - $b['id'];
        });

        // Hiển thị trang danh sách danh mục với kết quả tìm kiếm
        $this->loadView('category/index', ['categories' => $categories, 'searchKeyword' => $keyword]);
    }

    public function copy($id)
    {
        // Kiểm tra xem danh mục có tồn tại không
        $category = Category::getById($id);
        var_dump($id); // Kiểm tra giá trị của $id
        if (!$category) {
            // Trả về kết quả lỗi
            echo json_encode(['status' => 'error', 'message' => 'Danh mục không tồn tại']);
            exit;
        }

        // Trả về kết quả thành công chỉ với thông tin danh mục hiện tại
        echo json_encode(['status' => 'success', 'category' => $category]);
        exit;
    }
}
