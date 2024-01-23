<?php include '../common/is-session-set.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../common/head.php' ?>
  <title>Pending Payments</title>
</head>

<body data-bs-theme="dark">
  <?php include '../common/navbar.php' ?>

  <div class="container">
    <?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conn.php';
    $conn = $dbConnection->conn;

    $stm = $conn->prepare("SELECT customers.cid, customers.name, customers.phone, customers.photo, payments.last_date, payments.end_date, payments.amount, payments.status FROM customers, payments  WHERE customers.cid = payments.cid AND payments.status=\"pending\"");
    $stm->bind_result($cid, $name, $phone, $photo_path, $pay_last_date, $end_date, $pay_amount, $pay_status);
    if ($stm->execute()) {
      $stm->store_result();
      if ($stm->num_rows > 0){
        while ($stm->fetch()) {
          echo "
          <div class='card m-3 d-flex flex-row' data-item>
            <div class='card-body'>
              <div class='d-flex mb-3 gap-2 align-items-center'>
                <label class='fw-bold'>Name:</label>
                <label data-name=$name>$name<label>
              </div>
              <div class='d-flex mb-3 gap-2 align-items-center'>
                <label class='fw-bold'>Phone:</label>
                <a href='tel:{$phone}' data-phone=$phone>$phone</a>
              </div>
              <div class='d-flex mb-3 gap-2 align-items-center'>
                <label class='fw-bold'>Amount:</label>
                <label><i class='bi bi-currency-rupee'></i>$pay_amount<label>
              </div>
              <div class='d-flex mb-3 gap-2 align-items-center'>
                <label class='fw-bold'>Status:</label>
                <label data-status=$pay_status class='text-light p-1 rounded " . ($pay_status == ' complete' ? 'bg-success' : 'bg-danger') . "'>$pay_status<label>
                    </div>
                    <div class='d-flex mb-2 gap-2 flex-column justify-content-center'>
                        <label class='fw-bold'>Last PayDate</label>
                        <label>$pay_last_date<label>
                    </div>

                    <div class='d-flex gap-2 flex-column justify-content-center'>
                        <label class='fw-bold'>End Date</label>
                        <label data-end-date=$end_date>$end_date<label>
                    </div>
                  </div>
                  <div class='d-flex flex-column gap-3 justify-content-between'>
                    <img src='../get-image.php?name={$photo_path}' class='img-thumbnail rounded col' style='object-fit:contain; width: 10rem;'/>
                    <a class='btn btn-primary' href='../payments/renew.php?name={$name}&pay_amount={$pay_amount}&photo={$photo_path}&last_date={$pay_last_date}&end_date={$end_date}&cid={$cid}&pay_status={$pay_status}'><i class='bi bi-wallet2'></i> Renew Payment<a>
                  </div>
                </div>
          ";
        }
      } else {
        echo "<h1 class='display-4 text-center mt-3'>No Pyament Pending. 😊</h1>";
      }
    }
    ?>
  </div>

  <?php include '../common/scripts.php' ?>
</body>

</html>
