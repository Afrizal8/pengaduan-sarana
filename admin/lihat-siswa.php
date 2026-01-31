<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

// Ambil NIS dari URL
if(!isset($_GET['nis'])) {
  header("location: data-siswa.php");
  exit;
}

$nis = $_GET['nis'];

// Ambil data siswa berdasarkan NIS
$query = "SELECT * FROM siswa WHERE nis = '$nis'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
  header("location: data-siswa.php");
  exit;
}

$data = mysqli_fetch_assoc($result);

// Hitung jumlah pengaduan siswa ini
$query_pengaduan = "SELECT COUNT(*) as total FROM input_aspirasi WHERE nis = '$nis'";
$result_pengaduan = mysqli_query($conn, $query_pengaduan);
$total_pengaduan = mysqli_fetch_assoc($result_pengaduan)['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Detail Siswa - Admin</title>
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
              <i class="fa-solid fa-user-circle"></i> Detail Data Siswa
            </h5>
          </div>
          <div class="card-body">
            <table class="table table-borderless">
              <tr>
                <td width="30%"><i class="fa-solid fa-id-card"></i> <strong>NIS</strong></td>
                <td width="5%">:</td>
                <td><?= htmlspecialchars($data['nis']) ?></td>
              </tr>
              <tr>
                <td><i class="fa-solid fa-door-open"></i> <strong>Kelas</strong></td>
                <td>:</td>
                <td><?= htmlspecialchars($data['kelas']) ?></td>
              </tr>
              <tr>
                <td><i class="fa-solid fa-comment"></i> <strong>Total Pengaduan</strong></td>
                <td>:</td>
                <td>
                  <span class="badge bg-info"><?= $total_pengaduan ?> Pengaduan</span>
                </td>
              </tr>
            </table>

            <div class="d-flex justify-content-between mt-4">
              <a href="data-siswa.php" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Kembali
              </a>
              <div>
                <a href="edit-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-warning">
                  <i class="fa-solid fa-edit"></i> Edit
                </a>
                <a href="../process/hapus-siswa.php?nis=<?= $data['nis'] ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Yakin ingin menghapus data siswa ini?')">
                  <i class="fa-solid fa-trash"></i> Hapus
                </a>
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