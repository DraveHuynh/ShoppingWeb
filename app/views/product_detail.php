<body>
  <link rel="stylesheet" href="<?= CSS_URL ?>/product_detail.css" />

  <div class="product-detail">
    <div class="container">
      <div class="slide">
        <div class="swiper mySwiper">
          <div class="swiper-wrapper">
            <?php foreach ($all_images as $image) { ?>
            <div class="swiper-slide">
              <img
                src="<?= htmlspecialchars(IMAGE_URL . '/' . ltrim($image['img_url'], '/'), ENT_QUOTES, 'UTF-8') ?>"
                alt=""
              />
            </div>
            <?php } ?>
          </div>
          <div class="swiper-button-next"></div>
          <div class="swiper-button-prev"></div>
        </div>
      </div>

      <div class="content">
        <h3><?= htmlspecialchars($product['ten']) ?></h3>
        <p><?= htmlspecialchars($category['ten']) ?></p>
        <h4><?= number_format($product['gia'], 0, '.', ',') ?>VNĐ</h4>
        <div class="product-options">
          <?= htmlspecialchars($product['mo_ta']) ?>
        </div>

        <!-- Thêm khu vực tăng giảm số lượng -->
        <div class="quantity-selector">
          <i class="bx bx-minus" id="decrease"></i>
          <input
            type="number"
            name="quantity"
            id="quantity"
            value="1"
            min="1"
          />
          <input type="hidden" name="product_id" value="<?= $product_id ?>" />
          <i class="bx bx-plus" id="increase"></i>
        </div>

        <?php $logged_in = isset($_SESSION['customer_id']) ? '1' : '0';?>
        <div class="buttons">
          <a href="#" class="add-to-cart" data-logged-in="<?= $logged_in ?>"
            >Add to cart</a
          >
        </div>

        <div id="login-popup">
          <p>Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.</p>
          <a href="<?= BASE_URL ?>/index.php?url=login" class="login-btn"
            >Đăng nhập</a
          >
        </div>

        <div id="overlay"></div>
      </div>
    </div>
  </div>

  <div class="related-products">
    <h2>Sản phẩm liên quan</h2>
    <div class="swiper relatedSwiper">
      <div class="swiper-wrapper">
        <?php foreach ($related_products as $item) { ?>
        <div class="swiper-slide">
          <a href="<?= BASE_URL.'/index.php?url=product_detail&product_id='.$item['id'] ?>">
            <img
              src="<?= IMAGE_URL . '/' . ltrim($item['img_url'], '/') ?>"
              alt="<?= htmlspecialchars($item['ten']) ?>"
            />
            <h4><?= htmlspecialchars($item['ten']) ?></h4>
          </a>
        </div>
        <?php } ?>
      </div>
      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>
    </div>
  </div>

  <script>
    var swiper2 = new Swiper(".relatedSwiper", {
      slidesPerView: 4,
      spaceBetween: 20,
      navigation: {
        nextEl: ".related-products .swiper-button-next",
        prevEl: ".related-products .swiper-button-prev",
      },
      breakpoints: {
        768: {
          slidesPerView: 3,
        },
        480: {
          slidesPerView: 2,
        },
        0: {
          slidesPerView: 1,
        },
      },
    });

    // Swiper initialization
    var swiper = new Swiper(".mySwiper", {
      slidesPerView: 1,
      spaceBetween: 10,
      loop: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
    });

    // Login popup logic
    document.addEventListener("DOMContentLoaded", function () {
      const addToCartBtn = document.querySelector(".add-to-cart");
      const popup = document.getElementById("login-popup");
      const overlay = document.getElementById("overlay");

      addToCartBtn.addEventListener("click", function (e) {
        e.preventDefault();
        const loggedIn = this.getAttribute("data-logged-in");

        if (loggedIn === "0") {
          // Chưa đăng nhập -> hiện popup
          popup.style.display = "block";
          overlay.style.display = "block";
        } else {
          // Đã đăng nhập -> thực hiện hành động thêm vào giỏ
          // Có thể gọi Ajax hoặc chuyển hướng
          // window.location.href = '...'; // nếu muốn chuyển
          console.log("Thêm vào giỏ hàng (đã đăng nhập)");
        }
      });

      // Tắt popup khi click vào overlay
      overlay.addEventListener("click", function () {
        popup.style.display = "none";
        overlay.style.display = "none";
      });
    });

    // Thêm/xóa sô lượng sản phẩm
    document.addEventListener("DOMContentLoaded", function () {
      // Lấy các phần tử cần thiết
      const quantityInput = document.getElementById("quantity");
      const increaseBtn = document.getElementById("increase");
      const decreaseBtn = document.getElementById("decrease");

      // Xử lý sự kiện tăng số lượng
      increaseBtn.addEventListener("click", function () {
        let currentQuantity = parseInt(quantityInput.value);
        quantityInput.value = currentQuantity + 1;
      });

      // Xử lý sự kiện giảm số lượng
      decreaseBtn.addEventListener("click", function () {
        let currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
          quantityInput.value = currentQuantity - 1;
        }
      });
    });

    document
      .querySelector(".add-to-cart")
      .addEventListener("click", function (e) {
        e.preventDefault(); // Ngừng hành động mặc định của link

        const quantity = document.querySelector("#quantity").value; // Lấy số lượng
        const productId = document.querySelector('[name="product_id"]').value; // Lấy product_id

        // Kiểm tra xem fetch có khả dụng không (tức là có JavaScript hoạt động hay không)
        if (typeof fetch !== "undefined") {
          fetch("<?= BASE_URL ?>/index.php?url=cart/addCart", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-Requested-With": "XMLHttpRequest", // Thêm header để xác định là AJAX request
            },
            body: JSON.stringify({
              quantity: quantity,
              product_id: productId,
            }),
          })
            .then((response) => response.json())
            .then((data) => {
              const notification = document.createElement("div");
              notification.classList.add("notification");

              // Cập nhật thông báo theo trạng thái
              if (data.status === "success") {
                notification.classList.add("success");
              } else {
                notification.classList.add("error");
              }

              notification.textContent = data.message;

              // Thêm thông báo vào trang
              document.body.appendChild(notification);

              // Hiển thị thông báo với hiệu ứng mờ dần và di chuyển từ dưới lên
              setTimeout(() => {
                notification.style.display = "block"; // Đảm bảo thông báo hiển thị
                notification.classList.add("show"); // Thêm lớp show để làm thông báo xuất hiện
              }, 100);

              // Ẩn thông báo sau 5 giây
              setTimeout(() => {
                notification.classList.add("hide"); // Thêm lớp hide để làm thông báo biến mất
                setTimeout(() => {
                  notification.remove(); // Loại bỏ thông báo sau khi hiệu ứng hoàn tất
                }, 5000); // Đảm bảo thời gian kết thúc hiệu ứng ẩn
              }, 2000); // Hiển thị thông báo trong 5 giây
            })
            .catch((error) => {
              console.error("Error:", error);
            });
        } else {
          // Nếu fetch không có sẵn, dùng form ẩn (fallback)
          submitFallbackForm(productId, quantity);
        }
      });

    // Hàm gửi dữ liệu qua form ẩn
    function submitFallbackForm(productId, quantity) {
      const form = document.createElement("form"); // Tạo form mới
      form.method = "POST";
      form.action = "<?= BASE_URL ?>/index.php?url=cart/addCart"; // Địa chỉ gửi dữ liệu

      // Tạo input ẩn cho product_id và quantity
      const productInput = document.createElement("input");
      productInput.type = "hidden";
      productInput.name = "product_id";
      productInput.value = productId;

      const quantityInput = document.createElement("input");
      quantityInput.type = "hidden";
      quantityInput.name = "quantity";
      quantityInput.value = quantity;

      // Thêm các input vào form
      form.appendChild(productInput);
      form.appendChild(quantityInput);

      // Gửi form
      document.body.appendChild(form); // Thêm form vào body
      form.submit(); // Gửi form
    }
  </script>
</body>
