<?php include "../common/is-session-set.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include "../common/head.php" ?>
  <title>Show Old Records</title>
</head>

<body data-bs-theme="dark">
  <?php include "../common/navbar.php" ?>

  <div class="container">
    <?php
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conn.php';
    $from_date_ori = $_GET['from_date'];
    $from_date = date('Y-m-d', strtotime($from_date_ori));

    $conn = $dbConnection->conn;
    $stm = $conn->prepare("SELECT customers.cid, customers.name, customers.phone, customers.photo, payments.last_date, payments.end_date, payments.amount, payments.status FROM customers, payments  WHERE customers.cid = payments.cid AND payments.status=\"complete\" AND payments.end_date <= ?");
    $stm->bind_param('s', $from_date);
    if ($stm->execute()) {
      $stm->bind_result($cid, $name, $phone, $photo_path, $pay_last_date, $end_date, $pay_amount, $pay_status);
      $stm->store_result();
      if ($stm->num_rows() < 1) {
        echo "
        <div class='display-4 text-center  mt-3'>No Records 😊</div>
        ";
        die();
      }
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
                      <label data-status=$pay_status class='text-light p-1 rounded " . ($pay_status == 'complete' ? 'bg-success' : 'bg-danger') . "'>$pay_status<label>
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
                  <img src='../get-image.php?name={$photo_path}' class='img-thumbnail rounded col' style='object-fit:contain;'/>
                  <a class='btn btn-danger' href='delete.php?cid={$cid}&from_date={$from_date_ori}&photo_path={$photo_path}'><i class='bi bi-trash'></i> Delete<a>
                </div>
              </div>
            ";
      }
    } else {
      echo "<div class='alert alert-danger'>Database Error</div>";
    }
    ?>
  </div>

  <?php include "../common/scripts.php" ?>
</body>

</html>
