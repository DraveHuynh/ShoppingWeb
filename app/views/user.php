<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
  }

  .content {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #eee;
  }

  .container {
    position: relative;
    width: 900px;
    height: 450px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 0 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
  }

  .form-box {
    position: absolute;
    width: 100%;
    height: 100vh;
    background: #fff;
    align-items: center;
    color: #333;
    padding: 40px;
    z-index: 1;
    transition: 0.6s ease-in-out 1.2s, visibility 0s 1s;
  }

  form {
    width: 100%;
  }

  .container h1 {
    font-size: 30px;
    padding-top: 25px;
  }

  .input-box {
    position: relative;
    margin: 30px 0;
  }

  .input-box input {
    width: 100%;
    padding: 13px 50px 13px 20px;
    background: #eee;
    border-radius: 8px;
    border: none;
    outline: none;
    font-size: 16px;
  }

  .input-box input::placeholder {
    color: #888;
    font-weight: 400;
  }

  .input-box i {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #888;
  }

  .btn {
    width: 20%;
    height: 48px;
    background: #333;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border: none;
    cursor: pointer;
    font-size: 16px;
    color: #fff;
    font-weight: 600;
  }

  .btn:hover {
    background: #c5a230;
  }

  .btn-group {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
}

  .container p {
    font-size: 14.5px;
    margin: 15px 0;
  }

  @media screen and (max-width: 650px) {
    .container {
      height: calc(100vh - 200px);
    }

    .form-box {
      bottom: 0;
      width: 100%;
      height: 100%;
    }
    .btn {
      min-width: 30%;
      margin-bottom: 10px;
    }
  }

  @media screen and (max-width: 400px) {
    .form-box {
      padding: 20px;
    }
    .btn {
      min-width: 40%;
    }
  }


</style>

<body>
  <div class="user">
    <div class="content">
      <div class="container">
        <div class="form-box">
          <form method="post" action="<?= BASE_URL ?>/index.php?url=user/update">
            <h1>Thông tin người dùng</h1>
            <div class="input-box">
              <input type="text" name="username" placeholder="Họ và tên"  value="<?= $customer['username'] ?>"/>
              <i><ion-icon name="person"></ion-icon></i>
            </div>

            <div class="input-box">
              <input type="text" name="email" placeholder="Email" readonly value="<?= $customer['email'] ?>" />
              <i class="bx bxs-envelope"></i>
            </div>

            <div class="input-box">
              <input type="text" name="phone" placeholder="Số điện thoại" value="<?= $customer['phone_number'] ?>"/>
              <i class='bx bxs-phone'></i>
            </div>

            <input type="submit" name="update" value="Cập nhật" class="btn" />
            
          </form> 
        </div>
      </div>
    </div>
  </div>
</body>

