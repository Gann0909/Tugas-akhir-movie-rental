<?php
session_start();
if (!isset($_SESSION["login"])) {
  header("Location: ../index.php");
  exit;
}
require '../functions.php';

$notification = $_SESSION["notification"] ?? null;
if ($notification) {
    unset($_SESSION['notification']);
}

if (empty($_SESSION["username"])) {
    header('Location: ../index.php');
    exit();
}
$name = $_SESSION["username"];

$transactions = query("SELECT * FROM transactions");

?>
<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Dashboard - Movie Rental</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../img/logo1.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- my CSS -->
      <link rel="stylesheet" href="admin.css">

    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container first">
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme first">
          <div class="app-brand demo">
            <a href="#" class="app-brand-link">
              <span class="app-brand-logo demo">
                <img src="../img/logo1.png" alt="Logo" width="60px"/>
              </span>
              <span class="app-brand-text demo menu-text fw-bolder ms-2 second">Dashboard</span>
            </a>

            <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
              <i class="bx bx-chevron-left bx-sm align-middle"></i>
            </a>
          </div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboard -->
            <li class="menu-item">
              <a href="dashboard.php" class="menu-link second">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div data-i18n="Analytics">Data Penyewaan</div>
              </a>
            </li>

            <!-- Layouts -->
            

            <li class="menu-header small text-uppercase">
              <span class="menu-header-text second">Data Lainnya</span>
            </li>
            <li class="menu-item">
              <a href="data_admin.php" class="menu-link second">
              <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Account Settings">Data Admin</div>
              </a>
            <li class="menu-item">
              <a href="data_customer.php" class="menu-link second">
              <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Authentications">Data Customer</div>
              </a>
            <li class="menu-item">
              <a href="data_film.php" class="menu-link second">
              <i class="menu-icon tf-icons bx bx-table"></i>
                <div data-i18n="Misc">Data Film</div>
              </a>
            
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page" >
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme first"
            id="layout-navbar"

          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

              <ul class="navbar-nav flex-row align-items-center ms-auto">
                <!-- Place this tag where you want the button to render. -->

                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="../img/pp-botak.jpg" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end first">
                    <li>
                      <a class="dropdown-item" href="#">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="../img/pp-botak.jpg" alt class="w-px-40 h-auto rounded-circle" />
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <span class="fw-semibold d-block second"><?php echo $name; ?></span>
                            <small class="text-muted second">Admin</small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                    </li>
                    <li>
                    </li>
                    <li>
                    </li>
                    <li>
                      <a class="dropdown-item second" href="../logout.php">
                        <i class="bx bx-power-off me-2"></i>
                        <span class="align-middle">Log Out</span>
                      </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->
             <!-- toast notification -->
    <?php if ($notification): ?>
  <div id="notifikasi" class="bs-toast toast fade bg-<?= $notification['type'] ?> position-absolute m-3 end-0" role="alert" data-bs-autohide="true">
    <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <strong class="me-auto"><?='Notifikasi' ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <?= $notification['message'] ?>
    </div>
  </div>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('notifikasi');
    if (toastEl) {
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    }
  });
</script>


            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4 third"><span class="text-muted fw-light second">Data</span> Penyewaan</h4>

              <hr class="my-5 second" />
              

              <!-- Responsive Table -->
              <div class="card first">
                <h5 class="card-header third"> <span class="second">Table</span> Data Penyewaan</h5>
                <div class="table-responsive text-nowrap">
                  <table class="table first" >
                    <thead class="fouth">
                      <tr class="text-nowrap">
                        <th class="second">#</th>
                        <th class="second">Id Penyewaan</th>
                        <th class="second">Id Film</th>
                        <th class="second">Id Customer</th>
                        <th class="second">Nama Customer</th>
                        <th class="second">Nama Film</th>
                        <th class="second">Tanggal Sewa</th>
                        <th class="second">Tanggal Kembali</th>
                        <th class="second">Status</th>
                        <th class="second">Action</th>
                      </tr>
                      <?php $i = 1; ?>
        <?php foreach( $transactions as $row ): ?>
                    </thead>
                    <tbody class="fouth">
                      <tr>
                        <th scope="row" class="second"><?= $i ?></th>
                        <td class="second"><?= $row["penyewaan_id"]?></td>
                        <td class="second"><?= $row["film_id"]?></td>
                        <td class="second"><?= $row["id"]?></td>
                        <td class="second"><?= $row["nama_customer"]?></td>
                        <td class="second"><?= $row["nama_film"]?></td>
                        <td class="second"><?= $row["tanggal_sewa"]?></td>
                        <td class="second"><?= $row["tanggal_kembali"]?></td>
                        <td class="second"><?= $row["status"]?></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow second" data-bs-toggle="dropdown">
                              <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu first">
                              <a class="dropdown-item second" href="hapus_transaksi.php?id=<?= $row["penyewaan_id"]; ?>"
                                ><i class="bx bx-trash me-1"></i> Delete</a>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php $i++; ?>
        <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <!--/ Responsive Table -->

              <hr class="my-5 second"/>
            </div>
            <!-- / Content -->

            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme first">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0 second">
                  ©
                  <script>
                    document.write(new Date().getFullYear());
                  </script>
                  , Created By
                  <a href="" class="footer-link fw-bolder third">Kelompok Movie Rental</a>
                </div>
                
              </div>
            </footer>
            <!-- / Footer -->

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        class="btn btn-danger btn-buy-now"
        >Tambah Data</a
      >
    </div> -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#testdatatable').DataTable();
      } );
    </script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
