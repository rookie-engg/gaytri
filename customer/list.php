<?php include '../common/is-session-set.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../common/head.php' ?>
  <title>Customer list</title>
</head>

<body data-bs-theme="dark">
  <?php include '../common/navbar.php' ?>

  <div class="container">
    <input class="form-control mt-3" type="search" placeholder="Search" aria-label="Search" id="search-bar" />

    <?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conn.php';
    $targetDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'customer-images';

    $conn = $dbConnection->conn;
    $stm = $conn->prepare("SELECT customers.cid, customers.name, customers.phone, customers.photo, payments.last_date, payments.end_date, payments.amount, payments.status FROM customers, payments  WHERE customers.cid = payments.cid");
    if ($stm->execute()) {
      $stm->bind_result($cid, $name, $phone, $photo_path, $pay_last_date, $end_date, $pay_amount, $pay_status);
    ?>
      <?php
      while ($stm->fetch()) {
        echo "
              <div class='card mt-3' data-item>
                <div class='card-body d-flex'>
                    <div class=''>
                      <table class='table table-striped'>
                        <tbody>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 align-items-center'>
                                  <label class='fw-bold'>Name:</label>
                                  <label data-name=$name>$name<label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 align-items-center'>
                                <label class='fw-bold'>Phone:</label>
                                <a href='tel:{$phone}' data-phone=$phone>$phone</a>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 align-items-center'>
                                <label class='fw-bold'>Amount:</label>
                                <label><i class='bi bi-currency-rupee'></i>$pay_amount<label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 align-items-center'>
                                <label class='fw-bold'>Status:</label>
                                <label data-status=$pay_status class='text-light p-1 rounded text-uppercase " . ($pay_status == 'complete' ? 'bg-success' : 'bg-danger') . "'>$pay_status<label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 flex-column justify-content-center'>
                                <label class='fw-bold'>Last PayDate</label>
                                <label>$pay_last_date<label>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <div class='d-flex gap-2 flex-column justify-content-center'>
                                <label class='fw-bold'>End Date</label>
                                <label data-end-date=$end_date>$end_date<label>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class='d-flex flex-column gap-3 justify-content-between h-100'>
                      <img src='../get-image.php?name={$photo_path}' class='rounded card-img-top'/>
                      <a class='btn btn-primary' href='../payments/renew.php?name={$name}&pay_amount={$pay_amount}&photo={$photo_path}&last_date={$pay_last_date}&end_date={$end_date}&cid={$cid}&pay_status={$pay_status}'><i class='bi bi-wallet2'></i> Renew Payment<a>
                    </div>
                </div>
              </div>
            ";
      }
      ?>
    <?php
    } else {
    ?>
      <div class="alert alert-danger" role="alert">Database Error</div>
    <?php
    }
    ?>
  </div>

  <?php include '../common/scripts.php' ?>
  <script>
    let searchBar = document.getElementById('search-bar');
    let cards = document.querySelectorAll('.card[data-item]');

    searchBar.addEventListener('input', (ev) => {
      let text = searchBar?.value.trim().toLowerCase();
      if (!text || text.length <= 3) {
        cards.forEach(card => card.classList.remove('d-none'));
      };
      if (!isNaN(text)) {
        cards.forEach(e => {
          let phone = e.querySelector('a[data-phone]')?.getAttribute('data-phone').trim().toLocaleLowerCase();
          if (!phone) return;
          if (phone.startsWith(text))
            e.classList.remove('d-none');
          else
            e.classList.add('d-none');
        });
        return;
      }

      // check for end date
      if (/\d/.test(text)) {
        cards.forEach(card => {
          let endDate = card.querySelector('label[data-end-date]')?.getAttribute('data-end-date').trim().toLowerCase();
          if (!endDate) return;
          if (endDate.startsWith(text))
            card.classList.remove('d-none');
          else
            card.classList.add('d-none');
        });
        return;
      }

      cards.forEach(card => {
        let name = card.querySelector('label[data-name]')?.getAttribute('data-name').trim().toLowerCase();
        let status = card.querySelector('label[data-status]')?.getAttribute('data-status').trim().toLowerCase();
        if (name?.startsWith(text) || status?.startsWith(text)) {
          card.classList.remove('d-none');
        } else {
          card.classList.add('d-none');
        }
      });
    });
  </script>
</body>

</html>
