<?php
session_start();

include "../koneksi.php";

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
  header("location: login.php");
  exit;
}

// Check if form is submitted
if (isset($_POST['simpan'])) {
  $id_pelaporan = mysqli_real_escape_string($conn, $_POST['id_pelaporan']);
  $status = mysqli_real_escape_string($conn, $_POST['status']);
  $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
  
  // Check if record exists in aspirasi table
  $check_query = mysqli_query(
    $conn,
    "SELECT id_pelaporan FROM aspirasi WHERE id_pelaporan = '$id_pelaporan'"
  );
  
  if (mysqli_num_rows($check_query) > 0) {
    // Update existing record
    $query = mysqli_query(
      $conn,
      "UPDATE aspirasi 
       SET status = '$status', 
           feedback = '$feedback'
       WHERE id_pelaporan = '$id_pelaporan'"
    );
  } else {
    // Insert new record
    $query = mysqli_query(
      $conn,
      "INSERT INTO aspirasi (id_pelaporan, status, feedback) 
       VALUES ('$id_pelaporan', '$status', '$feedback')"
    );
  }
  
  if ($query) {
    // Success
    $_SESSION['success'] = "Status dan feedback pengaduan berhasil diperbarui!";
    header("location: lihat-pengaduan.php?id=" . $id_pelaporan);
    exit;
  } else {
    // Error
    $_SESSION['error'] = "Gagal memperbarui data: " . mysqli_error($conn);
    header("location: lihat-pengaduan.php?id=" . $id_pelaporan);
    exit;
  }
} else {
  // If accessed directly without form submission
  header("location: data-pengaduan.php");
  exit;
}
?>