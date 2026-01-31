<?php
session_start();

include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

if (!isset($_GET['id'])) {
  header("location: data-pengaduan.php");
  exit;
}

$id = $_GET['id'];

$query = mysqli_query(
  $conn,
  "
  SELECT
    ia.id_pelaporan,
    ia.id_kategori,
    s.nis,
    s.kelas,
    k.ket_kategori,
    ia.lokasi,
    ia.ket,
    IFNULL(a.status, 'Menunggu') AS status,
    IFNULL(a.feedback, '') AS feedback
  FROM input_aspirasi ia
  JOIN siswa s ON ia.nis = s.nis
  JOIN kategori k ON ia.id_kategori = k.id_kategori
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  WHERE ia.id_pelaporan = '$id'
  "
);

$data = mysqli_fetch_assoc($query);

if (!$data) {
  header("location: data-pengaduan.php");
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
  <title>Detail Pengaduan</title>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">
      <i class="fa-solid fa-school"></i> SIPASIS - Admin
    </span>

    <div class="d-flex">
      <span class="text-white me-3">
        <i class="fa-solid fa-user"></i> <?= $_SESSION['admin'] ?>
      </span>
      <a href="data-pengaduan.php" class="text-white me-3">
        <i class="fa-solid fa-arrow-left"></i> Kembali
      </a>
      <a href="../process/logout.php" class="btn btn-sm btn-danger">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php
  // Display success message
  if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle"></i> ' . $_SESSION['success'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['success']);
  }
  
  // Display error message
  if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-exclamation-circle"></i> ' . $_SESSION['error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['error']);
  }
  ?>

  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="fa-solid fa-file-lines"></i> Detail Pengaduan</h5>
    </div>

    <div class="card-body">
      <h6 class="mb-3 text-primary"><i class="fa-solid fa-info-circle"></i> Informasi Pelapor</h6>
      <table class="table table-bordered">
        <tr>
          <th width="30%">NIS</th>
          <td><?= $data['nis']; ?></td>
        </tr>
        <tr>
          <th>Kelas</th>
          <td><?= $data['kelas']; ?></td>
        </tr>
        <tr>
          <th>Kategori</th>
          <td><span class="badge bg-info"><?= $data['ket_kategori']; ?></span></td>
        </tr>
        <tr>
          <th>Lokasi</th>
          <td><?= $data['lokasi']; ?></td>
        </tr>
        <tr>
          <th>Keterangan</th>
          <td><?= nl2br(htmlspecialchars($data['ket'])); ?></td>
        </tr>
      </table>

      <hr class="my-4">

      <h6 class="mb-3 text-primary"><i class="fa-solid fa-pen-to-square"></i> Update Status & Feedback</h6>
      <form method="post" action="update-pengaduan.php" id="updateForm">
        <input type="hidden" name="id_pelaporan" value="<?= $data['id_pelaporan']; ?>">

        <div class="mb-3">
          <label class="form-label"><strong>Status Pengaduan <span class="text-danger">*</span></strong></label>
          <select name="status" class="form-select" required>
            <option value="Menunggu" <?= ($data['status'] == 'Menunggu') ? 'selected' : ''; ?>>
              <i class="fa-solid fa-clock"></i> Menunggu
            </option>
            <option value="Proses" <?= ($data['status'] == 'Proses') ? 'selected' : ''; ?>>
              <i class="fa-solid fa-spinner"></i> Proses
            </option>
            <option value="Selesai" <?= ($data['status'] == 'Selesai') ? 'selected' : ''; ?>>
              <i class="fa-solid fa-check"></i> Selesai
            </option>
          </select>
          <small class="text-muted">Pilih status pengaduan saat ini</small>
        </div>

        <div class="mb-3">
          <label class="form-label"><strong>Feedback</strong></label>
          <textarea name="feedback" class="form-control" rows="4" placeholder="Masukkan tanggapan atau feedback untuk pengaduan ini..."><?= htmlspecialchars($data['feedback']); ?></textarea>
          <small class="text-muted">Berikan tanggapan atau update terkait pengaduan</small>
        </div>

        <div class="d-flex justify-content-between">
          <a href="data-pengaduan.php" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
          </a>
          <button type="submit" class="btn btn-success" name="simpan">
            <i class="fa-solid fa-save"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  // Form validation
  document.getElementById('updateForm').addEventListener('submit', function(e) {
    const status = this.querySelector('[name="status"]').value;
    
    if (!status) {
      e.preventDefault();
      alert('Status pengaduan harus dipilih!');
      return false;
    }
  });
</script>
</body>
</html>