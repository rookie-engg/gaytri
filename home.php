<?php include 'common/is-session-set.php' ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'common/head.php' ?>
  <title>home</title>
</head>

<body data-bs-theme="dark">
  <?php include 'common/navbar.php' ?>

  <!-- Operations -->
  <div class="row m-3">
    <div class="card">
      <div class="card-body">
      <div class="card-title display-6">Customers</div>
        <div class="d-flex">
          <div class="me-2"><a class="btn btn-primary" href="customer/add.php"><i class="bi bi-person-add"></i> Add</a></div>
          <div class="me-2"><a class="btn btn-info" href="customer/list.php"><i class="bi bi-person-lines-fill"></i> list</a></div>
        </div>
      </div>
    </div>
  </div>

  <!-- payments -->
  <div class="row m-3">
    <div class="card">
      <div class="card-body">
        <div class="card-title display-6">Payments</div>
        <div class="d-flex">
          <!-- <div class="me-2"><a class="btn btn-primary"><i class="bi bi-wallet2"></i> Renew</a></div> -->
          <div class="me-2"><a class="btn btn-danger" href="payments/pending.php"><i class="bi bi-view-list"></i> Pending</a></div>
        </div>
      </div>
    </div>
  </div>

  <!-- old records -->
  <div class="row m-3">
    <div class="card">
      <div class="card-body">
        <div class="card-title display-6">Delete Records</div>
        <form action="delete-records/show-old-records.php" method="GET">
          <div class="mb-3">
            <label for="from-date" class="form-label">Show Records with <mark>complete</mark> status</label>
            <input type="date" name="from_date" id="from-date" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-info">show</button>
        </form>
      </div>
    </div>
  </div>

  <?php include 'common/scripts.php' ?>
</body>

</html>
