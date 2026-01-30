<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/fontawesome/css/all.css" />
    <link rel="stylesheet" href="assets/css/style.css">
    <title>SIPASIS</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
      <div class="container">
        <a class="navbar-brand fw-bold" href="#">
          <i class="fa-solid fa-school"></i> SIPASIS
        </a>
      </div>
    </nav>

    <section class="hero">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-6 mb-4">
            <h1 class="fw-bold">Sistem Pengaduan Sarana Siswa</h1>
            <p class="text-muted mt-3">
              SIPASIS (Sistem Pengaduan Sarana Siswa) dibuat dengan tujuan untuk
              menyediakan media pengaduan yang terstruktur, efektif, dan
              transparan bagi siswa dalam menyampaikan keluhan terkait sarana
              dan prasarana sekolah.
            </p>
            <div class="mt-4">
              <a
                href="siswa/login-siswa.php"
                class="btn btn-success btn-lg me-2"
              >
                <i class="fa-solid fa-graduation-cap"></i> Login Siswa
              </a>
              <a
                href="admin/login.php"
                class="btn btn-outline-dark btn-lg"
              >
                <i class="fa-solid fa-user"></i> Login Admin
              </a>
            </div>
          </div>

        <div class="col-md-6 text-center">
          <div class="icon-box mb-3">
            <i class="fa-solid fa-comments"></i>
          </div>
          <h5 class="fw-semibold">
            Pengaduan | Umpan balik | Feedback
            <p class="text-muted">
              Setiap laporan ditindak lanjuti oleh pihak sekolah. <br>
              <strong>Web App ini dibuat untuk keperluan UAS Pemograman Web I Tahun 2026</strong>
            </p>
          </h5>
        </div>
      </div>
              </div>
    </section>
    <footer class="bg-light py-3 text-center">
      <small class="text-muted">
        &copy; <?php echo date('Y');?>
        SIPASIS - By Muhammad Rizal Afrizal
      </small>
    </footer>

    <script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
