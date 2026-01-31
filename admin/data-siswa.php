<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

// Ambil data siswa dari database
$query = "SELECT * FROM siswa ORDER BY nis ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Data Siswa - Admin</title>
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
        <div class="card shadow-sm">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
              <i class="fa-solid fa-user-graduate"></i> Data Akun Siswa
            </h5>
            <div>
              <a href="index-admin.php" class="btn btn-light btn-sm me-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali
              </a>
              <a href="tambah-siswa.php" class="btn btn-success btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah Siswa
              </a>
            </div>
          </div>
          <div class="card-body">
            <?php if(isset($_GET['pesan'])): ?>
              <?php if($_GET['pesan'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-check-circle"></i> Data siswa berhasil disimpan!
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php elseif($_GET['pesan'] == 'update'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-check-circle"></i> Data siswa berhasil diupdate!
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php elseif($_GET['pesan'] == 'hapus'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-check-circle"></i> Data siswa berhasil dihapus!
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php elseif($_GET['pesan'] == 'error'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="fa-solid fa-exclamation-circle"></i> Terjadi kesalahan!
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
              <?php endif; ?>
            <?php endif; ?>

            <div class="table-responsive">
              <table class="table table-hover table-bordered">
                <thead class="table-light">
                  <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="15%">NIS</th>
                    <th width="50%">Kelas</th>
                    <th width="15%" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  if(mysqli_num_rows($result) > 0):
                    $no = 1;
                    while($data = mysqli_fetch_assoc($result)): 
                  ?>
                    <tr>
                      <td class="text-center"><?= $no++ ?></td>
                      <td><?= htmlspecialchars($data['nis']) ?></td>
                      <td><?= htmlspecialchars($data['kelas']) ?></td>
                      <td class="text-center">
                        <a href="lihat-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                          <i class="fa-solid fa-eye"></i>
                        </a>
                        <a href="edit-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-warning btn-sm" title="Edit">
                          <i class="fa-solid fa-edit"></i>
                        </a>
                        <a href="../process/hapus-siswa.php?nis=<?= $data['nis'] ?>" class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin ingin menghapus data siswa ini?')" title="Hapus">
                          <i class="fa-solid fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php 
                    endwhile;
                  else:
                  ?>
                    <tr>
                      <td colspan="4" class="text-center text-muted">
                        <i class="fa-solid fa-inbox"></i> Tidak ada data siswa
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>