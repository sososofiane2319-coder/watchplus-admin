<?php
/**
 * Web Routes
 * Maps ?page=&action= to Controllers
 */

$page = $_GET['page'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Load controllers
require_once __DIR__ . '/../app/Controllers/DashboardController.php';
require_once __DIR__ . '/../app/Controllers/CategoryController.php';
require_once __DIR__ . '/../app/Controllers/ChannelController.php';
require_once __DIR__ . '/../app/Controllers/SeriesController.php';
require_once __DIR__ . '/../app/Controllers/MatchController.php';
require_once __DIR__ . '/../app/Controllers/AdController.php';

// Settings page handler
function handleSettings($pdo, $action) {
    if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        // Save all settings
        $fields = [
            'app_name', 'app_version', 'app_description', 'support_email',
            'privacy_url', 'terms_of_service', 'developer_name', 'developer_bio',
            'update_version', 'update_url', 'update_message'
        ];
        $toggles = [
            'maintenance_mode', 'force_update', 'show_ads',
            'enable_channels', 'enable_series', 'enable_matches', 'update_enabled'
        ];

        foreach ($fields as $key) {
            $value = trim($_POST[$key] ?? '');
            $stmt = $pdo->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$key, $value, $value]);
        }
        foreach ($toggles as $key) {
            $value = isset($_POST[$key]) ? '1' : '0';
            $stmt = $pdo->prepare("INSERT INTO app_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute([$key, $value, $value]);
        }

        $_SESSION['success'] = 'تم حفظ الإعدادات بنجاح';
        header('Location: index.php?page=settings');
        exit;
    }

    // Load all settings
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM app_settings");
    $rows = $stmt->fetchAll();
    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    return $settings;
}

// Route handling
$viewData = [];
$viewFile = '';

switch ($page) {
    case 'dashboard':
        $viewData = DashboardController::index($pdo);
        $viewFile = 'dashboard/index';
        break;

    case 'categories':
        switch ($action) {
            case 'store':
                CategoryController::store($pdo);
                break;
            case 'update':
                CategoryController::update($pdo);
                break;
            case 'delete':
                CategoryController::delete($pdo);
                break;
            default:
                $viewData['categories'] = CategoryController::index($pdo);
                $editCategory = null;
                if ($action === 'edit' && $id) {
                    require_once __DIR__ . '/../app/Models/Category.php';
                    $catModel = new Category($pdo);
                    $editCategory = $catModel->getById($id);
                }
                $viewData['editCategory'] = $editCategory;
                $viewFile = 'categories/form';
                break;
        }
        break;

    case 'channels':
        switch ($action) {
            case 'store':
                ChannelController::store($pdo);
                break;
            case 'update':
                ChannelController::update($pdo);
                break;
            case 'delete':
                ChannelController::delete($pdo);
                break;
            case 'create':
            case 'edit':
                require_once __DIR__ . '/../app/Models/Category.php';
                $catModel = new Category($pdo);
                $viewData['categories'] = $catModel->getAll();
                if ($action === 'edit' && $id) {
                    require_once __DIR__ . '/../app/Models/Channel.php';
                    $chModel = new Channel($pdo);
                    $viewData['channel'] = $chModel->getById($id);
                }
                $viewFile = 'channels/form';
                break;
            default:
                $viewData['channels'] = ChannelController::index($pdo);
                $viewFile = 'channels/index';
                break;
        }
        break;

    case 'series':
        switch ($action) {
            case 'store':
                SeriesController::store($pdo);
                break;
            case 'update':
                SeriesController::update($pdo);
                break;
            case 'delete':
                SeriesController::delete($pdo);
                break;
            case 'add_season':
                SeriesController::addSeason($pdo);
                break;
            case 'add_episode':
                SeriesController::addEpisode($pdo);
                break;
            case 'delete_episode':
                SeriesController::deleteEpisode($pdo);
                break;
            case 'seasons':
                $viewData = SeriesController::seasons($pdo);
                $viewFile = 'series/seasons';
                break;
            case 'create':
            case 'edit':
                require_once __DIR__ . '/../app/Models/Category.php';
                $catModel = new Category($pdo);
                $viewData['categories'] = $catModel->getAll();
                if ($action === 'edit' && $id) {
                    require_once __DIR__ . '/../app/Models/Series.php';
                    $sModel = new Series($pdo);
                    $viewData['series'] = $sModel->getById($id);
                }
                $viewFile = 'series/form';
                break;
            default:
                $viewData['seriesList'] = SeriesController::index($pdo);
                $viewFile = 'series/index';
                break;
        }
        break;

    case 'matches':
        switch ($action) {
            case 'store':
                MatchController::store($pdo);
                break;
            case 'update':
                MatchController::update($pdo);
                break;
            case 'delete':
                MatchController::delete($pdo);
                break;
            case 'set_live':
                MatchController::setLive($pdo);
                break;
            case 'set_finished':
                MatchController::setFinished($pdo);
                break;
            case 'create':
            case 'edit':
                if ($action === 'edit' && $id) {
                    require_once __DIR__ . '/../app/Models/MatchModel.php';
                    $mModel = new MatchModel($pdo);
                    $viewData['match'] = $mModel->getById($id);
                }
                $viewFile = 'matches/form';
                break;
            default:
                $viewData['matches'] = MatchController::index($pdo);
                $viewFile = 'matches/index';
                break;
        }
        break;

    case 'ads':
        switch ($action) {
            case 'save':
                AdController::save($pdo);
                break;
            case 'delete':
                AdController::delete($pdo);
                break;
            default:
                $viewData['adSettings'] = AdController::index($pdo);
                $viewFile = 'ads/settings';
                break;
        }
        break;

    case 'settings':
        $viewData['settings'] = handleSettings($pdo, $action);
        $viewFile = 'settings/index';
        break;

    default:
        $viewData = DashboardController::index($pdo);
        $viewFile = 'dashboard/index';
        break;
}
