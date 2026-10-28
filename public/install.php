<?php
/**
 * Install Entry Point
 * Loads routes/install.php
 */

require_once __DIR__ . '/../routes/install.php';

// Load installer view
require __DIR__ . '/../views/install/layout.php';
