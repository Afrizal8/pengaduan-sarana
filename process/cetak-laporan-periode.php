<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if (!isset($_GET['tanggal_awal']) || !isset($_GET['tanggal_akhir'])) {
  header("location: ../admin/laporan.php");
  exit;
}

/*
|--------------------------------------------------------------------------
| 1️⃣ Normalisasi tanggal (PENTING untuk DATETIME)
|--------------------------------------------------------------------------
| tanggal_awal  -> 00:00:00
| tanggal_akhir -> 23:59:59
*/
$tanggal_awal  = mysqli_real_escape_string(
  $conn,
  $_GET['tanggal_awal'] . ' 00:00:00'
);
$tanggal_akhir = mysqli_real_escape_string(
  $conn,
  $_GET['tanggal_akhir'] . ' 23:59:59'
);

require('../assets/fpdf/fpdf.php');

class PDF extends FPDF
{
  function Header()
  {
    $this->SetFont('Arial','B',16);
    $this->Cell(0,10,'LAPORAN PENGADUAN SARANA SEKOLAH',0,1,'C');
    $this->SetFont('Arial','',10);
    $this->Cell(0,5,'Sistem Informasi Pengaduan Sarana Sekolah',0,1,'C');
    $this->Ln(5);
    $this->SetLineWidth(0.5);
    $this->Line(10,35,200,35);
    $this->Ln(10);
  }

  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Halaman '.$this->PageNo().' dari {nb}',0,0,'C');
  }
}

/*
|--------------------------------------------------------------------------
| 2️⃣ QUERY: Alias kolom tanggal (WAJIB)
|--------------------------------------------------------------------------
*/
$query = "
  SELECT
    ia.id_pelaporan,
    ia.tanggal AS tanggal_pengaduan,
    ia.lokasi,
    ia.ket,
    k.ket_kategori,
    s.nis,
    s.kelas,
    IFNULL(a.status,'Menunggu') AS status
  FROM input_aspirasi ia
  LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
  LEFT JOIN siswa s ON ia.nis = s.nis
  LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
  WHERE ia.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
  ORDER BY ia.tanggal DESC
";
$result = mysqli_query($conn, $query);

/*
|--------------------------------------------------------------------------
| 3️⃣ Buat PDF
|--------------------------------------------------------------------------
*/
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Informasi laporan
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'LAPORAN PENGADUAN PERIODE',0,1,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(
  0,
  5,
  'Periode: '.date('d-m-Y',strtotime($tanggal_awal)).
  ' s/d '.date('d-m-Y',strtotime($tanggal_akhir)),
  0,
  1,
  'L'
);
$pdf->Cell(0,5,'Tanggal Cetak: '.date('d-m-Y H:i:s'),0,1,'L');
$pdf->Ln(5);

// Header tabel
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(52,152,219);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(10,7,'No',1,0,'C',true);
$pdf->Cell(20,7,'NIS',1,0,'C',true);
$pdf->Cell(20,7,'Kelas',1,0,'C',true);
$pdf->Cell(40,7,'Kategori',1,0,'C',true);
$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
$pdf->Cell(35,7,'Tanggal',1,0,'C',true);
$pdf->Cell(25,7,'Status',1,1,'C',true);

// Data tabel
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0,0,0);
$no = 1;
$fill = false;

if (mysqli_num_rows($result) > 0) {
  while ($data = mysqli_fetch_assoc($result)) {
    $pdf->SetFillColor(240,240,240);

    $pdf->Cell(10,6,$no++,1,0,'C',$fill);
    $pdf->Cell(20,6,$data['nis'],1,0,'C',$fill);
    $pdf->Cell(20,6,$data['kelas'],1,0,'C',$fill);
    $pdf->Cell(40,6,substr($data['ket_kategori'],0,25),1,0,'L',$fill);
    $pdf->Cell(35,6,substr($data['lokasi'],0,22),1,0,'L',$fill);

    // ✅ DATETIME valid (tanggal + jam)
    $pdf->Cell(
      35,
      6,
      date('d-m-Y H:i', strtotime($data['tanggal_pengaduan'])),
      1,
      0,
      'C',
      $fill
    );

    // Status berwarna
    if ($data['status'] == 'Menunggu') {
      $pdf->SetTextColor(255,165,0);
    } elseif ($data['status'] == 'Proses') {
      $pdf->SetTextColor(0,123,255);
    } else {
      $pdf->SetTextColor(40,167,69);
    }

    $pdf->Cell(25,6,$data['status'],1,1,'C',$fill);
    $pdf->SetTextColor(0,0,0);
    $fill = !$fill;
  }
} else {
  $pdf->Cell(185,6,'Tidak ada data pengaduan pada periode tersebut',1,1,'C');
}

/*
|--------------------------------------------------------------------------
| 4️⃣ Statistik (konsisten DATETIME)
|--------------------------------------------------------------------------
*/
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,7,'STATISTIK PERIODE',0,1,'L');

$pdf->SetFont('Arial','',9);
$total    = mysqli_num_rows($result);
$menunggu = mysqli_num_rows(mysqli_query($conn,
  "SELECT 1 FROM input_aspirasi ia
   LEFT JOIN aspirasi a ON ia.id_pelaporan=a.id_pelaporan
   WHERE ia.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
   AND IFNULL(a.status,'Menunggu')='Menunggu'"
));
$proses = mysqli_num_rows(mysqli_query($conn,
  "SELECT 1 FROM input_aspirasi ia
   LEFT JOIN aspirasi a ON ia.id_pelaporan=a.id_pelaporan
   WHERE ia.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
   AND a.status='Proses'"
));
$selesai = mysqli_num_rows(mysqli_query($conn,
  "SELECT 1 FROM input_aspirasi ia
   LEFT JOIN aspirasi a ON ia.id_pelaporan=a.id_pelaporan
   WHERE ia.tanggal BETWEEN '$tanggal_awal' AND '$tanggal_akhir'
   AND a.status='Selesai'"
));

$pdf->Cell(50,6,'Total Pengaduan',1,0,'L');
$pdf->Cell(30,6,': '.$total,1,1,'L');
$pdf->Cell(50,6,'Status Menunggu',1,0,'L');
$pdf->Cell(30,6,': '.$menunggu,1,1,'L');
$pdf->Cell(50,6,'Status Proses',1,0,'L');
$pdf->Cell(30,6,': '.$proses,1,1,'L');
$pdf->Cell(50,6,'Status Selesai',1,0,'L');
$pdf->Cell(30,6,': '.$selesai,1,1,'L');

$pdf->Output(
  'I',
  'Laporan_Periode_'.
  date('Ymd',strtotime($tanggal_awal)).'_'.
  date('Ymd',strtotime($tanggal_akhir)).'.pdf'
);
?>