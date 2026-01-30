<?php 
session_start();

include "../koneksi.php";

if(!isset($_POST['login'])) {
  header("location: ../siswa/login-siswa.php");
  exit;
}

$nis = mysqli_real_escape_string($conn, $_POST['nis']);

$query = mysqli_query(
  $conn,
  "SELECT * FROM siswa WHERE nis = '$nis'"
);

if(mysqli_num_rows($query) == 1) {
  $data = mysqli_fetch_assoc($query);
  $_SESSION['siswa'] = 
  ['nis' => $data['nis'],
  'nama' => $data['nama'],
  'kelas' => $data['kelas']
  ];
  header("location: ../siswa/index-siswa.php");
  exit;
} else {
  echo "<script> alert('NIS tidak terdaftar')
  window.location='../siswa/login-siswa.php';
  </script>";
}
?>