<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/address.css">
    <div class="container">
    <div class="addresspage">
      <h1>Danh sách địa chỉ</h1>
      <button class="btn-add"><a href="<?= BASE_URL ?>/index.php?url=address/form">Thêm địa chỉ</a></button>

      <div class="address-grid">

        <?php foreach ($addresses as $address) { ?>
          <div class="address-card">
          <p><?= htmlspecialchars($address['so_nha_va_duong']) ?>, <?= htmlspecialchars($address['quan']) ?>, TP.HCM</p>
          <p><?= htmlspecialchars(preg_replace('/(\d{4})(\d{3})(\d{3})/', '$1 $2 $3', $address['sdt'])) ?></p>
          
          <div class="card-actions">
            <form method="POST" action="<?= BASE_URL ?>/index.php?url=address/action">
              <input type="hidden" name="dia_chi_id" value="<?= htmlspecialchars($address['dia_chi_id']) ?>">
              <button class="btn-edit" name="update"><i class="bx bx-pencil"></i>Sửa</button>
              <span class="divider">|</span>
              <button class="btn-delete" name="remove"><i class="bx bx-trash"></i>Xóa</button>
            </form>
          </div>
          
        </div>
        <?php } ?>

      </div>
    </div>
    </div>
    
  </body>

