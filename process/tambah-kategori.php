<?php
session_start();

include "../koneksi.php";

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

// Check if form is submitted
if (isset($_POST['tambah'])) {
  $ket_kategori = mysqli_real_escape_string($conn, trim($_POST['ket_kategori']));
  
  // Validate input
  if (empty($ket_kategori)) {
    $_SESSION['error'] = "Nama kategori tidak boleh kosong!";
    header("location: ../admin/data-kategori.php");
    exit;
  }
  
  // Check if category already exists
  $check_query = mysqli_query(
    $conn,
    "SELECT * FROM kategori WHERE ket_kategori = '$ket_kategori'"
  );
  
  if (mysqli_num_rows($check_query) > 0) {
    $_SESSION['error'] = "Kategori dengan nama tersebut sudah ada!";
    header("location: ../admin/data-kategori.php");
    exit;
  }
  
  // Insert new category
  $query = mysqli_query(
    $conn,
    "INSERT INTO kategori (ket_kategori) VALUES ('$ket_kategori')"
  );
  
  if ($query) {
    $_SESSION['success'] = "Kategori berhasil ditambahkan!";
  } else {
    $_SESSION['error'] = "Gagal menambahkan kategori: " . mysqli_error($conn);
  }
  
  header("location: ../admin/data-kategori.php");
  exit;
} else {
  header("location: ../admin/data-kategori.php");
  exit;
}
?>