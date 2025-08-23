<?php
require_once 'init.php';

// Lấy giao thức HTTP hoặc HTTPS
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";

// Lấy domain (localhost hoặc domain.com)
$host = $_SERVER['HTTP_HOST'];

// Chuyển `DOCUMENT_ROOT` và `ROOT_PATH` thành đường dẫn đúng
$projectRoot = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', str_replace('\\', '/', ROOT_PATH));

// Định nghĩa BASE_URL đúng
define('BASE_URL', rtrim($protocol . '://' . $host . $projectRoot, '/'));

// Các đường dẫn khác dựa vào BASE_URL
define('PUBLIC_URL', BASE_URL . '/public');
define('IMAGE_URL', PUBLIC_URL . '/assets/images');
define('CSS_URL', PUBLIC_URL . '/assets/css');
define('JS_URL', PUBLIC_URL . '/assets/js');
