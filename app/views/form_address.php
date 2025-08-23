<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/form_address.css">
  <div id="newAddressForm">
    <h1 class="form-title"><?=$title?></h1>

    <label for="City">Thành phố: Hồ Chí Minh</label>

    <form action="<?= BASE_URL ?>/index.php?url=address/checkAddress" method="POST">
      <label for="district">Quận</label>
      <select id="district" name="district">
        <option value="">-- Chọn quận --</option>
        <?php foreach ($ds_quan as $quan): ?>
          <option value="<?= $quan['id'] ?>" 
            <?= (isset($address['quan_id']) && $address['quan_id'] == $quan['id']) ? 'selected' : '' ?>>
            <?= $quan['ten'] ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="error-msg" id="district-error"><?= $errors['district'] ?? '' ?></div>

      <label for="street">Số nhà và tên đường</label>
      <input type="text" id="street" name="street" placeholder="Địa chỉ nhà" value="<?= $address['so_nha_va_duong'] ?? '' ?>">
      <div class="error-msg" id="street-error"><?= $errors['street'] ?? '' ?></div>

      <label for="phone">Số điện thoại</label>
      <input type="text" id="phone" name="phone" placeholder="Số điện thoại" value="<?= $address['sdt'] ?? ''  ?>">
      <div class="error-msg" id="phone-error"><?= $errors['phone'] ?? '' ?></div>
      <input type="hidden" name="dia_chi_id" value="<?= $address['id'] ?? '' ?>">

      <button type="submit" id="checkout-btn"><?=$title?></button>
    </form>
  </div>

  
</body>
