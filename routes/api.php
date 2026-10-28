<?php
/**
 * API Routes
 * JSON API endpoints for the mobile app
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once __DIR__ . '/../config/database.php';

$pdo = getDBConnection();
$endpoint = $_GET['endpoint'] ?? '';

function jsonResponse($data, $status = 'success') {
    echo json_encode(['status' => $status, 'data' => $data], JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError($message) {
    echo json_encode(['status' => 'error', 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($endpoint) {
    case 'categories':
        require_once __DIR__ . '/../app/Models/Category.php';
        $model = new Category($pdo);
        jsonResponse($model->getAll());
        break;

    case 'channels':
        require_once __DIR__ . '/../app/Models/Channel.php';
        $model = new Channel($pdo);
        jsonResponse($model->getActive());
        break;

    case 'series':
        require_once __DIR__ . '/../app/Models/Series.php';
        require_once __DIR__ . '/../app/Models/Season.php';
        require_once __DIR__ . '/../app/Models/Episode.php';
        $model = new Series($pdo);
        $allSeries = $model->getActive();
        foreach ($allSeries as &$s) {
            $full = $model->getWithSeasonsAndEpisodes($s['id']);
            $s['seasons'] = $full['seasons'] ?? [];
        }
        jsonResponse($allSeries);
        break;

    case 'matches':
        require_once __DIR__ . '/../app/Models/MatchModel.php';
        $model = new MatchModel($pdo);
        $upcoming = $model->getByStatus('upcoming');
        $live = $model->getByStatus('live');
        $matches = array_merge($live, $upcoming);
        usort($matches, function($a, $b) {
            return strtotime($a['kickoff_time']) - strtotime($b['kickoff_time']);
        });
        jsonResponse($matches);
        break;

    case 'ads':
        require_once __DIR__ . '/../app/Models/AdSetting.php';
        $model = new AdSetting($pdo);
        jsonResponse($model->getActiveAds());
        break;

    case 'settings':
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM app_settings");
        $rows = $stmt->fetchAll();
        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }
        jsonResponse($settings);
        break;

    case 'check-update':
        $stmt = $pdo->query("SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN ('update_enabled', 'update_version', 'update_url', 'update_message', 'force_update')");
        $rows = $stmt->fetchAll();
        $update = [];
        foreach ($rows as $row) {
            $update[$row['setting_key']] = $row['setting_value'];
        }
        jsonResponse($update);
        break;

    default:
        jsonError('Invalid endpoint');
        break;
}
