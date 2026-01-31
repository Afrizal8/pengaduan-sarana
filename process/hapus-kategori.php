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
  $_SESSION['error'] = "ID kategori tidak valid!";
  header("location: ../admin/data-kategori.php");
  exit;
}

$id_kategori = mysqli_real_escape_string($conn, $_GET['id']);

// Check if category is being used in input_aspirasi
$check_usage = mysqli_query(
  $conn,
  "SELECT COUNT(*) as total FROM input_aspirasi WHERE id_kategori = '$id_kategori'"
);
$usage_count = mysqli_fetch_assoc($check_usage)['total'];

if ($usage_count > 0) {
  $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan oleh $usage_count pengaduan!";
  header("location: ../admin/data-kategori.php");
  exit;
}

// Delete category
$query = mysqli_query(
  $conn,
  "DELETE FROM kategori WHERE id_kategori = '$id_kategori'"
);

if ($query) {
  $_SESSION['success'] = "Kategori berhasil dihapus!";
} else {
  $_SESSION['error'] = "Gagal menghapus kategori: " . mysqli_error($conn);
}

header("location: ../admin/data-kategori.php");
exit;
?>