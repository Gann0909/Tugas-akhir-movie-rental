<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

$title = "Tambah Data Customer";
$notification = $_SESSION["notification"] ?? null;
if ($notification) {
    unset($_SESSION['notification']);
}

// cek apakah tombol submit sudah ditakan atau belum
if ( isset( $_POST["submit"] ) ) {
    

    if( tambahCustomer($_POST) > 0) {
      $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Customer Berhasil Ditambahkan!'
        ];
        header('Location: data_customer.php');
        exit();
} else {
  $_SESSION['notification'] = [
    'type' => 'danger',
    'message' => 'Data Customer Gagal Ditambahkan: ' . mysqli_error($conn)
    ];
    header('Location: data_customer.php');
    exit();
}
}

?>


<!DOCTYPE html>

<html
  lang="en"
  class="light-style customizer-hide"
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

    <title>Tambah Data Customer - Movie Rental Admin</title>

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

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
     <link rel="stylesheet" href="admin.css">
    <!-- Page -->
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Content -->
    <?php if ($notification): ?>
  <div id="notifikasi" class="bs-toast toast fade bg-<?= $notification['type'] ?> position-absolute m-3 end-0" role="alert" data-bs-autohide="true">
    <div class="toast-header">
      <i class="bx bx-bell me-2"></i>
      <strong class="me-auto"><?= $notification['title'] ?? 'Notifikasi' ?></strong>
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
    <div class="container-xxl first">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card first fiveth">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center">
                <a href="../index.php" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                    <img src="../img/logo1.png" alt="Logo" width="80px">
                  </span>
                  <span class="app-brand-text demo text-body fw-bolder second sixth">Tambah Customer</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2 second">Untuk menambahkan Customer,</h4>
              <p class="mb-4 second">Silahkan isi field yang disediakan!</p>

              <form id="formAuthentication" class="mb-3" action="" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                  <label for="username" class="form-label second">Order ID</label>
                  <input
                    type="number"
                    class="form-control fiveth"
                    id="username"
                    name="order"
                    placeholder="Tuliskan Order ID"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="username" class="form-label second">Nama Customer</label>
                  <input
                    type="text"
                    class="form-control fiveth"
                    id="username"
                    name="nama"
                    placeholder="Tuliskan Nama Customer"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="username" class="form-label second">Email Customer</label>
                  <input
                    type="text"
                    class="form-control fiveth"
                    id="username"
                    name="email"
                    placeholder="Tuliskan Email Customer"
                    autofocus
                    required
                  />
                </div>
                <div class="mb-3">
                  <label for="username" class="form-label second">No. HP Customer</label>
                  <input
                    type="number"
                    class="form-control fiveth"
                    id="username"
                    name="nomor"
                    placeholder="Tuliskan No. HP Customer"
                    autofocus
                    required
                  />
                </div>
                
                <div class="mb-3 form-password-toggle">
                
                        <label for="smallSelect" class="form-label second">Status Transaksi</label>
                        <select id="smallSelect" class="form-select form-select-sm eight" name="status" required>
                          <option value="">Pilih Status Transaksi</option>
                          <option value="settlement">Settlement</option>
                          <option value="pending">Pending</option>
                          <option value="expired">Expired</option>
                        </select>
                </div>
                <button class="btn btn-danger d-grid w-100" type="submit" name="submit">Tambah</button>
                <hr>
                <a href="data_customer.php" class="second">Cancel</a>
              </form>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

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

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
