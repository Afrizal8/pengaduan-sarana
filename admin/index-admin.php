<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Halaman Admin</title>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold" href="#">
        <i class="fa-solid fa-school"></i> SIPASIS -Admin
      </span>

      <div class="d-flex">
        <span class="text-white me-3">
          <i class="fa-solid fa-user"></i>
          <?= $_SESSION['admin'] ?>
        </span>
        <a href="../process/logout.php" class="btn btn-light btn-sm">
          <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="row">
      <div class="col-md-12 mb-4">
        <div class="alert alert-succes">
          <strong>Selamat Datang !</strong>
          Anda login sebagai <b>Admin</b>.
          Silahkan kelola data pengaduan lab sekolah.
        </div>
      </div>

      <!-- ========================== CARD 1 ========================== -->
      <div class="col-md-4">
        <div class="card shadow-sm mb-3">
          <div class="card-body text-center">
            <i class="fa-solid fa-comment text-primary mb-3"></i>
            <h5>Data pengaduan</h5>
            <p class="text-muted">
              Lihat dan kelola pengaduan siswa
            </p>
            <a href="data-pengaduan.php" class="btn btn-primary">
              Kelola
            </a>
          </div>
        </div>
      </div>

      <!-- ========================== CARD 1 ========================== -->
      <div class="col-md-4">
        <div class="card shadow-sm mb-3">
          <div class="card-body text-center">
            <i class="fa-solid fa-list text-primary mb-3"></i>
            <h5>Kategori</h5>
            <p class="text-muted">
              Kelola kategori sarana
            </p>
            <a href="data-kategori.php" class="btn btn-primary">
              Kelola
            </a>
          </div>
        </div>
      </div>

      <!-- ========================== CARD 3 ========================== -->
      <div class="col-md-4">
        <div class="card shadow-sm mb-3">
          <div class="card-body text-center">
            <i class="fa-solid fa-user-graduate text-primary mb-3"></i>
            <h5>Akun Siswa</h5>
            <p class="text-muted">
              Kelola akun siswa
            </p>
            <a href="data-siswa.php" class="btn btn-primary">
              Kelola
            </a>
          </div>
        </div>
      </div>

      <!-- ========================== CARD 4 ========================== -->
      <div class="col-md-4">
        <div class="card shadow-sm mb-3">
          <div class="card-body text-center">
            <i class="fa-solid fa-building-columns text-primary mb-3"></i>
            <h5>Laporan</h5>
            <p class="text-muted">
              Lihat dan kelola pengaduan siswa
            </p>
            <a href="laporan.php" class="btn btn-primary">
              Kelola
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>