<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['siswa'])) {
  header("location: login-siswa.php");
  exit;
}
$siswa = $_SESSION['siswa'];
$nis = $siswa['nis'];

// Get statistics
$count_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi WHERE nis = '$nis'"))['total'];
$count_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.nis = '$nis' AND IFNULL(a.status,'Menunggu') = 'Menunggu'"))['total'];
$count_proses = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.nis = '$nis' AND IFNULL(a.status,'Menunggu') = 'Proses'"))['total'];
$count_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.nis = '$nis' AND IFNULL(a.status,'Menunggu') = 'Selesai'"))['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Dashboard Siswa - SIPASIS</title>
  <style>
    .stat-card {
      border: none;
      border-radius: 10px;
      transition: all 0.3s ease;
    }
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .action-btn {
      border: none;
      border-radius: 10px;
      padding: 20px;
      transition: all 0.3s ease;
      text-decoration: none;
      display: block;
    }
    .action-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-success shadow">
    <div class="container-fluid">
      <span class="navbar-brand fw-bold">
        SIPASIS - Siswa
      </span>
      <div class="d-flex align-items-center">
        <span class="text-white me-3">
          <?= $siswa['nis']; ?> | <?= $siswa['kelas']; ?>
        </span>
        <a href="../process/logout-siswa.php" class="btn btn-sm btn-light">
          Logout
        </a>
      </div>
    </div>
  </nav>

  <div class="container mt-4 mb-5">
    <?php
    // Display success message
    if (isset($_SESSION['success'])) {
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
              ' . $_SESSION['success'] . '
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
      unset($_SESSION['success']);
    }
    ?>

    <!-- Welcome Card -->
    <div class="card shadow-sm mb-4">
      <div class="card-body p-4">
        <h4 class="mb-1">Selamat Datang</h4>
        <p class="text-muted mb-0">NIS: <strong><?= $siswa['nis']; ?></strong> | Kelas: <strong><?= $siswa['kelas']; ?></strong></p>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
      <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card shadow-sm">
          <div class="card-body text-center">
            <h2 class="mb-1 text-primary"><?= $count_total; ?></h2>
            <p class="mb-0 text-muted">Total Pengaduan</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card shadow-sm">
          <div class="card-body text-center">
            <h2 class="mb-1 text-secondary"><?= $count_menunggu; ?></h2>
            <p class="mb-0 text-muted">Menunggu</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card shadow-sm">
          <div class="card-body text-center">
            <h2 class="mb-1 text-warning"><?= $count_proses; ?></h2>
            <p class="mb-0 text-muted">Diproses</p>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-6 mb-3">
        <div class="card stat-card shadow-sm">
          <div class="card-body text-center">
            <h2 class="mb-1 text-success"><?= $count_selesai; ?></h2>
            <p class="mb-0 text-muted">Selesai</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
      <div class="col-md-6 mb-3">
        <a href="input-pengaduan.php" class="action-btn bg-success text-white shadow-sm">
          <h5 class="mb-2">Buat Pengaduan Baru</h5>
          <p class="mb-0 opacity-75">Laporkan masalah sarana dan prasarana</p>
        </a>
      </div>

      <div class="col-md-6 mb-3">
        <a href="riwayat-pengaduan.php" class="action-btn bg-info text-white shadow-sm">
          <h5 class="mb-2">Riwayat Pengaduan</h5>
          <p class="mb-0 opacity-75">Lihat semua pengaduan yang pernah dibuat</p>
        </a>
      </div>
    </div>

    <!-- Information -->
    <div class="alert alert-info mt-4" role="alert">
      <strong>Informasi:</strong> Gunakan sistem ini untuk melaporkan masalah terkait sarana dan prasarana sekolah. Admin akan segera menindaklanjuti pengaduan Anda.
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>