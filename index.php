<?php
    // Đây là file index.php trong thư mục gốc (categories/index.php)
    // Nó đại diện cho điểm vào của phần danh mục trong ứng dụng của bạn

    // Bao gồm các file cần thiết
    require_once 'vendor/autoload.php';
    require_once 'app/controllers/CategoryController.php';

    // Tạo một phiên bản của CategoriesController
    $controller = new CategoriesController();

    // Xác định hành động thực hiện dựa trên tham số truy vấn "action"
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';

    // Gọi phương thức tương ứng trên controller
    switch ($action) {
        case 'index':
            $controller->index();
            break;
        case 'create':
            $controller->create();
            break;
        case 'edit':
            // Lấy ID của danh mục từ tham số truy vấn
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            var_dump($id);
            $controller->edit($id);
            break;
        case 'delete':
            // Lấy ID của danh mục từ tham số truy vấn
            $id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
            $controller->delete($id);
            break;
        case 'detail':
            // Lấy ID của danh mục từ tham số truy vấn
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $controller->detail($id);
            break;      
        case 'search':
            // Lấy từ khóa từ tham số truy vấn
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
            $controller->search($keyword);
            break;
        case 'copy':
            // Lấy ID của danh mục từ tham số truy vấn
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            $controller->copy($id);
            break;
            
        
        default:
            // Hành động không hợp lệ, chuyển hướng đến trang index
            header('Location: index.php');
            exit;
    }
?>
