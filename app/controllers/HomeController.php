<?php
require_once ROOT_PATH.'/app/models/BannerModel.php';
require_once ROOT_PATH.'/app/models/ProductModel.php';
require_once ROOT_PATH.'/app/models/showHomeModel.php';
class HomeController {
    public function index() {
        $title = "Trang Chủ";
        $banners = getAllBanners() ?? [];
        $Lists_showHome = getAllshowHome() ?? [];
        $showList = [];
        foreach ($Lists_showHome as $list){
            if (!isset($list['stt'], $list['tieu_de'], $list['danh_muc_id'])) {
                continue; // bỏ qua dòng không hợp lệ
                }
            $products = getProductsByCategory($list["danh_muc_id"]) ?? [];
            $products = array_slice($products, 0, 4);
            $showList[] = [
                'stt' => $list['stt'],
                'tieu_de' => $list['tieu_de'],
                'danh_muc_id'=> $list['danh_muc_id'],
                'products' => $products
            ];
        }
        // Bảo đảm sắp xếp đúng stt
        usort($showList, fn($a, $b) => $a['stt'] <=> $b['stt']);
        
        
        $view = ROOT_PATH.'/app/views/home.php';
        require_once ROOT_PATH . '/app/views/layout/main.php'; // Load layout chính
    }
}


