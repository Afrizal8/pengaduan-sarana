<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

// Get all categories
$query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY id_kategori ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/fontawesome/css/all.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <title>Data Kategori</title>
</head>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">
      SIPASIS - Admin
    </span>

    <div class="d-flex">
      <span class="text-white me-3">
        <?= $_SESSION['admin'] ?>
      </span>
      <a href="index-admin.php" class="text-white text-decoration-none me-3">
        Kembali
      </a>
      <a href="../process/logout.php" class="btn btn-sm btn-danger">
        Logout
      </a>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <?php
  // Display success message
  if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $_SESSION['success'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['success']);
  }
  
  // Display error message
  if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $_SESSION['error'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['error']);
  }
  ?>

  <div class="row">
    <!-- Form Add Category -->
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Tambah Kategori Baru</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="../process/tambah-kategori.php">
            <div class="mb-3">
              <label class="form-label"><strong>Nama Kategori</strong></label>
              <input type="text" name="ket_kategori" class="form-control" placeholder="Contoh: Fasilitas Kelas" required>
            </div>
            <button type="submit" name="tambah" class="btn btn-primary w-100">
              Tambah Kategori
            </button>
          </form>
        </div>
      </div>

      <!-- Statistics -->
      <div class="card shadow-sm mt-3">
        <div class="card-body text-center">
          <h2 class="mb-1 text-primary"><?= mysqli_num_rows($query); ?></h2>
          <p class="mb-0 text-muted">Total Kategori</p>
        </div>
      </div>
    </div>

    <!-- List Categories -->
    <div class="col-md-8">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
          <h5 class="mb-0">Daftar Kategori</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
              <thead class="table-light text-center">
                <tr>
                  <th width="10%">No</th>
                  <th width="15%">ID</th>
                  <th width="50%">Nama Kategori</th>
                  <th width="25%">Aksi</th>
                </tr>
              </thead>
              <tbody>
              <?php
              if (mysqli_num_rows($query) > 0) {
                $no = 1;
                while ($row = mysqli_fetch_assoc($query)) {
              ?>
                <tr>
                  <td class="text-center"><?= $no++; ?></td>
                  <td class="text-center"><?= $row['id_kategori']; ?></td>
                  <td><?= htmlspecialchars($row['ket_kategori']); ?></td>
                  <td class="text-center">
                    <button class="btn btn-warning btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editModal<?= $row['id_kategori']; ?>">
                      Edit
                    </button>
                    <a href="../process/hapus-kategori.php?id=<?= $row['id_kategori']; ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                      Hapus
                    </a>
                  </td>
                </tr>

                <!-- Edit Modal -->
                <div class="modal fade" id="editModal<?= $row['id_kategori']; ?>" tabindex="-1">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-warning">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <form method="POST" action="../process/edit-kategori.php">
                        <div class="modal-body">
                          <input type="hidden" name="id_kategori" value="<?= $row['id_kategori']; ?>">
                          <div class="mb-3">
                            <label class="form-label"><strong>ID Kategori</strong></label>
                            <input type="text" class="form-control" value="<?= $row['id_kategori']; ?>" disabled>
                          </div>
                          <div class="mb-3">
                            <label class="form-label"><strong>Nama Kategori</strong></label>
                            <input type="text" name="ket_kategori" class="form-control" value="<?= htmlspecialchars($row['ket_kategori']); ?>" required>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" name="edit" class="btn btn-warning">Simpan Perubahan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              <?php
                }
              } else {
                echo "
                  <tr>
                    <td colspan='4' class='text-center py-4'>
                      <p class='text-muted mb-0'>Belum ada data kategori</p>
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
  </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>