<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

// Pagination settings
$limit = 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search and filter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';

// Build WHERE clause
$where_clauses = [];
if ($search != '') {
  $where_clauses[] = "(s.nis LIKE '%$search%' OR s.kelas LIKE '%$search%' OR k.ket_kategori LIKE '%$search%' OR ia.lokasi LIKE '%$search%' OR ia.ket LIKE '%$search%')";
}
if ($filter_status != '') {
  $where_clauses[] = "IFNULL(a.status,'Menunggu') = '$filter_status'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

// Count total records
$count_query = mysqli_query(
  $conn,
  "
  SELECT COUNT(*) as total
  FROM input_aspirasi ia
  JOIN siswa s ON ia.nis = s.nis
  JOIN kategori k ON ia.id_kategori = k.id_kategori
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  $where_sql
  "
);
$total_records = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_records / $limit);

// Get data with pagination
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
    IFNULL(a.status,'Menunggu') AS status
  FROM input_aspirasi ia
  JOIN siswa s ON ia.nis = s.nis
  JOIN kategori k ON ia.id_kategori = k.id_kategori
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  $where_sql
  ORDER BY ia.tanggal DESC
  LIMIT $start, $limit
  "
);
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
  <style>
    .table td {
      vertical-align: middle;
    }
    .ket-column {
      max-width: 200px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }
  </style>
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
      <a href="index-admin.php" class="text-white text-decoration-none me-3">
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
      <h5 class="mb-0"><i class="fa-solid fa-comment"></i> Data Pengaduan Siswa</h5>
    </div>

    <div class="card-body">
      <!-- Search and Filter Section -->
      <form method="GET" action="" class="mb-3">
        <div class="row g-2">
          <div class="col-md-5">
            <div class="input-group">
              <span class="input-group-text"><i class="fa-solid fa-search"></i></span>
              <input type="text" name="search" class="form-control" placeholder="Cari NIS, Kelas, Kategori, Lokasi..." value="<?= htmlspecialchars($search); ?>">
            </div>
          </div>
          <div class="col-md-3">
            <select name="status" class="form-select">
              <option value="">Semua Status</option>
              <option value="Menunggu" <?= ($filter_status == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
              <option value="Proses" <?= ($filter_status == 'Proses') ? 'selected' : ''; ?>>Proses</option>
              <option value="Selesai" <?= ($filter_status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
            </select>
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary">
              <i class="fa-solid fa-filter"></i> Filter
            </button>
            <a href="data-pengaduan.php" class="btn btn-secondary">
              <i class="fa-solid fa-rotate-right"></i> Reset
            </a>
          </div>
        </div>
      </form>

      <!-- Statistics -->
      <div class="alert alert-info mb-3">
        <i class="fa-solid fa-info-circle"></i> 
        Menampilkan <strong><?= mysqli_num_rows($query); ?></strong> dari <strong><?= $total_records; ?></strong> pengaduan
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
          <thead class="table-primary text-center">
            <tr>
              <th width="5%">No</th>
              <th width="12%">Tanggal</th>
              <th width="8%">NIS</th>
              <th width="8%">Kelas</th>
              <th width="12%">Kategori</th>
              <th width="12%">Lokasi</th>
              <th width="20%">Keterangan</th>
              <th width="10%">Status</th>
              <th width="13%">Aksi</th>
            </tr>
          </thead>

          <tbody>
          <?php
          if (mysqli_num_rows($query) > 0) {
            $no = $start + 1;
            while ($row = mysqli_fetch_assoc($query)) {
          ?>
            <tr>
              <td class="text-center"><?= $no++; ?></td>
              <td class="text-center">
                <small><?= date('d/m/Y', strtotime($row['tanggal'])); ?><br>
                <?= date('H:i', strtotime($row['tanggal'])); ?></small>
              </td>
              <td class="text-center"><?= htmlspecialchars($row['nis']); ?></td>
              <td class="text-center"><span class="badge bg-secondary"><?= htmlspecialchars($row['kelas']); ?></span></td>
              <td><span class="badge bg-info"><?= htmlspecialchars($row['ket_kategori']); ?></span></td>
              <td><?= htmlspecialchars($row['lokasi']); ?></td>
              <td>
                <div class="ket-column" title="<?= htmlspecialchars($row['ket']); ?>">
                  <?= htmlspecialchars($row['ket']); ?>
                </div>
              </td>
              <td class="text-center">
                <?php
                if ($row['status'] == 'Menunggu') {
                  echo '<span class="badge bg-secondary"><i class="fa-solid fa-clock"></i> Menunggu</span>';
                } elseif ($row['status'] == 'Proses') {
                  echo '<span class="badge bg-warning text-dark"><i class="fa-solid fa-spinner"></i> Proses</span>';
                } elseif ($row['status'] == 'Selesai') {
                  echo '<span class="badge bg-success"><i class="fa-solid fa-check"></i> Selesai</span>';
                }
                ?>
              </td>
              <td class="text-center">
                <div class="btn-group btn-group-sm" role="group">
                  <a href="lihat-pengaduan.php?id=<?= $row['id_pelaporan']; ?>" 
                     class="btn btn-info" 
                     title="Lihat Detail">
                    <i class="fa-solid fa-eye"></i>
                  </a>
                  <a href="../process/hapus-pengaduan.php?id=<?= $row['id_pelaporan']; ?>"
                     class="btn btn-danger"
                     title="Hapus"
                     onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
          <?php
            }
          } else {
            echo "
              <tr>
                <td colspan='9' class='text-center py-4'>
                  <i class='fa-solid fa-inbox fa-3x text-muted mb-3'></i>
                  <p class='text-muted'>Data pengaduan tidak ditemukan</p>
                </td>
              </tr>
            ";
          }
          ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <?php if ($total_pages > 1): ?>
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mb-0">
          <!-- Previous Button -->
          <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= $page - 1; ?>&search=<?= urlencode($search); ?>&status=<?= urlencode($filter_status); ?>">
              <i class="fa-solid fa-chevron-left"></i>
            </a>
          </li>

          <!-- Page Numbers -->
          <?php
          $start_page = max(1, $page - 2);
          $end_page = min($total_pages, $page + 2);
          
          for ($i = $start_page; $i <= $end_page; $i++):
          ?>
          <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
            <a class="page-link" href="?page=<?= $i; ?>&search=<?= urlencode($search); ?>&status=<?= urlencode($filter_status); ?>">
              <?= $i; ?>
            </a>
          </li>
          <?php endfor; ?>

          <!-- Next Button -->
          <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?= $page + 1; ?>&search=<?= urlencode($search); ?>&status=<?= urlencode($filter_status); ?>">
              <i class="fa-solid fa-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
      <?php endif; ?>
    </div>
  </div>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>