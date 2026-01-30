<?php
session_start();
if(!isset($_SESSION['siswa'])) {
  header("location: ../login-siswa.php");
  exit;
}
$siswa = $_SESSION['siswa'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Halaman Siswa</title>
</head>
<body class="body-light">
  <nav class="navbar navbar-dark bg-success">
    <div class="container-fluid">
      <span class="navbar-brand">
        <i class="fa-solid fa-graduation-cap"></i> SIPASIS - siswa
      </span>
      <a href="../process/logout-siswa.php" class="btn btn-sm btn-light">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
              </a>
    </div>
  </nav>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>