<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nis = mysqli_real_escape_string($conn, $_POST['nis']);
  $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);

  // Cek apakah NIS sudah ada
  $cek_query = "SELECT * FROM siswa WHERE nis = '$nis'";
  $cek_result = mysqli_query($conn, $cek_query);

  if(mysqli_num_rows($cek_result) > 0) {
    header("location: ../admin/tambah-siswa.php?pesan=duplicate");
    exit;
  }

  // Insert data siswa
  $query = "INSERT INTO siswa (nis, kelas) VALUES ('$nis', '$kelas')";
  
  if(mysqli_query($conn, $query)) {
    header("location: ../admin/data-siswa.php?pesan=sukses");
  } else {
    header("location: ../admin/tambah-siswa.php?pesan=error");
  }
} else {
  header("location: ../admin/data-siswa.php");
}
?>