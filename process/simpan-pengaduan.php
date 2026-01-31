<?php
session_start();
include "../koneksi.php";

// Cek login siswa
if (!isset($_SESSION['siswa'])) {
    header("location: ../siswa/login-siswa.php");
    exit;
}

// Set timezone agar waktu sesuai Indonesia
date_default_timezone_set('Asia/Jakarta');

// Ambil data session & form
$nis = $_SESSION['siswa']['nis'];
$id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
$lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
$ket = mysqli_real_escape_string($conn, $_POST['ket']);

// Generate tanggal & jam sekarang
$tanggal = date('Y-m-d H:i:s');

// Simpan ke database
$query = mysqli_query(
    $conn,
    "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket, tanggal)
     VALUES ('$nis', '$id_kategori', '$lokasi', '$ket', '$tanggal')"
);

// Validasi hasil insert
if ($query) {
    $_SESSION['success'] = "Pengaduan berhasil dikirim.";
} else {
    $_SESSION['error'] = "Gagal mengirim pengaduan.";
}

// Redirect kembali ke halaman siswa
header("location: ../siswa/index-siswa.php");
exit;