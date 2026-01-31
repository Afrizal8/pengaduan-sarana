<?php
session_start();
include "../koneksi.php";

if(!isset($_SESSION['siswa'])) {
  header("location: ../siswa/login-siswa.php");
  exit;
}
$nis = $_SESSION['siswa']['nis'];
$id_kategori = mysqli_real_escape_string($conn,$_POST['id_kategori']);
$lokasi = mysqli_real_escape_string($conn,$_POST['lokasi']);
$ket = mysqli_real_escape_string($conn,$_POST['ket']);

mysqli_query($conn,"INSERT INTO input_aspirasi (nis,id_kategori,lokasi,ket)
VALUES ('$nis','$id_kategori','$lokasi','$ket')");

header("location: ../siswa/index-siswa.php");
?>