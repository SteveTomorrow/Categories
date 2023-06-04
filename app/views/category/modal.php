<!-- Modal thêm danh mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="index.php?action=create">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="parent_id" class="form-label">Parent category:</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">-- Choose Parent --</option>
                            <?php
                            // Hiển thị danh sách danh mục trong dropdown
                            foreach ($categories as $category) {
                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal sửa danh mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="index.php?action=edit">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_id" class="form-label">ID:</label>
                        <input type="text" class="form-control" id="edit_id" name="id" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_parent_id" class="form-label">Parent category:</label>
                        <select class="form-control" id="edit_parent_id" name="parent_id" data-selected-id="">
                            <option value="">None</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Modal sao chép danh mục -->
<div class="modal fade" id="copyCategoryModal" tabindex="-1" role="dialog" aria-labelledby="copyCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="copyCategoryModalLabel">Copy categories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="index.php?action=copy">
                <div class="modal-body">
                    <p>Are you sure you want to copy this category?</p>
                    <input type="hidden" id="copy_category_id" name="category_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Copy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal chi tiết danh mục -->
<div class="modal fade" id="detailCategoryModal" tabindex="-1" role="dialog" aria-labelledby="detailCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailCategoryModalLabel">Detail Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>ID: <span id="detail_id"></span></p>
                <p>Name: <span id="detail_name"></span></p>
                <p>Parent category: <span id="detail_parent"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
