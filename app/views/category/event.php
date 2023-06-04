<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
<script>
    // Tìm kiếm danh mục
    $(document).ready(function () {
        // Tìm kiếm danh mục
        $('#search').on('keypress', function (event) {
            if (event.which === 13) { // 13 là mã ASCII của phím Enter
                var keyword = $(this).val().trim();
                if (keyword !== '') {
                    window.location.href = 'index.php?action=search&keyword=' + encodeURIComponent(keyword);
                }
            }
        });
    });

    // Hiển thị chi tiết danh mục khi click vào tên danh mục
    $(document).ready(function() {
        $('.category-node').on('click', function(event) {
            if ($(event.target).is('.btn, .btn i')) {
                return;
            }

            var name = $(this).find('.btn-edit').data('name');
            var parent = $(this).find('.btn-edit').data('parent');

            $('#detail_name').text(name);
            $('#detail_parent').text(parent);

            $('#detailCategoryModal').modal('show');
        });
    });

    $('.btn-edit').on('click', function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var parent = $(this).data('parent');

        // Gán giá trị vào các trường input trong modal
        $('#editCategoryModal').find('#edit_id').val(id);
        $('#editCategoryModal').find('#edit_name').val(name);
                
        // Đặt giá trị selected cho danh mục cha trong dropdown
        var selectParent = $('#editCategoryModal').find('#edit_parent_id');
        selectParent.empty(); // Xóa các option hiện tại trong select

        // Thêm option "Rỗng"
        selectParent.append($('<option>').val('').text('Rỗng'));

        // Lặp qua danh sách danh mục
        $.each(<?php echo json_encode($categories); ?>, function(index, category) {
            // Kiểm tra nếu danh mục hiện tại không có ID tương tự với danh mục đang chỉnh sửa
            if (category.id !== id) {
                // Tạo một option mới và thêm vào select
                var option = $('<option>').val(category.id).text(category.name);
                
                // Kiểm tra nếu danh mục cha trùng với parent
                if (category.id === parent) {
                    option.attr('selected', true); // Đặt option này là selected
                }
                
                selectParent.append(option);
            }
        });

        // Hiển thị modal
        $('#editCategoryModal').modal('show');
    });



    // Sao chép danh mục
    $(document).ready(function() {
        // Xử lý sự kiện khi click vào button "Copy"
        $('.btn-copy').on('click', function() {
            // Lấy thông tin danh mục
            var id = $(this).data('id');
            var name = $(this).data('name');
            var parent = $(this).data('parent');

            // Tạo chuỗi chứa thông tin danh mục để copy vào clipboard
            var copyText = 'ID: ' + id + '\n' +
                'Name: ' + name + '\n' +
                'Parent: ' + parent;

            // Tạo một textarea ẩn để chứa chuỗi copyText
            var textarea = $('<textarea>').val(copyText).css({
                position: 'fixed',
                top: 0,
                left: 0,
                opacity: 0
            }).appendTo('body');

            // Copy nội dung từ textarea vào clipboard
            textarea[0].select();
            document.execCommand('copy');

            // Xóa textarea
            textarea.remove();

            // Hiển thị thông báo copy thành công
            alert('Đã sao chép thông tin danh mục!');
        });
    });



    // Xóa danh mục
    $('.btn-delete').on('click', function () {
        var categoryId = $(this).data('category-id');

        if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
            window.location.href = 'index.php?action=delete&category_id=' + categoryId;
        }
    });

    // Hàm trở về trang trước
    function goBack() {
        window.history.back();
    }

    // Hàm trở về trang index
    function goToIndex() {
        window.location.href = 'index.php';
    }
    // Tự động ẩn thông báo sau 0.5 giây
    setTimeout(function() {
        document.getElementById('successMessage').style.display = 'none';
        document.getElementById('errorMessage').style.display = 'none';
    }, 500);
</script>