<?php

include "../common/is-session-set.php";
include "../conn.php";

$uploadsFolder = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'customer-images';

$conn = $dbConnection->conn;
$conn->begin_transaction();
$cid = $_GET['cid'];
$photo_path = $_GET['photo_path'];
$from_date = $_GET['from_date'];

try {
  $stm1 = $conn->prepare("DELETE FROM payments WHERE cid=?");
  $stm1->bind_param('d', $cid);
  $stm1->execute();

  $stm3 = $conn->prepare("DELETE FROM customers WHERE cid=?");
  $stm3->bind_param('d', $cid);
  $stm3->execute();

  $to_del = $uploadsFolder . DIRECTORY_SEPARATOR . $photo_path;
  echo $to_del;
  echo file_exists($to_del);
  if (file_exists($to_del)) {
    if (!unlink($to_del)) {
      throw new Exception("error while deleting");
    };
  } else {
    throw new Exception("error file exists $to_del");
  }
} catch (\Throwable $th) {
  //throw $th;
  $conn->rollback();

  echo "
  <script>
  alert('{$th->getMessage()} ❌');
  window.location = 'show-old-records.php?from_date={$from_date}';
  </script>
  ";
  die();
}

$conn->commit();

echo "
<script>  
  alert('Record Deleted ✅');
  window.location = 'show-old-records.php?from_date={$from_date}';
</script>
";
