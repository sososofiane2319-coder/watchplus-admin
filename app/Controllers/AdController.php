<?php
/**
 * AdController
 * Manages ad networks (AdMob, AppLovin, StartApp, Unity)
 */
class AdController {

    public static function index($pdo) {
        require_once __DIR__ . '/../Models/AdSetting.php';
        $model = new AdSetting($pdo);
        return $model->getAll();
    }

    public static function save($pdo) {
        require_once __DIR__ . '/../Models/AdSetting.php';
        $model = new AdSetting($pdo);

        $networkName = $_POST['network_name'] ?? '';
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $settings = [];
        switch ($networkName) {
            case 'admob':
                $settings = [
                    'app_id' => trim($_POST['app_id'] ?? ''),
                    'banner_id' => trim($_POST['banner_id'] ?? ''),
                    'interstitial_id' => trim($_POST['interstitial_id'] ?? ''),
                    'rewarded_id' => trim($_POST['rewarded_id'] ?? ''),
                    'native_id' => trim($_POST['native_id'] ?? ''),
                ];
                break;
            case 'applovin':
                $settings = [
                    'sdk_key' => trim($_POST['sdk_key'] ?? ''),
                    'banner_zone_id' => trim($_POST['banner_zone_id'] ?? ''),
                    'interstitial_zone_id' => trim($_POST['interstitial_zone_id'] ?? ''),
                    'rewarded_zone_id' => trim($_POST['rewarded_zone_id'] ?? ''),
                ];
                break;
            case 'startapp':
                $settings = [
                    'app_id' => trim($_POST['app_id'] ?? ''),
                    'return_ad' => $_POST['return_ad'] ?? 'no',
                    'interstitial_type' => $_POST['interstitial_type'] ?? 'overlay',
                ];
                break;
            case 'unity':
                $settings = [
                    'game_id' => trim($_POST['game_id'] ?? ''),
                    'banner_placement' => trim($_POST['banner_placement'] ?? ''),
                    'interstitial_placement' => trim($_POST['interstitial_placement'] ?? ''),
                    'rewarded_placement' => trim($_POST['rewarded_placement'] ?? ''),
                    'test_mode' => isset($_POST['test_mode']) ? true : false,
                ];
                break;
        }

        $model->save($networkName, $settings, $isActive);
        $_SESSION['success'] = 'تم حفظ إعدادات الإعلانات بنجاح';
        header('Location: index.php?page=ads');
        exit;
    }

    public static function delete($pdo) {
        require_once __DIR__ . '/../Models/AdSetting.php';
        $model = new AdSetting($pdo);
        $networkName = $_GET['network'] ?? '';
        $model->delete($networkName);
        $_SESSION['success'] = 'تم حذف شبكة الإعلانات بنجاح';
        header('Location: index.php?page=ads');
        exit;
    }
}
