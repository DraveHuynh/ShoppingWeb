<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/cart.css">
  
  <div class="cart-view">

    <div class="scroll-container">
    <div class="cart-container">
      <h2><i class='bx bx-cart-alt'></i>Giỏ hàng</h2>
      <?php foreach ($cartItems as $item) { ?>
        <div class="cart-item">
          <img src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($item['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>" alt="" />
          <div class="item-info">
            <h4><?= $item['ten_san_pham'] ?></h4>
            <p><?= $item['ten_danh_muc'] ?></p>
            <p class="item-price"><?= number_format($item['gia'], 0, ',', '.') ?>₫</p>
            <div class="item-controls">
              <div class="quantity-group">
                <button class="btn-decrease"><i class='bx bx-minus'></i></button>
                <input type="number" class="quantity-number" min="1" value="<?= $item['quantity'] ?>" data-product-id="<?= $item['product_id'] ?>">
                <button class="btn-increase"><i class='bx bx-plus'></i></button>
              </div>
          
              <form method="post" action="<?= BASE_URL ?>/index.php?url=cart/removeCart" style="display: inline;">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <button type="submit" class="delete-button"><i class='bx bx-trash'></i></button>
              </form>
          
              <form class="status-form">
                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                <input type="checkbox" name="status" class="toggle-status" <?= $item['status'] ? 'checked' : '' ?>>
              </form>
          
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
    </div>
    
    
    <?php
      $subtotal = 0;
      // Kiểm tra giỏ hàng trong session
      if (isset($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
          if ($item['status']) {
            // Lấy giá sản phẩm từ mảng $cartItems (giả sử bạn đã có mảng $cartItems với giá trị 'gia' cho mỗi sản phẩm)
            foreach ($cartItems as $ci) {
              if ($ci['product_id'] == $item['product_id']) {
                // Cộng dồn vào subtotal
                $subtotal += $ci['gia'] * $item['quantity'];
                break;
              }
            }
          }
        }
      }
    ?>
    
    <div class="summary">
      <h3>Tóm tắt đơn hàng</h3>
      <p><span>Tạm tính</span><span id="subtotal"><?= number_format($subtotal, 0, ',', '.') ?>₫</span></p>
      <p><span>Phí vận chuyển</span><span>Miễn phí</span></p>
      <hr>
      <p><strong>Tổng cộng</strong><strong id="total"><?= number_format($subtotal, 0, ',', '.') ?>₫</strong></p>
  
      <!-- Chọn địa chỉ -->
      <label for="addressSelect">Địa chỉ giao hàng</label>
      <select id="addressSelect" name="addressSelect">
        <option value="">-- Chọn địa chỉ --</option>
        <?php foreach ($ds_dia_chi as $dc): ?>
          <option value="<?= $dc['dia_chi_id'] ?>" 
          data-address="<?= $dc['so_nha_va_duong'] ?>" 
          data-district="<?= $dc['ten_quan'] ?>" 
          data-phone="<?= $dc['sdt'] ?>">
          <?= $dc['so_nha_va_duong'] ?>, <?= $dc['ten_quan'] ?>
        </option>
        <?php endforeach; ?>
        <option value="new">Thêm địa chỉ mới</option>
      </select>
      <div class="error-msg" id="addressSelect-error"></div>
  
      <!-- Form thêm địa chỉ mới -->
      <div id="newAddressForm" style="display: none; margin-top: 10px;">
        <label for="City">Thành phố: Hồ Chí Minh</label>
        <form action="<?= BASE_URL ?>/index.php?url=cart/checkAddress" method="POST" id="checkout-form">
          <label for="district">Quận</label>
          <select id="district" name="district">
            <option value="">-- Chọn quận --</option>
            <?php foreach ($ds_quan as $quan): ?>
              <option value="<?= $quan['id'] ?>"><?= $quan['ten'] ?></option>
              <?php endforeach; ?>
          </select>
          <div class="error-msg" id="district-error"><?= $error ?? '' ?></div>
      
          <label for="street">Số nhà và tên đường</label>
          <input type="text" id="street" name="street"  placeholder="Địa chỉ nhà">
          <div class="error-msg" id="street-error"><?= $error ?? '' ?></div>
      
          <label for="phone">Số điện thoại</label>
          <input type="text" id="phone" name="phone"  placeholder="Số điện thoại">
          <div class="error-msg" id="phone-error"><?= $error ?? '' ?></div>
      
          <button type="button" id="checkout-btn">Thanh toán</button>
        </form>
      </div>
  
      <form id="selectedAddressForm" action="<?= BASE_URL ?>/index.php?url=cart/addAddress" method="POST" style="display: none;">
        <input type="hidden" name="dia_chi_id" id="hiddenDiaChiId">
        <p><strong>Địa chỉ:</strong> <span id="selectedAddress"></span></p>
        <p><strong>Số điện thoại:</strong> <span id="selectedPhone"></span></p>
        <button type="submit">Thanh toán</button>
      </form>   
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
  updateSummary();
  const debounceMap = {}; // Mỗi sản phẩm giữ một timeout riêng

  function debounce(func, delay, productId) {
    clearTimeout(debounceMap[productId]);
    debounceMap[productId] = setTimeout(func, delay);
  }

  function updateCart(productId, newQuantity) {
    fetch("<?= BASE_URL ?>/index.php?url=cart/updateCart", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
      body: JSON.stringify({
        product_id: productId,
        quantity: newQuantity,
      }),
    }).then(() => updateSummary()); // Sau khi cập nhật giỏ hàng, gọi updateSummary
  }

  const quantityInputs = document.querySelectorAll(".quantity-number");

  quantityInputs.forEach((input) => {
    const container = input.parentElement;
    const productId = input.dataset.productId;

    // Nhập trực tiếp
    input.addEventListener("input", function () {
      const newQuantity = parseInt(input.value);
      if (!isNaN(newQuantity) && newQuantity > 0) {
        debounce(() => updateCart(productId, newQuantity), 500, productId);
      }
    });

    // Nút tăng
    container
      .querySelector(".btn-increase")
      .addEventListener("click", function () {
        let value = parseInt(input.value);
        input.value = value + 1;
        debounce(() => updateCart(productId, input.value), 300, productId);
      });

    // Nút giảm
    container
      .querySelector(".btn-decrease")
      .addEventListener("click", function () {
        let value = parseInt(input.value);
        if (value > 1) {
          input.value = value - 1;
          debounce(() => updateCart(productId, input.value), 300, productId);
        }
      });
  });

  const statusCheckboxes = document.querySelectorAll(".toggle-status");

  statusCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
      const form = this.closest("form");
      const productId = form.querySelector('input[name="product_id"]').value;
      const status = this.checked ? 1 : 0;

      // Gửi yêu cầu AJAX để cập nhật trạng thái
      fetch("<?= BASE_URL ?>/index.php?url=cart/toggleStatus", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
          product_id: productId,
          status: status,
        }),
      })
        .then((response) => response.json())
        .then((data) => {
          updateSummary(); // Sau khi cập nhật trạng thái, gọi updateSummary
        })
        .catch((error) => {
          console.error("Error toggling status:", error);
        });
    });
  });

  // Cập nhật tóm tắt giỏ hàng
  function updateSummary() {
    fetch("<?= BASE_URL ?>/index.php?url=cart/getSummary", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((response) => response.json())
      .then((data) => {
        const subtotalElem = document.getElementById("subtotal");
        const totalElem = document.getElementById("total");

        if (subtotalElem && totalElem) {
          // Cập nhật lại tổng số lượng sản phẩm và tổng tiền
          subtotalElem.innerText = data.subtotal.toLocaleString();
          totalElem.innerText = data.total.toLocaleString();

          // Hiển thị thông báo giỏ hàng trống nếu không có sản phẩm nào được chọn
          const cartEmptyMessage =
            document.getElementById("cart-empty-message");
          if (data.subtotal === 0) {
            if (cartEmptyMessage) {
              cartEmptyMessage.style.display = "block"; // Hiển thị thông báo giỏ hàng trống
            }
          } else {
            if (cartEmptyMessage) {
              cartEmptyMessage.style.display = "none"; // Ẩn thông báo giỏ hàng trống
            }
          }
        } else {
          console.error("Một hoặc nhiều phần tử không tồn tại!");
        }
      })
      .catch((error) => {
        console.error("Error updating summary:", error);
      });
  }
});

