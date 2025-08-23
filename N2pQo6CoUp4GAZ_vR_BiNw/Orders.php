<?php 
$orders= getAllOrders();
$orders_info = [];
foreach ($orders as $order) {
    $order_details = getAllOrderDetailsByOId($order['id']);
    $address = getAllAddressByAIdAdmin($order['dia_chi_id']);
    $quan = getDistrictName($address['quan_id']);
    $customer = getCustomer($order['khach_hang_id']);
    $trang_thai_map = [
        'Unconfirmed' => 'Chưa xác nhận',
        'Confirmed' => 'Đã xác nhận',
        'Cancel' => 'Đã hủy'
    ];
    $trang_thai = $trang_thai_map[$order['trang_thai']] ?? 'Không xác định';
    
    $san_pham = [];
    
    foreach ($order_details as $order_detail) {
        $product = getProduct($order_detail['san_pham_id']);
        $category = getCategory($product['danh_muc_id']);

        $san_pham[] = [
            'ten_san_pham' => $product['ten'],
            'hinh_anh' => $product['img_url'],
            'ten_danh_muc' => $category['ten'],
            'so_luong' => $order_detail['so_luong'],
            'don_gia' => $order_detail['don_gia'],
        ];
    }
    
    $orders_info[] = [
        'don_hang_id' => $order['id'],
        'tong_tien' => $order['tong_tien'],
        'trang_thai' => $trang_thai,
        'quan' => $quan['ten'],
        'so_nha_va_duong' => $address['so_nha_va_duong'],
        'sdt' => $address['sdt'],
        'san_pham' => $san_pham,
        'ten_khach' => $customer['username'],
    ];
}

?>

<div class="page">
  <h1>Quản lý đơn hàng</h1>
  <div class="order-container">
    <table>
    <tr class="title">
        <th>ID</th>
        <th>Tổng tiền</th>
        <th>Trạng thái</th>
        <th>Thời gian</th>
        <th>Hành động</th>
    </tr>

    <?php if (!empty($orders)){
    foreach ($orders as $order) {
        $trang_thai_map = [
            'Unconfirmed' => 'Chưa xác nhận',
            'Confirmed' => 'Đã xác nhận',
            'Cancel' => 'Đã hủy'
        ];
        $trang_thai = $trang_thai_map[$order['trang_thai']] ?? 'Không xác định';
    ?> 
    <tr>
        <td><?= htmlspecialchars($order['id']) ?></td>
        <td><?= htmlspecialchars($order['tong_tien']) ?></td>
        <td><?= $trang_thai ?></td>
        <td><?= htmlspecialchars($order['ngay_tao']) ?></td>
         
        <?php if ($trang_thai == 'Chưa xác nhận') { ?>
            <td>
                <div class="dropdown">
                    <button class="dropbtn">Hành động</button>
                    <div class="dropdown-content">
                        <form method="POST" action="<?= BASE_URL ?>/app/controllers/Admin_OrderController.php">
                            <input type="hidden" name="don_hang_id" value="<?= $order['id'] ?>">
                            <input type="submit" name="cacel" value="Hủy đơn hàng">
                            <input type="submit" name="update" value="Xác nhận đơn">
                        </form>
                        
                        <form method="POST" action="">
                            <input type="hidden" name="don_hang_id" value="<?= $order['id'] ?>">
                            <input type="submit" name="detail" value="Xem chi tiết">
                        </form>
                    
                    </div>
                </div>
            </td>
        <?php  }else { ?> 
            <td>
                <form method="POST" action="">
                    <input type="hidden" name="don_hang_id" value="<?= $order['id'] ?>">
                    <input class="addbtn" type="submit" name="detail" value="Xem chi tiết">
                </form>
            </td>
        <?php } ?>
    </tr>
    <?php } }
    else {echo '<tr><td colspan="9">Không có đơn hàng!</td></tr>';}
    ?>

</table>
  </div>
  


<?php 
if (isset($_POST['detail']) && ($_POST['don_hang_id'])) {
    $don_hang_id = $_POST['don_hang_id'];
    $order_detail = [];
    foreach ($orders_info as $order) {
        if ($order['don_hang_id'] == $don_hang_id) {
            $order_detail = $order;
        }
    }
?>
<div id="editModal" class="modal" >
    <div class="modal-content" style="width: 770px">
        <span class="close">&times;</span>
        <h2>Chi tiết hóa đơn</h2>
        <h3><?= $order_detail['ten_khach'] ?></h3>
        <h3>TRẠNG THÁI ĐƠN HÀNG |
        <span style="color: #f53d2d; text-transform: uppercase;"><?= $order_detail['trang_thai'] ?></span></h3>
        <div class="scroll-container" style="max-height: 350px; overflow-y: auto; margin-top: 5px;">
        <table>
            <tr>
                <th>Hình ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
            </tr>
            <?php foreach ($order_detail['san_pham'] as $sp) { ?>
                <tr>
                <td style="margin: 0; padding: 0;"><img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($sp['hinh_anh'], '/'), ENT_QUOTES, 'UTF-8') ?> 
                " alt="" style="width: 150px; " /></td>
                <td><?= $sp['ten_san_pham'] ?></td>
                <td><?= $sp['ten_danh_muc'] ?></td>
                <td><?= $sp['so_luong'] ?></td>
                <td><?= number_format($sp['don_gia'], 0, ',', '.') ?></td>
                </tr>
                
            <?php } ?>
        </table>
        </div>
        <hr>
        <h4>Địa chỉ: <?= $order_detail['so_nha_va_duong'] .', '. $order_detail['quan'] .', Tp.HCM' ?></h4>
        <h4>Số điện thoại: <?= htmlspecialchars(preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $order_detail['sdt'])) ?></h4>
        <h4>Thành tiền: <?= number_format($order_detail['tong_tien'], 0, ',', '.') ?> 
        <span style="font-size: 0.75em; vertical-align: super;">đ</span></h4>
    </div>
</div>
    <?php } ?>

</div>