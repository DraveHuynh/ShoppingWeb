<?php
// Định nghĩa đường dẫn gốc
define('ROOT_PATH', dirname(__DIR__));

// Nạp các file quan trọng
require_once ROOT_PATH . '/config/constants.php';
require_once ROOT_PATH . '/config/database.php'; // Chỉ cần require file này
require_once ROOT_PATH . '/core/functions.php';
