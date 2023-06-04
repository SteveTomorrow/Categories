<?php
class Category
{
    private $id;
    private $name;
    private $parentId;
    private $conn;
    private $childCategories;

    // Constructor, getters, setters và các phương thức khác
    
    /**
     * Lấy ID của danh mục
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Thiết lập ID cho danh mục
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Thiết lập tên cho danh mục
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Thiết lập ID cha cho danh mục
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Thiết lập danh sách danh mục con cho danh mục
     */
    public function setChildCategories($childCategories)
    {
        $this->childCategories = $childCategories;
    }

    // Các phương thức khác

    /**
     * Constructor
     * Kết nối đến cơ sở dữ liệu
     */
    public function __construct()
    {
        $host = 'localhost';
        $port = '5432';
        $dbname = 'categories';
        $user = 'postgres';
        $password = '09092000@';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        try {
            $this->conn = new PDO($dsn, $user, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Đã kết nối thành công đến cơ sở dữ liệu!";
        } catch (PDOException $e) {
            echo "Kết nối thất bại: " . $e->getMessage();
        }
    }

    /**
     * Lưu thông tin danh mục vào cơ sở dữ liệu
     */
    public function save()
    {
        $stmt = $this->conn->prepare("INSERT INTO categories (name, parent_id) VALUES (:name, :parent_id)");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':parent_id', $this->parentId);
        $stmt->execute();
    }

    /**
     * Cập nhật thông tin danh mục trong cơ sở dữ liệu
     */
    public function update()
    {
        if ($this->parentId === '') {
            $this->parentId = null;
        }

        $stmt = $this->conn->prepare("UPDATE categories SET name = :name, parent_id = :parent_id WHERE id = :id");
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':parent_id', $this->parentId, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Xoá danh mục
     */
    public function delete()
    {
        $this->deleteChildren($this->id);

        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    }

    /**
     * Hàm đệ quy để xoá danh mục con
     */
    private function deleteChildren($parentId)
    {
        $stmt = $this->conn->prepare("SELECT id FROM categories WHERE parent_id = :parentId");
        $stmt->bindParam(':parentId', $parentId);
        $stmt->execute();
        $children = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($children as $childId) {
            $this->deleteChildren($childId);
            $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
            $stmt->bindParam(':id', $childId);
            $stmt->execute();
        }
    }

    /**
     * Lấy tất cả danh mục từ cơ sở dữ liệu
     */
    public static function getAll()
    {
        $conn = new PDO("pgsql:host=localhost;port=5432;dbname=categories", "postgres", "09092000@");
        $stmt = $conn->prepare("SELECT * FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }

    /**
     * Lấy danh mục theo ID từ cơ sở dữ liệu
     */
    public static function getById($id)
    {
        $conn = new PDO("pgsql:host=localhost;port=5432;dbname=categories", "postgres", "09092000@");
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $categoryData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($categoryData) {
            $category = new Category();
            $category->setId($categoryData['id']);
            $category->setName($categoryData['name']);
            $category->setParentId($categoryData['parent_id']);
            return $category;
        }

        return null;
    }

    /**
     * Lấy danh sách các danh mục con của một danh mục
     */
    public static function getSubCategories($parentId)
    {
        $conn = new PDO("pgsql:host=localhost;port=5432;dbname=categories", "postgres", "09092000@");
        $stmt = $conn->prepare("SELECT * FROM categories WHERE parent_id = :parentId");
        $stmt->bindParam(':parentId', $parentId);
        $stmt->execute();
        $subCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $subCategories;
    }

    /**
     * Tìm kiếm danh mục dựa trên từ khóa
     */
    public static function search($keyword)
    {
        $conn = new PDO("pgsql:host=localhost;port=5432;dbname=categories", "postgres", "09092000@");
        $stmt = $conn->prepare("SELECT * FROM categories WHERE name LIKE :keyword");
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $categories;
    }

    /**
     * Kiểm tra xem tên danh mục đã tồn tại trong cơ sở dữ liệu hay chưa
     */
    public function checkUniqueName()
    {
        $query = "SELECT COUNT(*) FROM categories WHERE name = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        return $count === 0;
    }
}
