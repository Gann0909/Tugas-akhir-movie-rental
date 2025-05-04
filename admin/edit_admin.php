<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../index.php");
    exit;
}

require '../functions.php';

// Ambil data di URL

$id = $_GET['id'];

// Query data admin berdasarkan id

$admin = query("SELECT * FROM admin WHERE id = '$id'")[0];


// cek apakah tombol submit sudah ditakan atau belum
if ( isset( $_POST["submit"] ) ) {
    

    if( ubah($_POST) > 0) {
      $_SESSION['notification'] = [
        'type' => 'primary',
        'message' => 'Data Admin Berhasil Diubah'
        ];
        header('Location: data_admin.php');
        exit();
} else {
  $_SESSION['notification'] = [
    'type' => 'danger',
    'message' => 'Data Admin Gagal Diganti: ' . mysqli_error($conn)
  ];
  header('Location: data_admin.php');
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

    <title>Edit Data Admin - Movie Rental Admin</title>

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
                  <span class="app-brand-text demo text-body fw-bolder second sixth">Edit Data Admin</span>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-2 second">Jadilah bagian dari Movie Rental!</h4>
              <p class="mb-4 second">Silahkan isi field yang disediakan!</p>

              <form id="formAuthentication" class="mb-3" action="" method="POST">
                <div class="mb-3">
                <input type="hidden" name="id" value="<?= $admin['id']; ?>">
                  <label for="username" class="form-label second">Username</label>
                  <input
                    type="text"
                    class="form-control fiveth"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    value="<?= $admin["username"]; ?>"
                    autofocus
                  />
                </div>
                
                <div class="mb-3 form-password-toggle">
                  <label class="form-label second" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control fiveth"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      value="<?= $admin["password"]; ?>"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label second" for="password">Konfirmasi Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control fiveth"
                      name="password2"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <button class="btn btn-danger d-grid w-100" type="submit" name="submit">Save Changes</button>
                <hr>
                <a href="data_admin.php" class="second">Cancel</a>
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
