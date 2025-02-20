<?php
session_start();
include '../functions/functions.php';
$dataproduk = query("SELECT pesanan.idproduk, produk.namaproduk, pesanan.tanggal, pesanan.hargajual, pesanan.jumlah, pesanan.total FROM produk INNER JOIN pesanan ON produk.id=pesanan.idproduk");
if (isset($_POST["Submit"])) {
  $idproduk = $_POST['idproduk'];
  $jumlah = $_POST['jumlah'];
  $harga = $_POST['hargajual'];
  $total = $jumlah*$harga;

        $data = mysqli_query($conn, "SELECT *FROM produk where id='$idproduk'");
        $sto = mysqli_fetch_array($data);
        $stok= $sto['stok'];

        //menghitung sisa stok
    $sisa = $stok - $jumlah;
    if($stok < $jumlah){
        ?>
        <script language="JavaScript">
            alert('Oops! Jumlah pengeluaran lebih besar dari stok ...');
            document.location='datapesanan.php';
        </script>
        <?php
    }
        else {
            $insert = mysqli_query($conn, "INSERT INTO pesanan (idproduk, jumlah, hargajual, total) VALUES ('$idproduk', '$jumlah', '$harga', '$total')");
            if($insert){
                //update stok
                $upstok=mysqli_query($conn, "UPDATE produk SET stok='$sisa' WHERE id='$idproduk'");
                ?>
                    <script language="JavaScript">
                    alert('Good! Input transaksi pengeluaran barang berhasil ...');
                    document.location='datapesanan.php';
                </script>
                <?php
            }
        }
  
  //Cek apakah data berhasil ditambahkan atau tidak
  if (tambahDataPesanan($_POST) > 0) {
    echo "
    <script>
            alert('data berhasil ditambahkan!');
        document.location.href='datapesanan.php';
    </script>
    ";
  } else {
    echo "
    <script>
            alert('data gagal ditambahkan!');
        document.location.href='datapesanan.php';
    </script>
    ";
  }
}

if ($_SESSION['nama'] != "") {
  $username = $_SESSION['username'];
  $level = $_SESSION['level'];
} else {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    KASIR PENJUALAN
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.0.4" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">PT BATIK MUTIARA</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">

    <?php
    if ($_SESSION['level'] == 'admin') {
      include 'sidenavigationadmin.php';
    } else {
      include 'sidenavigationkasir.php';
    }
    ?>
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="">Halaman</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Stok</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Data Pesanan</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <form action="" method="post">
              <div class="input-group">
                <button type="submit" name="cari" class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></button>
                <input type="text" class="form-control" placeholder="Search..." autocomplete="off" name="keyword" autofocus>
              </div>

            </form>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="login.php" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none"><?php echo $_SESSION['level']; ?></span></span>
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>

            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Data Produk</h6>
            </div>
            <?php
            if ($level == 'admin') {
              include 'adminpesanan.php';
            } else if ($level == 'kasir') {
              include 'kasirpesanan.php';
            } else {
              include 'sidenavigationpelanggan.php';
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
      Input Pesanan Baru
    </button>

    <footer class="footer pt-3  ">
      <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-6 mb-lg-0 mb-4">
            <div class="copyright text-center text-sm text-muted text-lg-start">
              © <script>
                document.write(new Date().getFullYear())
              </script>,
              made with <i class="fa fa-heart"></i> by
              <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Kelompok 4</a>
              for a better web.
            </div>
          </div>

        </div>
      </div>
    </footer>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>
<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Tambah Pesanan Baru</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form action="" method="post">
        <div class="modal-body">
          <?php
          $no = 0;
          $hasil = mysqli_query($conn, "SELECT *FROM produk ORDER BY namaproduk");
          echo '<select name="idproduk" class="form-control mt-2" required>';
          echo '<option value="">Pilih Produk</option>';
          while ($rowbar = mysqli_fetch_array($hasil)) {
            echo '<option value="' . $rowbar['id'] . '">' . $rowbar['namaproduk'] . ' - ' . $rowbar['harga'] . '</option>';
          }
          echo '</select>';
          ?>
          <input type="text" class="form-control mt-2" name="hargajual" placeholder="Harga Jual">
          <input type="text" class="form-control mt-2" name="laba" placeholder="Laba">
          <input type="num" class="form-control mt-2" name="jumlah" placeholder="Jumlah Order">
        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" data-bs-dismiss="modal" name="Submit" value="Submit">Input</button>
          <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>


</html>
