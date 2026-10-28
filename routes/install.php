<?php
/**
 * Install Routes
 * Handles the 4-step installer
 */

session_start();

require_once __DIR__ . '/../app/Controllers/InstallController.php';

// If already installed, redirect to main app
if (InstallController::isInstalled()) {
    header('Location: index.php');
    exit;
}

$step = intval($_GET['step'] ?? 1);

// Validate step order
if ($step > 1 && empty($_SESSION['install_step' . ($step - 1)])) {
    header('Location: install.php?step=' . ($step - 1));
    exit;
}

$data = InstallController::handleStep($step);
