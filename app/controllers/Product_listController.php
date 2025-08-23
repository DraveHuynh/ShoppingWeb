<?php
require_once ROOT_PATH.'/app/models/ProductModel.php';
require_once ROOT_PATH.'/app/models/CategoryModel.php';

class Product_listController {
    public function index() {
        $title = "Danh sách sản phẩm";
        $title_product_list = "Tất cả sản phẩm";

        // Lấy  sản phẩm từ model
        if (!empty($_POST['search'])) {
            $keyword = trim($_POST['search'] ?? '');  // Xử lý từ khóa
            $products = searchProducts($keyword); // Tìm kiếm sản phẩm
            $title_product_list = "Từ khóa tìm kiếm: ".$_POST['search'];
        } elseif (!empty($_GET['category_id'])) {
            $categoryId = intval($_GET['category_id']);
            $category = getCategory($categoryId);
            $products = getProductsByCategory($categoryId); // Lọc theo danh mục
            $title_product_list = $category['ten'];
        } else {
            $products = getAllProducts(); // Lấy tất cả sản phẩm
        }

        // Lấy tất cả danh mục sản phẩm từ model
        $categories = getAllCategorys();

        // Chuyển dữ liệu vào view
        $view = ROOT_PATH.'/app/views/product_list.php';
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }
}

function searchProducts($keyword) {
    if (empty($keyword)) return [];  // Tránh tìm kiếm khi không có từ khóa

    $products = getAllProducts();  // Lấy tất cả sản phẩm
    $keyword = mb_strtolower(trim($keyword));  // Chuẩn hóa từ khóa
    $keywords = preg_split('/\s+/', $keyword);  // Tách từ khóa thành mảng

    $matchedProducts = [];  // Mảng lưu các sản phẩm khớp

    foreach ($products as $product) {
        $productName = mb_strtolower($product['ten']);  // Chuẩn hóa tên sản phẩm
        $matchCount = 0;

        foreach ($keywords as $word) {
            if (strpos($productName, $word) !== false) {
                $matchCount++;
            }
        }

        // Tính tỷ lệ khớp với số từ trong từ khóa
        $totalWords = count($keywords);
        $matchPercent = ($totalWords > 0) ? ($matchCount / $totalWords) * 100 : 0;

        // Nếu tỷ lệ khớp đạt >= 30% thì coi là sản phẩm có liên quan
        if ($matchPercent >= 30) {
            $product['match_percent'] = $matchPercent;  // Thêm phần trăm vào mỗi sản phẩm
            $matchedProducts[] = $product;  // Thêm vào danh sách kết quả
        }
    }

    // Sắp xếp sản phẩm theo match_percent giảm dần
    usort($matchedProducts, function($a, $b) {
        return $b['match_percent'] <=> $a['match_percent'];
    });

    return $matchedProducts;
}
