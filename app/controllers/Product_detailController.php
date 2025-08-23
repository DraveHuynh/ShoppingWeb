<?php
require_once ROOT_PATH.'/app/models/ProductModel.php';
require_once ROOT_PATH.'/app/models/ImagesProductModel.php';
require_once ROOT_PATH.'/app/models/CategoryModel.php';

class Product_detailController {
    public function index() {
        $title = "Chi tiết sản phẩm";
        // Lấy  sản phẩm từ model
        $product_id = intval($_GET['product_id']);
        $product = getProduct($product_id); // lấy tất cả sản phẩm

        $image_products = getNameImages($product_id);

        $all_images = array_merge(
            [['img_url' => $product['img_url']]],  // ảnh chính
            $image_products                         // ảnh phụ
        );
        

        $caterogyId = $product['danh_muc_id'];

        $caterogy = getCategory($caterogyId);
        $related_products = getProductsByCategory($caterogyId);

        $related_products = array_filter($related_products, function($item) use ($product) {
            return $item['id'] != $product['id'];
        });

        $view = ROOT_PATH.'/app/views/product_detail.php';

        // Truyền dữ liệu vào view
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }
}
