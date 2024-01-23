<nav class="navbar bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" onclick="window.location = window.location.origin + '/home.php'">
      <img src="https://getbootstrap.com//docs/5.3/assets/brand/bootstrap-logo.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
      Gaytri
    </a>
    <?php echo date("l") . ", " . date('d-m-y') ?>
    <a onclick="window.location = window.location.origin + '/logout.php'" class="btn btn-info">logout</a>
  </div>
</nav>
