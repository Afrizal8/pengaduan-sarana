<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Data Pengaduan</title>
</head>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold" href="#">
      <i class="fa-solid fa-school"></i> SIPASIS -Admin
    </span>

    <div class="d-flex">
      <span class="text-white me-3">
        <i class="fa-solid fa-user"></i> <?= $_SESSION['admin'] ?>
      </span>
      <a href="index-admin.php">
        <i class="fa-solid fa-arrow-left"></i>
      </a>
      <a href="../process/logout.php" class="btn btn-sm btn-danger">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5><i class="fa-solid fa-comment"></i> Data Pengaduan Siswa</h5>
    </div>

    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
          <thead class="table-light text-center">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>NIS</th>
              <th>Kelas</th>
              <th>Kategori</th>
              <th>Lokasi</th>
              <th>Keterangan</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
          <?php
          $no = 1;
          $query = mysqli_query(
            $conn,
            "
            SELECT
              ia.id_pelaporan,
              ia.tanggal,
              s.nis,
              s.kelas,
              k.ket_kategori,
              ia.lokasi,
              ia.ket,
              IFNULL(a.status,'menunggu') AS status
            FROM input_aspirasi ia
            JOIN siswa s ON ia.nis = s.nis
            JOIN kategori k ON ia.id_kategori = k.id_kategori
            LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
            ORDER BY ia.tanggal DESC
            "
          );

          if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
          ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center"><?= date('d-m-y H:i', strtotime($row['tanggal'])); ?></td>
              <td class="text-center"><?= $row['nis']; ?></td>
              <td class="text-center"><?= $row['kelas']; ?></td>
              <td><?= $row['ket_kategori']; ?></td>
              <td><?= $row['lokasi']; ?></td>
              <td><?= $row['ket']; ?></td>
              <td class="text-center">
                <?php
                if ($row['status'] == 'menunggu') {
                  echo '<span class="badge bg-secondary">Menunggu</span>';
                } elseif ($row['status'] == 'proses') {
                  echo '<span class="badge bg-warning">Proses</span>';
                } elseif ($row['status'] == 'selesai') {
                  echo '<span class="badge bg-success">Selesai</span>';
                }
                ?>
              </td>
              <td class="text-center">
                <a href="lihat-pengaduan.php?id=<?= $row['id_pelaporan']; ?>" class="btn btn-info btn-sm">
                  <i class="fa-solid fa-eye"></i> Lihat
                </a>
                <a href="../process/hapus-pengaduan.php?id=<?= $row['id_pelaporan']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Apakah anda yakin ingin menghapus pengaduan ini?')">
                  <i class="fa-solid fa-trash"></i> Hapus
                </a>
              </td>

            </tr>
          <?php
            }
          } else {
            echo "
              <tr>
                <td colspan='7' class='text-center'>
                  Data belum tersedia
                </td>
              </tr>
            ";
          }
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>