<?php include '../common/is-session-set.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../common/head.php' ?>
  <title>Add Customer</title>
</head>

<body data-bs-theme="dark">
  <?php include '../common/navbar.php' ?>
  <div class="container">
    <div class="row m-3">
      <?php

      if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $customerName = strtolower($_POST['customerName']);
        $phoneNumber = strtolower($_POST['phoneNumber']);
        $amount = strtolower($_POST['amount']);
        $status = strtolower($_POST['status']);
        $end_date = strtolower($_POST['end_date']);

        if (
          (!isset($customerName)) ||
          (!isset($phoneNumber)) ||
          (!$_FILES['photo']['error'] == 0) ||
          (!isset($amount)) ||
          (!isset($status))
        ) {
      ?>
          <div class="alert alert-danger" role="alert">Please Fill all the fields</div>
          <?php
        } else {
          require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conn.php';

          $targetDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'customer-images';

          $originalFileName = basename($_FILES["photo"]["name"]);
          $imageFileType = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
          $uniqueFileName = time() . '_' . uniqid() . '_' . '.' . $imageFileType;
          $sourceImage = $_FILES["photo"]["tmp_name"];
          $compressedImage = imagecreatefromjpeg($sourceImage);

          if ($compressedImage == false) {
          ?>
            <div class="alert alert-danger" role="alert">Photo Error</div>
      <?php
            die();
          } else {

            $conn = $dbConnection->conn;

            $conn->begin_transaction();
            try {
              $stm = $conn->prepare("INSERT INTO customers(`name`, `phone`, `photo`) VALUES (?, ?, ?)");
              $stm->bind_param('sss', $customerName, $phoneNumber, $uniqueFileName);
              if ($stm->execute()) {
                $last_id = $conn->insert_id;
                $stm2 = $conn->prepare("INSERT INTO payments(`cid`, `last_date`, `end_date`, `amount`, `status`) VALUES (?,NOW(),?,?,?)");
                $stm2->bind_param('dsds', $last_id, $end_date, $amount, $status);
                if ($stm2->execute()) {
                  imagejpeg($compressedImage, $targetDir . DIRECTORY_SEPARATOR . $uniqueFileName);
                  imagedestroy($compressedImage);
                  $conn->commit();
                  echo "
                  <script>
                    alert('Successfully Added ✅');
                    window.location = '../home.php';
                  </script>";
                  die();
                } else {
                  $conn->rollback();
                }
              } else {
                echo "<div class='alert alert-danger'>Phone Number Already Exists ❌!</div>";
                $conn->rollback();
              }
            } catch (\Throwable $th) {
              $conn->rollback();
              echo "<div class='alert alert-danger' role='alert'>Failed ❌!</div>";
            }
          }
        }
      }
      ?>
      <form action="add.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
          <label for="customerName" class="form-label">Customer Name</label>
          <input type="text" class="form-control" id="customerName" name="customerName" maxlength="50" required>
        </div>

        <div class="mb-3">
          <label for="phoneNumber" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" minlength="10" maxlength="10" required>
        </div>

        <div class="mb-3">
          <label for="photo" class="form-label">Photo</label>
          <input type="file" class="form-control" id="photo" name="photo" accept=".jpg, .jpeg" required>
        </div>

        <div class="mb-3">
          <label for="amount" class="form-label"><i class="bi bi-currency-rupee"></i> Amount</label>
          <input type="number" class="form-control" id="amount" name="amount" value="2600" required>
        </div>

        <div class="mb-3">
          <label for="end_date">Mess End Date</label>
          <input type="date" id="end_date" class="form-control" name="end_date" required />
        </div>

        <div class="mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" id="status" name="status" required>
            <option value="complete" selected>Complete</option>
            <option value="pending">Pending</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
  <?php include '../common/scripts.php' ?>
</body>

</html>
