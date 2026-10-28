<?php
/**
 * Main Entry Point
 * Install check → Auth gate → Router → Template render
 */

session_start();

// Check if installed
require_once __DIR__ . '/../app/Controllers/InstallController.php';
if (!InstallController::isInstalled()) {
    header('Location: install.php');
    exit;
}

// Load database
require_once __DIR__ . '/../config/database.php';
$pdo = getDBConnection();

// Load auth
require_once __DIR__ . '/../app/Controllers/AuthController.php';

// Handle logout
$page = $_GET['page'] ?? 'dashboard';
if ($page === 'logout') {
    AuthController::logout();
}

// Handle login
if ($page === 'login') {
    $loginError = null;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = AuthController::login($pdo);
        if ($result === true) {
            header('Location: index.php?page=dashboard');
            exit;
        } else {
            $loginError = $result;
        }
    }
    require __DIR__ . '/../views/auth/login.php';
    exit;
}

// Auth gate
if (!AuthController::isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Load router
require __DIR__ . '/../routes/web.php';

// Render layout
if (!empty($viewFile)) {
    require __DIR__ . '/../views/layouts/header.php';
    require __DIR__ . '/../views/layouts/sidebar.php';
    
    echo '<div class="main-content">';
    
    // Flash messages
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['success']) . '<button class="alert-close" onclick="this.parentElement.remove()">&times;</button></div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger"><i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($_SESSION['error']) . '<button class="alert-close" onclick="this.parentElement.remove()">&times;</button></div>';
        unset($_SESSION['error']);
    }
    
    require __DIR__ . '/../views/' . $viewFile . '.php';
    
    echo '</div>'; // .main-content
    
    require __DIR__ . '/../views/layouts/footer.php';
}
