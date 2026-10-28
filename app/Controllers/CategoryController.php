<?php
/**
 * CategoryController
 * CRUD for categories
 */
class CategoryController {

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/Category.php';
        $model = new Category($pdo);
        return $model->getAll();
    }

    public static function store($pdo) {
        require_once __DIR__ . '/../Models/Category.php';
        $model = new Category($pdo);
        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? 'general';

        if (empty($name)) {
            $_SESSION['error'] = 'اسم التصنيف مطلوب';
            header('Location: index.php?page=categories');
            exit;
        }

        $model->create($name, $type);
        $_SESSION['success'] = 'تم إضافة التصنيف بنجاح';
        header('Location: index.php?page=categories');
        exit;
    }

    public static function update($pdo) {
        require_once __DIR__ . '/../Models/Category.php';
        $model = new Category($pdo);
        $id = $_POST['id'] ?? 0;
        $name = trim($_POST['name'] ?? '');
        $type = $_POST['type'] ?? 'general';

        if (empty($name)) {
            $_SESSION['error'] = 'اسم التصنيف مطلوب';
            header('Location: index.php?page=categories');
            exit;
        }

        $model->update($id, $name, $type);
        $_SESSION['success'] = 'تم تحديث التصنيف بنجاح';
        header('Location: index.php?page=categories');
        exit;
    }

    public static function delete($pdo) {
        require_once __DIR__ . '/../Models/Category.php';
        $model = new Category($pdo);
        $id = $_GET['id'] ?? 0;
        $model->delete($id);
        $_SESSION['success'] = 'تم حذف التصنيف بنجاح';
        header('Location: index.php?page=categories');
        exit;
    }
}
