<?php
session_start();
include "../koneksi.php";
if (!isset($_SESSION['siswa'])) {
  header("location: login-siswa.php");
  exit;
}

$siswa = $_SESSION['siswa'];
$kategori = mysqli_query(
$conn, "SELECT * FROM kategori"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css">
  <title>Buat pengaduan</title>
</head>
<body class="bg-light">
  <nav class="navbar navbar-dark bg-success">
  <div class="container-fluid">
    <a href="index-siswa.php" class="navbar-brand">
      <i class="fa-solid fa-arrow-left"></i> kembali
    </a>

    <span class="navbar-text text-white">
      <p><b>NIS:</b><?= $siswa['nis'];?> <b>Kelas:</b><?= $siswa['kelas'];?> </p>
    </span>
  </div>
  </nav>
  <div class="container mt-4">
    <div class="card shadow-sm">
      <h5 class="mb-0 mt-2"><i class="fa-solid fa-plus"></i> Form Pengaduan siswa</h5>
    <div class="card-body">
      <form method="POST" action="../process/simpan-pengaduan.php">
        <div class="mb3">
          <label class="form-label">kategori sarana :</label>
          <select name="id_kategori" class="form-select"> // required na acan
            <option value="">-- Silahkan pilih kategori sarana</option>
            <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
            <option value="<?= $k['id_kategori']; ?> ">
              <?= $k['ket_kategori'];?>
              <?php }?>
            </option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Lokasi</label>
          <input type="text" name="lokasi" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi pengaduan</label>
          <textarea name="ket" class="form-control" rows="4" required></textarea>
        </div>
        <div class="d-flex justify-content-between">
          <a href="index-siswa.php">
            <i class="fa-solid fa-arrow-left"></i> Batal</a>
            <button type="submit" name="kirim" class="btn btn-success">
        Kirim pengaduan
        </button>
        </div>
      </form>
    </div>
  </div>
</div>

      <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>