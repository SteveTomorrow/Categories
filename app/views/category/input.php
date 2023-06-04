<?php
// Kiểm tra và hiển thị thông báo thành công
if (isset($_GET['success'])) {
    echo '<div id="successMessage" class="alert alert-success">Category updated successfully</div>';
}

// Kiểm tra và hiển thị thông báo lỗi
if (isset($_GET['error'])) {
    $errorMessage = isset($_GET['message']) ? urldecode($_GET['message']) : 'An error occurred';
    echo '<div id="errorMessage" class="alert alert-danger">' . $errorMessage . '</div>';
}
?>
