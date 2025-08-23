<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/order.css">
  <div class="container">
  <div class="content">
      <h1>Đơn hàng</h1>

      <?php foreach ($orders_info as $order) { ?>

      <div class="order-container">
        <div class="shop-header">
          <div class="right">
            TRẠNG THÁI ĐƠN HÀNG |
            <span style="color: #f53d2d; text-transform: uppercase;"><?= $order['trang_thai'] ?></span>
          </div>
        </div>
        <?php foreach ($order['san_pham'] as $sp) { ?>
        <div class="product-item">
          <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($sp['hinh_anh'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="" />
          <div class="product-info">
            <p><strong><?= $sp['ten_san_pham'] ?></strong></p>
            <p>Phân loại: <?= $sp['ten_danh_muc'] ?></p>
            <p>x<?= $sp['so_luong'] ?></p>
            <p><?= number_format($sp['don_gia'], 0, ',', '.') ?></p>
          </div>
        </div>
        
        <?php  } ?>
      
        <div class="total-section">Thành tiền: <?= number_format($order['tong_tien'], 0, ',', '.') ?> 
        <span style="font-size: 0.75em; vertical-align: super;">đ</span>
        </div>
        <div class="order-actions">
          <form method="post" action="<?= BASE_URL ?>/index.php?url=order/update">
          <select id="dia_chi_id" name="dia_chi_id" style="margin-bottom: 20px;">
        <?php 
        foreach ($ds_dia_chi as $dia_chi) { ?>
          <option value="<?= $dia_chi['dia_chi_id'] ?>" 
            <?= (isset($order['dia_chi_id']) && $order['dia_chi_id'] == $dia_chi['dia_chi_id']) ? 'selected' : '' ?>>
            <?= $dia_chi['so_nha_va_duong'] ?>, <?= $dia_chi['quan'] ?>, Tp.HCM, 
            <?= htmlspecialchars(preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $dia_chi['sdt'])) ?> 
          </option>
        <?php ;
      }?>
      </select>
            <input type="hidden" name="don_hang_id" value="<?= $order['don_hang_id'] ?>">
            <?php if ($order['trang_thai'] == "Chưa xác nhận") {?>
              <button type="submit" name="update">Cập nhật</button>
              <button type="submit" name="cancel">Hủy đơn hàng</button>
              <?php } else { 
                if ($order['trang_thai'] === 'Đã xác nhận'){
                  echo '<span style="color: green; font-weight: bold;">Đã xác nhận</span>';
                } elseif ($order['trang_thai'] === 'Đã hủy') {
                  echo ' <span style="color: #f53d2d; font-weight: bold;">Đã hủy</span>';
                }
              } ?>
          </form>
          
        </div>
      </div>

      <?php  } ?>

    </div>
  </div>
</body>

