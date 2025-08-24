<div class="page">
  <h1>Quản lý thông tin khách hàng</h1>
  <div class="category-container">
    <table>
    <tr class="title">
    <th>ID</th>
    <th>Tên khách hàng</th>
    <th>Email</th>
    <th>Số điện thoại</th>
    </tr>
    <?php
      $customers = getAllCustomers();
      if (!empty($customers)) {
        foreach ($customers as $customer) {
          echo '
            <tr>
              <td>' . htmlspecialchars($customer['id']) . '</td>
              <td>' . htmlspecialchars($customer['username']) . '</td>
              <td>' . htmlspecialchars($customer['email']) . '</td>
              <td>' . htmlspecialchars($customer['phone_number']) . '</td>
            </tr>
            ';
          }
        } else {echo '<tr><td colspan="9">Không có khách hàng!</td></tr>';}
        ?>
    </table>
    </div>