document
  .getElementById("addressSelect")
  .addEventListener("change", function () {
    const selectedValue = this.value;
    const selectedOption = this.options[this.selectedIndex];
    const newAddressForm = document.getElementById("newAddressForm");
    const selectedForm = document.getElementById("selectedAddressForm");

    // Reset form hiển thị
    newAddressForm.style.display = "none";
    selectedForm.style.display = "none";

    if (!selectedValue) {
      // Không chọn gì
      return;
    }

    if (selectedValue === "new") {
      // Hiển thị form thêm mới
      newAddressForm.style.display = "block";
    } else {
      // Hiển thị địa chỉ đã chọn
      document.getElementById(
        "selectedAddress"
      ).innerText = `${selectedOption.getAttribute(
        "data-address"
      )}, ${selectedOption.getAttribute("data-district")}`;
      document.getElementById("selectedPhone").innerText =
        selectedOption.getAttribute("data-phone");
      document.getElementById("hiddenDiaChiId").value = selectedValue;
      selectedForm.style.display = "block";
    }
  });

document.getElementById("checkout-btn").addEventListener("click", function () {
  // Reset lỗi
  ["district", "street", "phone", "addressSelect"].forEach((id) => {
    const error = document.getElementById(id + "-error");
    if (error) error.innerText = "";
  });

  let valid = true;
  const addressSelectVal = document.getElementById("addressSelect").value;

  if (!addressSelectVal) {
    document.getElementById("addressSelect-error").innerText =
      "Vui lòng chọn địa chỉ hoặc thêm mới.";
    valid = false;
  }

  if (addressSelectVal === "new") {
    const district = document.getElementById("district").value.trim();
    const street = document.getElementById("street").value.trim();
    const phone = document.getElementById("phone").value.trim();

    if (!district) {
      document.getElementById("district-error").innerText =
        "Vui lòng chọn quận.";
      valid = false;
    }

    if (!street) {
      document.getElementById("street-error").innerText =
        "Vui lòng nhập địa chỉ.";
      valid = false;
    }

    if (!phone) {
      document.getElementById("phone-error").innerText =
        "Vui lòng nhập số điện thoại.";
      valid = false;
    } else if (!/^0\d{9}$/.test(phone)) {
      document.getElementById("phone-error").innerText =
        "Số điện thoại không hợp lệ.";
      valid = false;
    }

    if (valid) {
      document.getElementById("checkout-form").submit();
    }
  }
});

  </script>

</body>