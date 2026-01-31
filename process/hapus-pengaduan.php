<?php
session_start();

include "../koneksi.php";

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

// Check if id parameter exists
if (!isset($_GET['id'])) {
  $_SESSION['error'] = "ID pengaduan tidak valid!";
  header("location: ../admin/data-pengaduan.php");
  exit;
}

$id_pelaporan = mysqli_real_escape_string($conn, $_GET['id']);

// Begin transaction
mysqli_begin_transaction($conn);

try {
  // Delete from aspirasi table first (child table)
  $delete_aspirasi = mysqli_query(
    $conn,
    "DELETE FROM aspirasi WHERE id_pelaporan = '$id_pelaporan'"
  );

  // Then delete from input_aspirasi table (parent table)
  $delete_input = mysqli_query(
    $conn,
    "DELETE FROM input_aspirasi WHERE id_pelaporan = '$id_pelaporan'"
  );

  if ($delete_input) {
    // Commit transaction
    mysqli_commit($conn);
    $_SESSION['success'] = "Pengaduan berhasil dihapus!";
  } else {
    throw new Exception("Gagal menghapus data pengaduan");
  }
} catch (Exception $e) {
  // Rollback transaction on error
  mysqli_rollback($conn);
  $_SESSION['error'] = "Gagal menghapus pengaduan: " . $e->getMessage();
}

// Redirect back to data pengaduan page
header("location: ../admin/data-pengaduan.php");
exit;
?>