<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if(isset($_GET['nis'])) {
  $nis = mysqli_real_escape_string($conn, $_GET['nis']);

  // Hapus data siswa
  $query = "DELETE FROM siswa WHERE nis = '$nis'";
  
  if(mysqli_query($conn, $query)) {
    header("location: ../admin/data-siswa.php?pesan=hapus");
  } else {
    header("location: ../admin/data-siswa.php?pesan=error");
  }
} else {
  header("location: ../admin/data-siswa.php");
}
?>