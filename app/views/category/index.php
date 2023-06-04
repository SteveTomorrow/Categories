<?php include 'input.php'; ?>
<?php include 'header.php'; ?>
<body>
    <div class="container-md">
        <!-- Thanh điều hướng -->
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary mb-3">
            <div class="container">
                <!-- Categories -->
                <a class="navbar-brand" ></i> Categories</a>

                <!-- Menu lựa chọn -->
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ml-auto">
                        <!-- Lựa chọn Back -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="goBack()"><i class="fa fa-arrow-left text-light"></i> </a>
                        </li>
                        <!-- Lựa chọn Home -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="goToIndex()"><i class="fa fa-home text-light"></i> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="row mt-3">
            <div class="col-md-12 text-right">
                <!-- Thanh tìm kiếm -->
                <div class="input-group">
                    <input type="text" id="search" class="form-control" placeholder="Search by name">
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12 text-right">
                <!-- Button thêm danh mục -->
                <button type="button" class="btn btn-primary rounded-circle" data-toggle="modal" data-target="#addCategoryModal">
                    <i class="fa fa-plus text-light"></i>
                </button>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-12">
                <!-- Hiển thị danh mục sản phẩm -->
                <div class="scrollable-div">
                    <div class="container-sm">
                        <table class="table table-striped table-sm custom-table">
                            <?php
                                // Gọi file xử lý
                                include 'process.php';
                                include 'modal.php';
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <?php include 'event.php'; ?>
</body>
</html>
