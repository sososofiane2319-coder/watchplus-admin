<?php
/**
 * SeriesController
 * CRUD for series + seasons + episodes (with poster upload)
 */
class SeriesController {

    private static function handleUpload($fieldName, $prefix = 'sr') {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/series/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            return 'uploads/series/' . $filename;
        }
        return null;
    }

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/Series.php';
        require_once __DIR__ . '/../Models/Season.php';
        require_once __DIR__ . '/../Models/Episode.php';
        $model = new Series($pdo);
        return $model->getAll();
    }

    public static function store($pdo) {
        require_once __DIR__ . '/../Models/Series.php';
        $model = new Series($pdo);

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'release_year' => $_POST['release_year'] ?? null,
            'rating' => $_POST['rating'] ?? null,
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'poster' => self::handleUpload('poster', 'sr'),
            'banner' => self::handleUpload('banner', 'sr_banner'),
        ];

        if (empty($data['title'])) {
            $_SESSION['error'] = 'عنوان المسلسل مطلوب';
            header('Location: index.php?page=series&action=create');
            exit;
        }

        $model->create($data);
        $_SESSION['success'] = 'تم إضافة المسلسل بنجاح';
        header('Location: index.php?page=series');
        exit;
    }

    public static function update($pdo) {
        require_once __DIR__ . '/../Models/Series.php';
        $model = new Series($pdo);
        $id = $_POST['id'] ?? 0;

        $existing = $model->getById($id);
        $poster = self::handleUpload('poster', 'sr');
        $banner = self::handleUpload('banner', 'sr_banner');

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'release_year' => $_POST['release_year'] ?? null,
            'rating' => $_POST['rating'] ?? null,
            'category_id' => $_POST['category_id'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'poster' => $poster ?: ($existing['poster'] ?? null),
            'banner' => $banner ?: ($existing['banner'] ?? null),
        ];

        if (empty($data['title'])) {
            $_SESSION['error'] = 'عنوان المسلسل مطلوب';
            header('Location: index.php?page=series&action=edit&id=' . $id);
            exit;
        }

        $model->update($id, $data);
        $_SESSION['success'] = 'تم تحديث المسلسل بنجاح';
        header('Location: index.php?page=series');
        exit;
    }

    public static function delete($pdo) {
        require_once __DIR__ . '/../Models/Series.php';
        $model = new Series($pdo);
        $id = $_GET['id'] ?? 0;
        $model->delete($id);
        $_SESSION['success'] = 'تم حذف المسلسل بنجاح';
        header('Location: index.php?page=series');
        exit;
    }

    public static function seasons($pdo) {
        require_once __DIR__ . '/../Models/Series.php';
        require_once __DIR__ . '/../Models/Season.php';
        require_once __DIR__ . '/../Models/Episode.php';

        $seriesId = $_GET['id'] ?? 0;
        $seriesModel = new Series($pdo);
        $seasonModel = new Season($pdo);
        $episodeModel = new Episode($pdo);

        $series = $seriesModel->getById($seriesId);
        if (!$series) {
            $_SESSION['error'] = 'المسلسل غير موجود';
            header('Location: index.php?page=series');
            exit;
        }

        $seasons = $seasonModel->getBySeriesId($seriesId);
        foreach ($seasons as &$season) {
            $season['episodes'] = $episodeModel->getBySeasonId($season['id']);
        }

        return compact('series', 'seasons');
    }

    public static function addSeason($pdo) {
        require_once __DIR__ . '/../Models/Season.php';
        $model = new Season($pdo);
        $seriesId = $_POST['series_id'] ?? 0;
        $seasonNumber = intval($_POST['season_number'] ?? 1);
        $releaseYear = $_POST['release_year'] ?? null;

        $model->create($seriesId, $seasonNumber, $releaseYear ?: null);
        $_SESSION['success'] = 'تم إضافة الموسم بنجاح';
        header('Location: index.php?page=series&action=seasons&id=' . $seriesId);
        exit;
    }

    public static function addEpisode($pdo) {
        require_once __DIR__ . '/../Models/Episode.php';
        $model = new Episode($pdo);
        $seasonId = $_POST['season_id'] ?? 0;
        $seriesId = $_POST['series_id'] ?? 0;
        $episodeNumber = intval($_POST['episode_number'] ?? 1);
        $title = trim($_POST['title'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');

        if (empty($videoUrl)) {
            $_SESSION['error'] = 'رابط البث مطلوب';
            header('Location: index.php?page=series&action=seasons&id=' . $seriesId);
            exit;
        }

        $model->create($seasonId, $episodeNumber, $title, $videoUrl);
        $_SESSION['success'] = 'تم إضافة الحلقة بنجاح';
        header('Location: index.php?page=series&action=seasons&id=' . $seriesId);
        exit;
    }

    public static function deleteEpisode($pdo) {
        require_once __DIR__ . '/../Models/Episode.php';
        $model = new Episode($pdo);
        $id = $_GET['episode_id'] ?? 0;
        $seriesId = $_GET['series_id'] ?? 0;

        $model->delete($id);
        $_SESSION['success'] = 'تم حذف الحلقة بنجاح';
        header('Location: index.php?page=series&action=seasons&id=' . $seriesId);
        exit;
    }
}
