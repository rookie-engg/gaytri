<?php
session_start();

if (isset($_SESSION['userId'])) {
  header('Location: home.php');
  die();
} ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

  <title>Login</title>
</head>

<body data-bs-theme="dark">
  <?php

  if (isset($_POST['username']) && isset($_POST['password'])) {
    require __DIR__ . DIRECTORY_SEPARATOR . 'conn.php';

    $username = $_POST["username"];
    $password = $_POST["password"];
    $query = "SELECT id FROM users WHERE username = ? AND password = ?";
    $stm = $dbConnection->conn->prepare($query);
    if ($stm === false) {
      echo "error " . $dbConnection->conn->error;
      die();
    }
    $stm->bind_param("ss", $username, $password);
    $success = $stm->execute();
    if (!$success) {
  ?>
      <div class="alert alert-danger" role="alert">Login failed database error</div>
    <?php
      die();
    }

    $results = $stm->get_result();
    $numRows = $results->num_rows;

    if ($numRows == 1) {
      while ($row = $results->fetch_assoc()) {
        $_SESSION['userId'] = $row['id'];
        // header('Location: home.php');
        echo "
        <script>
          window.location = 'home.php';
        </script>
        ";
        die();
      }
    } else {
    ?>
      <div class="alert alert-danger" role="alert">User doesn't exists</div>
  <?php
    }
  }
  ?>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-3">
      <h2 class="card-title text-center">Login</h2>
      <div class="card-body">
        <form action="index.php" method="post">
          <div class="m-3 d-flex justify-content-end gap-3">
            <label for="username">User</label>
            <input type="text" name="username" />
          </div>
          <div class="m-3 d-flex justify-content-end gap-3">
            <label for="password">Password</label>
            <input type="text" name="password" />
          </div>
          <button type="submit" class="btn btn-primary">submit</button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
