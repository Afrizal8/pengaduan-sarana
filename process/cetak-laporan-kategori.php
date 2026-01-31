<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if(!isset($_GET['id_kategori']) || empty($_GET['id_kategori'])) {
  header("location: ../admin/laporan.php");
  exit;
}

$id_kategori = mysqli_real_escape_string($conn, $_GET['id_kategori']);

// Ambil nama kategori
$query_kat = "SELECT ket_kategori FROM kategori WHERE id_kategori = '$id_kategori'";
$result_kat = mysqli_query($conn, $query_kat);
$kategori_data = mysqli_fetch_assoc($result_kat);
$nama_kategori = $kategori_data['ket_kategori'];

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

// Ambil data pengaduan berdasarkan kategori
$query = "SELECT ia.*, k.ket_kategori, s.nis, s.kelas, a.status 
          FROM input_aspirasi ia
          LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
          LEFT JOIN siswa s ON ia.nis = s.nis
          LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
          WHERE ia.id_kategori = '$id_kategori'
          ORDER BY ia.tanggal DESC";
$result = mysqli_query($conn, $query);

// Buat PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Informasi laporan
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'LAPORAN PENGADUAN KATEGORI: '.$nama_kategori,0,1,'L');
$pdf->SetFont('Arial','',9);
$pdf->Cell(0,5,'Tanggal Cetak: '.date('d-m-Y H:i:s'),0,1,'L');
$pdf->Ln(5);

// Tabel header
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(52,152,219);
$pdf->SetTextColor(255,255,255);
$pdf->Cell(10,7,'No',1,0,'C',true);
$pdf->Cell(25,7,'NIS',1,0,'C',true);
$pdf->Cell(25,7,'Kelas',1,0,'C',true);
$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
$pdf->Cell(50,7,'Keterangan',1,0,'C',true);
$pdf->Cell(25,7,'Tanggal',1,0,'C',true);
$pdf->Cell(20,7,'Status',1,1,'C',true);

// Data
$pdf->SetFont('Arial','',8);
$pdf->SetTextColor(0,0,0);
$no = 1;
$fill = false;

if(mysqli_num_rows($result) > 0) {
  while($data = mysqli_fetch_assoc($result)) {
    $pdf->SetFillColor(240,240,240);
    
    $pdf->Cell(10,6,$no++,1,0,'C',$fill);
    $pdf->Cell(25,6,$data['nis'],1,0,'C',$fill);
    $pdf->Cell(25,6,$data['kelas'],1,0,'C',$fill);
    $pdf->Cell(35,6,substr($data['lokasi'],0,20),1,0,'L',$fill);
    $pdf->Cell(50,6,substr($data['ket'],0,30),1,0,'L',$fill);
    $pdf->Cell(25,6,date('d-m-Y',strtotime($data['tanggal'])),1,0,'C',$fill);
    
    // Status dengan warna
    $status = $data['status'];
    if($status == 'Menunggu') {
      $pdf->SetTextColor(255,165,0);
    } elseif($status == 'Proses') {
      $pdf->SetTextColor(0,123,255);
    } else {
      $pdf->SetTextColor(40,167,69);
    }
    $pdf->Cell(20,6,$status,1,1,'C',$fill);
    $pdf->SetTextColor(0,0,0);
    
    $fill = !$fill;
  }
} else {
  $pdf->Cell(190,6,'Tidak ada data pengaduan untuk kategori '.$nama_kategori,1,1,'C');
}

// Statistik per status untuk kategori ini
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,7,'STATISTIK KATEGORI '.$nama_kategori,0,1,'L');

$pdf->SetFont('Arial','',9);
$total = mysqli_num_rows($result);
$menunggu = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.id_kategori='$id_kategori' AND a.status='Menunggu'"));
$proses = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.id_kategori='$id_kategori' AND a.status='Proses'"));
$selesai = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM input_aspirasi ia LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.id_kategori='$id_kategori' AND a.status='Selesai'"));

$pdf->Cell(50,6,'Total Pengaduan',1,0,'L');
$pdf->Cell(30,6,': '.$total,1,1,'L');
$pdf->Cell(50,6,'Status Menunggu',1,0,'L');
$pdf->Cell(30,6,': '.$menunggu,1,1,'L');
$pdf->Cell(50,6,'Status Proses',1,0,'L');
$pdf->Cell(30,6,': '.$proses,1,1,'L');
$pdf->Cell(50,6,'Status Selesai',1,0,'L');
$pdf->Cell(30,6,': '.$selesai,1,1,'L');

// Output PDF
$pdf->Output('I', 'Laporan_Kategori_'.str_replace(' ','_',$nama_kategori).'_'.date('YmdHis').'.pdf');
?>
