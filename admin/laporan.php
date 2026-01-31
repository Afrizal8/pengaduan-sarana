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
  <title>Laporan - Admin</title>
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
    <div class="row">
      <div class="col-md-12">
        <div class="card shadow-sm mb-4">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fa-solid fa-file-pdf"></i> Cetak Laporan
            </h5>
            <a href="index-admin.php" class="btn btn-light btn-sm">
              <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
          </div>
          <div class="card-body">
            <p class="text-muted mb-4">
              Pilih jenis laporan yang ingin dicetak dalam format PDF
            </p>

            <div class="row">
              <!-- Laporan Semua Pengaduan -->
              <div class="col-md-6 mb-3">
                <div class="card h-100 border">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <div class="bg-primary text-white rounded p-3 me-3">
                        <i class="fa-solid fa-file-lines fa-2x"></i>
                      </div>
                      <div>
                        <h5 class="mb-1">Laporan Semua Pengaduan</h5>
                        <small class="text-muted">Semua data pengaduan</small>
                      </div>
                    </div>
                    <a href="../process/cetak-laporan-semua.php" target="_blank" class="btn btn-primary w-100 mt-5">
                      <i class="fa-solid fa-print"></i> Cetak Laporan
                    </a>
                  </div>
                </div>
              </div>

              <!-- Laporan Per Status -->
              <div class="col-md-6 mb-3">
                <div class="card h-100 border">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                      <div class="bg-success text-white rounded p-3 me-3">
                        <i class="fa-solid fa-filter fa-2x"></i>
                      </div>
                      <div>
                        <h5 class="mb-1">Laporan Per Status</h5>
                        <small class="text-muted">Filter berdasarkan status</small>
                      </div>
                    </div>
                    <form action="../process/cetak-laporan-status.php" method="GET" target="_blank">
                      <select name="status" class="form-select mb-2" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Menunggu">Menunggu</option>
                        <option value="Proses">Proses</option>
                        <option value="Selesai">Selesai</option>
                      </select>
                      <button type="submit" class="btn btn-success w-100">
                        <i class="fa-solid fa-print"></i> Cetak Laporan
                      </button>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>