<?php
/**
 * InstallController
 * Auto-installer logic (4 steps)
 */
class InstallController {

    public static function isInstalled() {
        $envPath = dirname(dirname(__DIR__)) . '/.env';
        if (!file_exists($envPath)) return false;
        $content = file_get_contents($envPath);
        return strpos($content, 'INSTALLED=true') !== false;
    }

    public static function handleStep($step) {
        switch ($step) {
            case 1:
                return self::step1();
            case 2:
                return self::step2();
            case 3:
                return self::step3();
            case 4:
                return self::step4();
            default:
                return self::step1();
        }
    }

    /**
     * Step 1: Check Requirements
     */
    private static function step1() {
        $requirements = [];

        // PHP Version
        $requirements['php_version'] = [
            'label' => 'PHP >= 7.4',
            'status' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'current' => PHP_VERSION,
        ];

        // PDO Extension
        $requirements['pdo'] = [
            'label' => 'PDO Extension',
            'status' => extension_loaded('pdo'),
        ];

        // PDO MySQL
        $requirements['pdo_mysql'] = [
            'label' => 'PDO MySQL Driver',
            'status' => extension_loaded('pdo_mysql'),
        ];

        // mbstring
        $requirements['mbstring'] = [
            'label' => 'mbstring Extension',
            'status' => extension_loaded('mbstring'),
        ];

        // JSON
        $requirements['json'] = [
            'label' => 'JSON Extension',
            'status' => extension_loaded('json'),
        ];

        // Writable directories
        $baseDir = dirname(dirname(__DIR__));
        $requirements['env_writable'] = [
            'label' => '.env writable',
            'status' => is_writable($baseDir) || (file_exists($baseDir . '/.env') && is_writable($baseDir . '/.env')),
        ];

        $uploadsDir = $baseDir . '/public/uploads';
        $requirements['uploads_writable'] = [
            'label' => 'public/uploads/ writable',
            'status' => is_dir($uploadsDir) ? is_writable($uploadsDir) : is_writable(dirname($uploadsDir)),
        ];

        $allPassed = true;
        foreach ($requirements as $req) {
            if (!$req['status']) {
                $allPassed = false;
                break;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $allPassed) {
            $_SESSION['install_step1'] = true;
            header('Location: install.php?step=2');
            exit;
        }

        return ['requirements' => $requirements, 'allPassed' => $allPassed];
    }

    /**
     * Step 2: Database Setup
     */
    private static function step2() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['db_host'] = trim($_POST['db_host'] ?? 'localhost');
            $_SESSION['db_name'] = trim($_POST['db_name'] ?? '');
            $_SESSION['db_user'] = trim($_POST['db_user'] ?? 'root');
            $_SESSION['db_pass'] = $_POST['db_pass'] ?? '';

            // AJAX test connection
            if (isset($_POST['test_connection'])) {
                header('Content-Type: application/json');
                try {
                    new PDO(
                        "mysql:host={$_SESSION['db_host']}",
                        $_SESSION['db_user'],
                        $_SESSION['db_pass'],
                        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                    );
                    echo json_encode(['status' => 'success', 'message' => 'تم الاتصال بنجاح']);
                } catch (PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => 'فشل الاتصال: ' . $e->getMessage()]);
                }
                exit;
            }

            $_SESSION['install_step2'] = true;
            header('Location: install.php?step=3');
            exit;
        }

        return [];
    }

    /**
     * Step 3: Admin Account
     */
    private static function step3() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['admin_email'] = trim($_POST['admin_email'] ?? '');
            $_SESSION['admin_password'] = $_POST['admin_password'] ?? '';
            $_SESSION['install_step3'] = true;
            header('Location: install.php?step=4');
            exit;
        }

        return [];
    }

    /**
     * Step 4: Finish — Execute install via AJAX
     */
    private static function step4() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['execute_install'])) {
            header('Content-Type: application/json');

            try {
                $host = $_SESSION['db_host'] ?? 'localhost';
                $dbname = $_SESSION['db_name'] ?? '';
                $user = $_SESSION['db_user'] ?? 'root';
                $pass = $_SESSION['db_pass'] ?? '';

                // Connect and create database if not exists
                $pdo = new PDO(
                    "mysql:host={$host}",
                    $user,
                    $pass,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $pdo->exec("USE `{$dbname}`");

                // Execute install.sql
                $sqlFile = dirname(dirname(__DIR__)) . '/install.sql';
                $sql = file_get_contents($sqlFile);
                $pdo->exec($sql);

                // Create admin account
                $adminEmail = $_SESSION['admin_email'] ?? '';
                $adminPassword = password_hash($_SESSION['admin_password'] ?? '', PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO admins (email, password, role) VALUES (?, ?, 'super_admin')");
                $stmt->execute([$adminEmail, $adminPassword]);

                // Write .env file
                $envContent = "DB_HOST={$host}\n";
                $envContent .= "DB_NAME={$dbname}\n";
                $envContent .= "DB_USER={$user}\n";
                $envContent .= "DB_PASS={$pass}\n";
                $envContent .= "APP_NAME=WATCH PLUS\n";
                $envContent .= "APP_URL=\n";
                $envContent .= "MAINTENANCE_MODE=false\n";
                $envContent .= "INSTALLED=true\n";

                $envPath = dirname(dirname(__DIR__)) . '/.env';
                file_put_contents($envPath, $envContent);

                // Create upload directories
                $uploadsBase = dirname(dirname(__DIR__)) . '/public/uploads';
                foreach (['channels', 'series', 'matches'] as $dir) {
                    $path = $uploadsBase . '/' . $dir;
                    if (!is_dir($path)) {
                        mkdir($path, 0755, true);
                    }
                }

                // Clean session install data
                unset($_SESSION['install_step1'], $_SESSION['install_step2'], $_SESSION['install_step3']);
                unset($_SESSION['db_host'], $_SESSION['db_name'], $_SESSION['db_user'], $_SESSION['db_pass']);
                unset($_SESSION['admin_email'], $_SESSION['admin_password']);

                echo json_encode(['status' => 'success', 'message' => 'تم التثبيت بنجاح!']);
            } catch (Exception $e) {
                echo json_encode(['status' => 'error', 'message' => 'خطأ في التثبيت: ' . $e->getMessage()]);
            }
            exit;
        }

        return [
            'db_host' => $_SESSION['db_host'] ?? 'localhost',
            'db_name' => $_SESSION['db_name'] ?? '',
            'db_user' => $_SESSION['db_user'] ?? 'root',
            'admin_email' => $_SESSION['admin_email'] ?? '',
        ];
    }
}
