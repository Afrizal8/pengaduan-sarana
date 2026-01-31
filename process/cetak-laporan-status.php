<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
  header("location: ../admin/login.php");
  exit;
}

if(!isset($_GET['status']) || empty($_GET['status'])) {
  header("location: ../admin/laporan.php");
  exit;
}

$status = mysqli_real_escape_string($conn, $_GET['status']);

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

// Ambil data pengaduan berdasarkan status
$query = "SELECT ia.*, k.ket_kategori, s.nis, s.kelas, a.status, a.feedback 
          FROM input_aspirasi ia
          LEFT JOIN kategori k ON ia.id_kategori = k.id_kategori
          LEFT JOIN siswa s ON ia.nis = s.nis
          LEFT JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
          WHERE a.status = '$status'
          ORDER BY ia.tanggal DESC";
$result = mysqli_query($conn, $query);

// Buat PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

// Informasi laporan
$pdf->SetFont('Arial','B',11);
$pdf->Cell(0,7,'LAPORAN PENGADUAN DENGAN STATUS: '.$status,0,1,'L');
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
$pdf->Cell(40,7,'Kategori',1,0,'C',true);
$pdf->Cell(30,7,'Lokasi',1,0,'C',true);
$pdf->Cell(25,7,'Tanggal',1,0,'C',true);
$pdf->Cell(25,7,'Status',1,1,'C',true);

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
    $pdf->Cell(40,6,substr($data['ket_kategori'],0,25),1,0,'L',$fill);
    $pdf->Cell(30,6,substr($data['lokasi'],0,20),1,0,'L',$fill);
    $pdf->Cell(25,6,date('d-m-Y',strtotime($data['tanggal'])),1,0,'C',$fill);
    
    // Status dengan warna
    if($status == 'Menunggu') {
      $pdf->SetTextColor(255,165,0);
    } elseif($status == 'Proses') {
      $pdf->SetTextColor(0,123,255);
    } else {
      $pdf->SetTextColor(40,167,69);
    }
    $pdf->Cell(25,6,$status,1,1,'C',$fill);
    $pdf->SetTextColor(0,0,0);
    
    $fill = !$fill;
  }
} else {
  $pdf->Cell(180,6,'Tidak ada data pengaduan dengan status '.$status,1,1,'C');
}

// Statistik
$pdf->Ln(5);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,7,'RINGKASAN',0,1,'L');

$pdf->SetFont('Arial','',9);
$total = mysqli_num_rows($result);

$pdf->Cell(70,6,'Total Pengaduan dengan Status '.$status,1,0,'L');
$pdf->Cell(30,6,': '.$total.' pengaduan',1,1,'L');

// Output PDF
$pdf->Output('I', 'Laporan_Status_'.$status.'_'.date('YmdHis').'.pdf');
?>
