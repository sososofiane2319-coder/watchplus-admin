<?php
/**
 * MatchController
 * CRUD for matches (two teams, time, broadcaster, commentator)
 */
class MatchController {

    private static function handleUpload($fieldName, $prefix = 'mt') {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/matches/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
        $filename = $prefix . '_' . time() . '_' . uniqid() . '.' . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $destination)) {
            return 'uploads/matches/' . $filename;
        }
        return null;
    }

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);
        return $model->getAll();
    }

    public static function store($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);

        $homeTeamLogo = self::handleUpload('home_team_logo', 'mt_home');
        $awayTeamLogo = self::handleUpload('away_team_logo', 'mt_away');
        $broadcasterLogo = self::handleUpload('broadcaster_logo', 'mt_bc');

        // Allow URL-based logos if no file uploaded
        if (!$homeTeamLogo && !empty($_POST['home_team_logo_url'])) {
            $homeTeamLogo = trim($_POST['home_team_logo_url']);
        }
        if (!$awayTeamLogo && !empty($_POST['away_team_logo_url'])) {
            $awayTeamLogo = trim($_POST['away_team_logo_url']);
        }

        $data = [
            'competition_name' => trim($_POST['competition_name'] ?? ''),
            'home_team_name' => trim($_POST['home_team_name'] ?? ''),
            'home_team_logo' => $homeTeamLogo,
            'away_team_name' => trim($_POST['away_team_name'] ?? ''),
            'away_team_logo' => $awayTeamLogo,
            'kickoff_time' => $_POST['kickoff_time'] ?? '',
            'status' => $_POST['status'] ?? 'upcoming',
            'live_minute' => trim($_POST['live_minute'] ?? ''),
            'stream_url' => trim($_POST['stream_url'] ?? ''),
            'broadcaster_name' => trim($_POST['broadcaster_name'] ?? ''),
            'broadcaster_logo' => $broadcasterLogo,
            'commentator_name' => trim($_POST['commentator_name'] ?? ''),
        ];

        if (empty($data['competition_name']) || empty($data['home_team_name']) || empty($data['away_team_name']) || empty($data['kickoff_time'])) {
            $_SESSION['error'] = 'اسم البطولة، الفريقان، وتوقيت المباراة مطلوبون';
            header('Location: index.php?page=matches&action=create');
            exit;
        }

        $model->create($data);
        $_SESSION['success'] = 'تم إضافة المباراة بنجاح';
        header('Location: index.php?page=matches');
        exit;
    }

    public static function update($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);
        $id = $_POST['id'] ?? 0;

        $existing = $model->getById($id);

        $homeTeamLogo = self::handleUpload('home_team_logo', 'mt_home');
        $awayTeamLogo = self::handleUpload('away_team_logo', 'mt_away');
        $broadcasterLogo = self::handleUpload('broadcaster_logo', 'mt_bc');

        if (!$homeTeamLogo && !empty($_POST['home_team_logo_url'])) {
            $homeTeamLogo = trim($_POST['home_team_logo_url']);
        }
        if (!$awayTeamLogo && !empty($_POST['away_team_logo_url'])) {
            $awayTeamLogo = trim($_POST['away_team_logo_url']);
        }

        $data = [
            'competition_name' => trim($_POST['competition_name'] ?? ''),
            'home_team_name' => trim($_POST['home_team_name'] ?? ''),
            'home_team_logo' => $homeTeamLogo ?: ($existing['home_team_logo'] ?? null),
            'away_team_name' => trim($_POST['away_team_name'] ?? ''),
            'away_team_logo' => $awayTeamLogo ?: ($existing['away_team_logo'] ?? null),
            'kickoff_time' => $_POST['kickoff_time'] ?? '',
            'status' => $_POST['status'] ?? 'upcoming',
            'live_minute' => trim($_POST['live_minute'] ?? ''),
            'stream_url' => trim($_POST['stream_url'] ?? ''),
            'broadcaster_name' => trim($_POST['broadcaster_name'] ?? ''),
            'broadcaster_logo' => $broadcasterLogo ?: ($existing['broadcaster_logo'] ?? null),
            'commentator_name' => trim($_POST['commentator_name'] ?? ''),
        ];

        $model->update($id, $data);
        $_SESSION['success'] = 'تم تحديث المباراة بنجاح';
        header('Location: index.php?page=matches');
        exit;
    }

    public static function delete($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);
        $id = $_GET['id'] ?? 0;
        $model->delete($id);
        $_SESSION['success'] = 'تم حذف المباراة بنجاح';
        header('Location: index.php?page=matches');
        exit;
    }

    public static function setLive($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);
        $id = $_GET['id'] ?? 0;
        $model->setLive($id);
        $_SESSION['success'] = 'تم تعيين المباراة كمباشرة';
        header('Location: index.php?page=matches');
        exit;
    }

    public static function setFinished($pdo) {
        require_once __DIR__ . '/../Models/MatchModel.php';
        $model = new MatchModel($pdo);
        $id = $_GET['id'] ?? 0;
        $model->setFinished($id);
        $_SESSION['success'] = 'تم تعيين المباراة كمنتهية';
        header('Location: index.php?page=matches');
        exit;
    }
}
