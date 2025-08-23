<?php
// Giả sử bạn đã có $_GET['url'] từ URL như 'product_list.php'
$url = isset($_GET['url']) ? $_GET['url'] : 'home.php';  // Nếu không có url, mặc định là 'home.php'

// Loại bỏ phần mở rộng .php khỏi URL
$url = str_replace('.php', '', $url);

// Tách URL thành các phần
$parts = explode('/', $url);

// Nếu URL là 'index' thì dùng HomeController, còn không thì tìm controller tương ứng với tên file
if ($parts[0] == 'index') {
    $controllerName = 'HomeController';
} else {
    // Tạo tên controller từ URL và thêm 'Controller'
    $controllerName = ucfirst($parts[0]) . 'Controller';  
}

// Kiểm tra phương thức (nếu có), mặc định là 'index'
$methodName = isset($parts[1]) ? $parts[1] : 'index';  

// Định nghĩa đường dẫn tới file controller
$controllerPath = dirname(__DIR__, 1) . "/app/controllers/$controllerName.php";

// Kiểm tra nếu file controller tồn tại
if (file_exists($controllerPath)) {
    require_once $controllerPath;  // Include file controller
    $controller = new $controllerName();  // Tạo đối tượng controller

    // Kiểm tra nếu phương thức tồn tại trong controller
    if (method_exists($controller, $methodName)) {
        $controller->$methodName();  // Gọi phương thức trong controller
    } else {
        die("Method '$methodName' không tồn tại trong $controllerName!");
    }
} else {
    die("Controller '$controllerName' không tìm thấy!");
}