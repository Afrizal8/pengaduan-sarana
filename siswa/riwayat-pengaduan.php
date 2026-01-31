<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['siswa'])) {
  header("location: login-siswa.php");
  exit;
}

$siswa = $_SESSION['siswa'];
$nis = $siswa['nis'];

// Get student data
$student_query = mysqli_query($conn, "SELECT * FROM siswa WHERE nis = '$nis'");
$student = mysqli_fetch_assoc($student_query);

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Filter by status
$filter_status = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : '';
$where_status = $filter_status != '' ? "AND IFNULL(a.status,'Menunggu') = '$filter_status'" : "";

// Count total records
$count_query = mysqli_query(
  $conn,
  "
  SELECT COUNT(*) as total
  FROM input_aspirasi ia
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  WHERE ia.nis = '$nis'
  $where_status
  "
);
$total_records = mysqli_fetch_assoc($count_query)['total'];
$total_pages = ceil($total_records / $limit);

// Get complaint history
$query = mysqli_query(
  $conn,
  "
  SELECT
    ia.id_pelaporan,
    ia.tanggal,
    k.ket_kategori,
    ia.lokasi,
    ia.ket,
    IFNULL(a.status, 'Menunggu') AS status,
    IFNULL(a.feedback, '') AS feedback
  FROM input_aspirasi ia
  JOIN kategori k ON ia.id_kategori = k.id_kategori
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  WHERE ia.nis = '$nis'
  $where_status
  ORDER BY ia.tanggal DESC
  LIMIT $start, $limit
  "
);

// Count by status
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
  <title>Riwayat Pengaduan</title>
  <style>
    .card-hover:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }
    .pengaduan-card {
      border-left: 4px solid #dee2e6;
    }
    .pengaduan-card.status-menunggu {
      border-left-color: #6c757d;
    }
    .pengaduan-card.status-proses {
      border-left-color: #ffc107;
    }
    .pengaduan-card.status-selesai {
      border-left-color: #198754;
    }
  </style>
</head>

<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-success">
  <div class="container-fluid">
    <span class="navbar-brand fw-bold">
      SIPASIS - Siswa
    </span>

    <div class="d-flex align-items-center">
      <span class="text-white me-3">
        <?= $student['nis']; ?> | <?= $student['kelas']; ?>
      </span>
      <a href="index-siswa.php" class="text-white text-decoration-none me-3">
        Kembali
      </a>
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

  <!-- Header -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h4 class="mb-1">Riwayat Pengaduan</h4>
          <p class="text-muted mb-0">Total <strong><?= $total_records; ?></strong> pengaduan</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a href="input-pengaduan.php" class="btn btn-success">
            Buat Pengaduan Baru
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Statistics -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h2 class="mb-1 text-secondary"><?= $count_menunggu; ?></h2>
          <p class="mb-0 text-muted">Menunggu</p>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h2 class="mb-1 text-warning"><?= $count_proses; ?></h2>
          <p class="mb-0 text-muted">Diproses</p>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card shadow-sm">
        <div class="card-body text-center">
          <h2 class="mb-1 text-success"><?= $count_selesai; ?></h2>
          <p class="mb-0 text-muted">Selesai</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter -->
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <form method="GET" action="">
        <div class="row g-2 align-items-center">
          <div class="col-md-6">
            <label class="form-label mb-1"><strong>Filter Status:</strong></label>
            <select name="status" class="form-select" onchange="this.form.submit()">
              <option value="">Semua Status</option>
              <option value="Menunggu" <?= ($filter_status == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
              <option value="Proses" <?= ($filter_status == 'Proses') ? 'selected' : ''; ?>>Diproses</option>
              <option value="Selesai" <?= ($filter_status == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
            </select>
          </div>
          <div class="col-md-6 text-md-end">
            <?php if ($filter_status != ''): ?>
            <a href="riwayat-pengaduan.php" class="btn btn-outline-secondary">
              Reset Filter
            </a>
            <?php endif; ?>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- List -->
  <?php
  if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
      $status_class = '';
      $status_badge = '';
      $card_status = '';
      
      if ($row['status'] == 'Menunggu') {
        $status_badge = 'bg-secondary';
        $card_status = 'status-menunggu';
      } elseif ($row['status'] == 'Proses') {
        $status_badge = 'bg-warning text-dark';
        $card_status = 'status-proses';
      } elseif ($row['status'] == 'Selesai') {
        $status_badge = 'bg-success';
        $card_status = 'status-selesai';
      }
  ?>
  <div class="card shadow-sm card-hover pengaduan-card <?= $card_status; ?> mb-3">
    <div class="card-body">
      <div class="row">
        <div class="col-md-9">
          <div class="d-flex align-items-start mb-2">
            <span class="badge <?= $status_badge; ?> me-2">
              <?= $row['status']; ?>
            </span>
            <span class="badge bg-info">
              <?= htmlspecialchars($row['ket_kategori']); ?>
            </span>
          </div>
          
          <h5 class="mb-2"><?= htmlspecialchars($row['lokasi']); ?></h5>
          
          <p class="text-muted mb-2">
            <?= nl2br(htmlspecialchars($row['ket'])); ?>
          </p>
          
          <small class="text-muted">
            Dilaporkan: <?= date('d F Y, H:i', strtotime($row['tanggal'])); ?>
          </small>
        </div>
        
        <div class="col-md-3 text-md-end mt-3 mt-md-0">
          <button class="btn btn-outline-primary btn-sm w-100" 
                  type="button" 
                  data-bs-toggle="collapse" 
                  data-bs-target="#detail<?= $row['id_pelaporan']; ?>">
            Lihat Tanggapan
          </button>
        </div>
      </div>
      
      <!-- Feedback Section -->
      <div class="collapse mt-3" id="detail<?= $row['id_pelaporan']; ?>">
        <hr>
        <div class="bg-light p-3 rounded">
          <h6 class="mb-2"><strong>Tanggapan Admin:</strong></h6>
          <?php if ($row['feedback'] != ''): ?>
            <p class="mb-0"><?= nl2br(htmlspecialchars($row['feedback'])); ?></p>
          <?php else: ?>
            <p class="text-muted mb-0 fst-italic">Belum ada tanggapan dari admin</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php
    }
  } else {
  ?>
  <div class="card shadow-sm">
    <div class="card-body text-center py-5">
      <h5 class="text-muted mb-3">Belum Ada Pengaduan</h5>
      <p class="text-muted mb-3">Anda belum pernah membuat pengaduan</p>
      <a href="input-pengaduan.php" class="btn btn-success">
        Buat Pengaduan Pertama
      </a>
    </div>
  </div>
  <?php
  }
  ?>

  <!-- Pagination -->
  <?php if ($total_pages > 1): ?>
  <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
      <li class="page-item <?= ($page <= 1) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page - 1; ?>&status=<?= urlencode($filter_status); ?>">
          Previous
        </a>
      </li>

      <?php
      $start_page = max(1, $page - 2);
      $end_page = min($total_pages, $page + 2);
      
      for ($i = $start_page; $i <= $end_page; $i++):
      ?>
      <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
        <a class="page-link" href="?page=<?= $i; ?>&status=<?= urlencode($filter_status); ?>">
          <?= $i; ?>
        </a>
      </li>
      <?php endfor; ?>

      <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : ''; ?>">
        <a class="page-link" href="?page=<?= $page + 1; ?>&status=<?= urlencode($filter_status); ?>">
          Next
        </a>
      </li>
    </ul>
  </nav>
  <?php endif; ?>
</div>

<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>