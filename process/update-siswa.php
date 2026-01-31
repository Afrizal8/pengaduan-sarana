<?php
session_start();
include"../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
  $nis_lama = mysqli_real_escape_string($conn, $_POST['nis_lama']);
  $nis = mysqli_real_escape_string($conn, $_POST['nis']);
  $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);

  // Jika NIS diubah, cek apakah NIS baru sudah ada
  if($nis != $nis_lama) {
    $cek_query = "SELECT * FROM siswa WHERE nis = '$nis'";
    $cek_result = mysqli_query($conn, $cek_query);

    if(mysqli_num_rows($cek_result) > 0) {
      header("location: ../admin/edit-siswa.php?nis=$nis_lama&pesan=duplicate");
      exit;
    }
  }

  // Update data siswa
  $query = "UPDATE siswa SET nis = '$nis', kelas = '$kelas' WHERE nis = '$nis_lama'";
  
  if(mysqli_query($conn, $query)) {
    header("location: ../admin/data-siswa.php?pesan=update");
  } else {
    header("location: ../admin/edit-siswa.php?nis=$nis_lama&pesan=error");
  }
} else {
  header("location: ../admin/data-siswa.php");
}
?>