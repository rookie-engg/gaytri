<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'conn.php';

include '../common/is-session-set.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $cid = $_POST['cid'];
  $amount = $_POST['amount'];
  $end_date = date('Y-m-d', strtotime($_POST['end_date']));
  $last_date = date('Y-m-d', strtotime($_POST['last_date']));
  $status = $_POST['status'];

  $conn = $dbConnection->conn;
  $stm = $conn->prepare("UPDATE payments SET `last_date`=?,`end_date`=?,`status`=?,`amount`=? WHERE cid=?");
  $stm->bind_param('sssdd', $last_date, $end_date, $status, $amount, $cid);

  if ($stm->execute()) {
    echo "
    <script>
      alert('Renewed Successfully ✅');
      window.location = '../home.php';
    </script>";
    die();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include '../common/head.php' ?>
  <title>Renew</title>
</head>

<body data-bs-theme="dark">
  <?php include '../common/navbar.php' ?>
  <div class="container">
    <div class="card">
      <img class="card-img-top img-thumbnail" style="object-fit: contain; height: 12rem;" src="<?php echo "../get-image.php?name={$_GET['photo']}"; ?>"></img>
      <div class="card-body">
        <div class="text-center">
          <label class="display-5"><?php echo $_GET['name'] ?></label>
        </div>
        <form action="renew.php" method="post" class="m-3">
          <div class="mb-3 d-none">
            <label for="cid">Customer ID</label>
            <input type="text" name="cid" value="<?php echo $_GET['cid']; ?>">
          </div>

          <div class="mb-3">
            <label for="amount" class="form-label"><i class="bi bi-currency-rupee"></i> Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $_GET['pay_amount']; ?>" required>
          </div>

          <div class="mb-3">
            <label for="lastDate">Last Pay Date</label>
            <input type="date" class="form-control" id="lastDate" name="last_date" value="<?php echo $_GET['last_date']; ?>" required />
          </div>

          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="complete" <?php if ($_GET['pay_status'] == 'complete') echo 'selected'; ?>>Complete</option>
              <option value="pending" <?php if ($_GET['pay_status'] == 'pending') echo 'selected'; ?>>Pending</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="endDate">Mess End Date</label>
            <input type="date" id="endDate" name="end_date" class="form-control" value="<?php echo $_GET['end_date']; ?>" required />
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary">submit</button>
          </div>
      </div>
    </div>
    </form>
  </div>
  <?php include '../common/scripts.php' ?>
</body>

</html>
