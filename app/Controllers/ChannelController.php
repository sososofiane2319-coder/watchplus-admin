<?php
/**
 * ChannelController
 * CRUD for channels (with logo upload)
 */
class ChannelController {

    private static function handleUpload($fieldName, $prefix = 'ch') {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/channels/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            return 'uploads/channels/' . $filename;
        }
        return null;
    }

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/Channel.php';
        $model = new Channel($pdo);
        return $model->getAll();
    }

    public static function store($pdo) {
        require_once __DIR__ . '/../Models/Channel.php';
        require_once __DIR__ . '/../Models/Category.php';
        $model = new Channel($pdo);

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'stream_url' => trim($_POST['stream_url'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'sort_order' => intval($_POST['sort_order'] ?? 0),
            'logo' => self::handleUpload('logo', 'ch'),
        ];

        if (empty($data['name']) || empty($data['stream_url'])) {
            $_SESSION['error'] = 'اسم القناة ورابط البث مطلوبان';
            header('Location: index.php?page=channels&action=create');
            exit;
        }

        $model->create($data);
        $_SESSION['success'] = 'تم إضافة القناة بنجاح';
        header('Location: index.php?page=channels');
        exit;
    }

    public static function update($pdo) {
        require_once __DIR__ . '/../Models/Channel.php';
        $model = new Channel($pdo);
        $id = $_POST['id'] ?? 0;

        $existing = $model->getById($id);
        $logo = self::handleUpload('logo', 'ch');

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'stream_url' => trim($_POST['stream_url'] ?? ''),
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'sort_order' => intval($_POST['sort_order'] ?? 0),
            'logo' => $logo ?: ($existing['logo'] ?? null),
        ];

        if (empty($data['name']) || empty($data['stream_url'])) {
            $_SESSION['error'] = 'اسم القناة ورابط البث مطلوبان';
            header('Location: index.php?page=channels&action=edit&id=' . $id);
            exit;
        }

        $model->update($id, $data);
        $_SESSION['success'] = 'تم تحديث القناة بنجاح';
        header('Location: index.php?page=channels');
        exit;
    }

    public static function delete($pdo) {
        require_once __DIR__ . '/../Models/Channel.php';
        $model = new Channel($pdo);
        $id = $_GET['id'] ?? 0;
        $model->delete($id);
        $_SESSION['success'] = 'تم حذف القناة بنجاح';
        header('Location: index.php?page=channels');
        exit;
    }
}
