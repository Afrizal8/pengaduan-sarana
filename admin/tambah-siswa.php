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
  <title>Tambah Siswa - Admin</title>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold">
        <i class="fa-solid fa-school"></i> SIPASIS - Admin
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
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
              <i class="fa-solid fa-user-plus"></i> Tambah Data Siswa
            </h5>
          </div>
          <div class="card-body">
            <form action="../process/tambah-siswa.php" method="POST">
              <div class="mb-3">
                <label for="nis" class="form-label">
                  <i class="fa-solid fa-id-card"></i> NIS
                </label>
                <input type="text" class="form-control" id="nis" name="nis" 
                       placeholder="Masukkan NIS" required maxlength="10">
                <small class="text-muted">Maksimal 10 karakter</small>
              </div>

              <div class="mb-3">
                <label for="kelas" class="form-label">
                  <i class="fa-solid fa-door-open"></i> Kelas
                </label>
                <input type="text" class="form-control" id="kelas" name="kelas" 
                       placeholder="Contoh: X RPL 1" required maxlength="10">
                <small class="text-muted">Maksimal 10 karakter</small>
              </div>

              <div class="d-flex justify-content-between">
                <a href="data-siswa.php" class="btn btn-secondary">
                  <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                  <i class="fa-solid fa-save"></i> Simpan Data
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>