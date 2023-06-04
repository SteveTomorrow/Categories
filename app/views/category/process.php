<?php

// Số danh mục trên mỗi trang
$categoriesPerPage = 11;

// Trang hiện tại
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$action = isset($_GET['action']) ? $_GET['action'] : '';
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';

// Tính toán vị trí bắt đầu và kết thúc của danh mục trên trang hiện tại
$startIndex = ($page - 1) * $categoriesPerPage;
$endIndex = $startIndex + $categoriesPerPage - 1;

// Số lượng danh mục trong danh sách
$categoryCount = count($categories);

// Tổng số trang
$totalPages = ceil($categoryCount / $categoriesPerPage);

// Hàm xây dựng hiển thị danh mục
function buildCategoryTreeWithPagination($categories, $startIndex, $endIndex, $parent = 0, $level = 0, $searchKeyword = '')
{
    $count = 1; // Biến đếm số thứ tự
    $isSearching = ($searchKeyword !== ''); // Xác định trạng thái tìm kiếm
    if ($isSearching) {
        foreach ($categories as $category) {
            $isMatchingKeyword = ($category['name'] === $searchKeyword);
            if ($isMatchingKeyword) {
                buildCategory($startIndex, $endIndex, $categories, $category, $level + 1, $count, true, $searchKeyword);
            }
        }
    } else {
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent) {
                buildCategory($startIndex, $endIndex, $categories, $category, $level + 1, $count, false);
            }
        }
    }
}

#Hàm hiển thị
function buildCategory($startIndex, $endIndex, $categories, $category, $level = 0, &$count, $isSearching, $searchKeyword = '')
{
    if ($count >= $startIndex && $count <= $endIndex) {
        
        $tab = str_repeat('&nbsp;', 6 * $level);

        echo '<tr class="category-node">';
        echo '<td>' . $count . '</td>';
        echo '<td>' .$tab. ($level > 1 ? '└─── ' : '') . $category['name'] . ($category['parent_id'] != null ? '-' : '') . $category['parent_id'] . '</td>';
        echo '<td>';
        echo '<button class="btn btn-edit" data-id="' . $category['id'] . '" data-name="' . $category['name'] . '" data-parent="' . $category['parent_id'] . '"><i class="fa fa-pencil"></i></button>';
        echo '<button class="btn btn-copy" data-id="' . $category['id'] . '" data-name="' . $category['name'] . '" data-parent="' . $category['parent_id']  . '"><i class="fa fa-copy"></i></button>';
        echo '<button class="btn btn-delete" data-category-id="' . $category['id'] . '"><i class="fa fa-trash"></i></button>';
        echo '</td>';
        echo '</tr>';
    }

    $count++;

    foreach ($categories as $childCategory) {
        if ($childCategory['parent_id'] == $category['id']) {
            buildCategory($startIndex, $endIndex, $categories, $childCategory, $level + 1, $count, $isSearching, $searchKeyword);
        }
    }
}

// Danh sách danh mục sản phẩm
echo '<table class="table">';echo '<thead>';
echo '<tr>';
echo '<th>#</th>';
echo '<th>Category Name</th>';
echo '<th>Operations</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';

function countDescendants($categories, $category, $searchKeyword) {
    $count = 0;

    foreach ($categories as $childCategory) {
        if ($childCategory['parent_id'] == $category['id']) {
            $count++; // Tăng biến đếm cho mỗi con cháu tìm thấy
            $count += countDescendants($categories, $childCategory, $searchKeyword);
        }
    }

    return $count;
}

if ($action === 'search' && $keyword !== '') {
    buildCategoryTreeWithPagination($categories, $startIndex, $endIndex, 0, 0, $keyword);
    $categoryCount = 1; // Khởi tạo biến categoryCount
    foreach ($categories as $category) {
        $isMatchingKeyword = ($category['name'] === $keyword);
        if ($isMatchingKeyword) {
            $categoryCount += countDescendants($categories, $category, $keyword);
        }
    }
    $totalPages = ceil($categoryCount / $categoriesPerPage);
    echo '<div class="result-count">Found ' . $categoryCount . ' categories</div>';
    // Sử dụng giá trị categoryCount và totalPages cho xử lý tiếp theo
} else {
    buildCategoryTreeWithPagination($categories, $startIndex, $endIndex);
}

echo '</tbody>';
echo '</table>';

// Hiển thị phân trang
echo '<nav aria-label="Page navigation">';
echo '<ul class="pagination justify-content-center">';

$queryString = '';
if (!empty($action)) {
    $queryParams = $_GET;
    unset($queryParams['page']);
    $queryString = http_build_query($queryParams);
}

// Nút Previous
if ($page > 1) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '&' . $queryString . '">Previous</a></li>';
} else {
    echo '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
}

// Các nút trang
for ($i = 1; $i <= $totalPages; $i++) {
    if ($i == $page) {
        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
    } else {
        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '&' . $queryString . '">' . $i . '</a></li>';
    }
}

// Nút Next
if ($page < $totalPages) {
    echo '<li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '&' . $queryString . '">Next</a></li>';
} else {
    echo '<li class="page-item disabled"><span class="page-link">Next</span></li>';
}

echo '</ul>';
echo '</nav>';

?>
