<?php  
session_start();
require 'functions.php';



if (isset($_POST["login"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    // cek username
    if (mysqli_num_rows($result) === 1) {
        // cek password
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row["password"]))  {
            // cek session
            $_SESSION["login"] = true;
            $_SESSION["username"] = $row["username"];

            header("Location: admin/dashboard.php");
            exit;
        }
    }
    $error = true;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movie Rental</title>

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="img/logo1.png" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,700;1,700&display=swap"
    rel="stylesheet">
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

  <!-- Icons -->
  <script src="https://unpkg.com/feather-icons"></script>

  <!-- My Style -->
  <link rel="stylesheet" href="css/style.css">

  <!-- aos -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <!-- font awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"
  integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />


  <!-- AlpineJS -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- App -->
   <script src="src/app.js" async></script>

   <!-- midtrans -->
   <script type="text/javascript"
   src="https://app.sandbox.midtrans.com/snap/snap.js"
   data-client-key="SB-Mid-client-IOnblcCMZZzfpPNZ"></script>
</head>

<body>

  <!-- Navbar start -->
  <nav class="navbar" x-data>
    <a href="#" class="navbar-logo" data-aos="zoom-in" data-aos-duration="200">Movie<span>Rental</span>.</a>

    <div class="navbar-nav">
      <a href="#home" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="200">Home</a>
      <a href="#about" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="400">Tentang Kami</a>
      <a href="#menu" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="600">Coming Soon</a>
      <a href="#products" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="800">Film Kami</a>
      <a href="#contact" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="1000">Kontak</a>
    </div>

    <div class="navbar-extra">
      <a href="#" id="shopping-cart-button" data-aos="zoom-in" data-aos-duration="200" data-aos-delay="1400">
        <i data-feather="shopping-cart"></i>
        <span class="quantity-badge" x-show="$store.cart.quantity" x-text="$store.cart.quantity"></span>
      </a>
      <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
    </div>

    <!-- Shopping Cart start -->
    <div class="shopping-cart">
      <template x-for="(item, index) in $store.cart.items" x-keys="index">
        <div class="cart-item">
          <img :src="`img/${item.gambar}`" :alt="item.judul_film">
          <div class="item-detail">
            <h3 x-text="item.judul_film"></h3>
            <div class="item-harga_sewa">
              <span x-text="rupiah(item.harga_sewa)"></span> &times;
              <button id="remove" @click="$store.cart.remove(item.film_id)">&minus;</button>
              <span x-text="item.quantity"></span>
              <button id="add" @click="$store.cart.add(item)">&plus;</button> <p>Hari</p>
            </div>
          </div>
          <!-- <i data-feather="trash-2" class="remove-item"></i> -->
        </div>
      </template>
      <h4 x-show="!$store.cart.items.length" style="margin-top: 1rem;">Cart Is Empty</h4>
      <h4 x-show="$store.cart.items.length">Total : <span x-text="rupiah($store.cart.total)"></span></h4>
      <div class="form-container" x-show="$store.cart.items.length">
        <form action="" id="checkoutForm" method="POST">
          <input type="hidden" name="items" x-model="JSON.stringify($store.cart.items)">
          <input type="hidden" name="total" x-model="$store.cart.total">
          <h5>Customer Detail</h5>
          <label for="name">
            <span>Name</span>
            <input type="text" id="name" name="name" required autocomplete="off">
          </label>
          <label for="email">
            <span>Email</span>
            <input type="email" id="email" name="email" required autocomplete="off">
          </label>
          <label for="phone">
            <span>Phone</span>
            <input type="number" id="phone" name="phone" required autocomplete="off">
          </label>
          <button class="checkout disabled" type="submit" id="checkout-button" value="checkout" name="submit">Checkout</button>
          <span><p></p></span>
        </form>
      </div>
    </div>
    <!-- Shopping Cart end -->

  </nav>
  <!-- Navbar end -->

  
  <!-- Hero Section start -->
  <section class="hero" id="home">
    
    <div class="mask-container">
      <?php if (isset($error)) : ?>
        <div class="alert-error" id="alertError">
          <div class="toast">
        <i class="fas fa-times-circle"></i>
          <p>Username Atau Password Salah!</p>
          </div>
          </div>

          <script>
        setTimeout(function() {
            var alertBox = document.getElementById("alertError");
            if (alertBox) {
                alertBox.style.transition = "opacity 0.5s";
                alertBox.style.opacity = "0";
                setTimeout(function() {
                    alertBox.style.display = "none";
                }, 500);
            }
        }, 5000);
    </script>
        <?php endif; ?>
      <main class="content">

        <h1 data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="1400">Tempatnya Para <span>Pecinta Film</span></h1>
        <p data-aos="fade-up" data-aos-duration="800" data-aos-delay="1800">Solusi untuk hiburan anda dan keluarga.</p>
      </main>
    </div>
  </section>
  <!-- Hero Section end -->

  <!-- About Section start -->
  <section id="about" class="about">
    <h2 data-aos="fade-up"><span>Tentang</span> Kami</h2>

    <div class="row">
      <div class="about-img">
        <video autoplay muted loop data-aos="fade-up" data-aos-delay="1000">
          <source src="img/logo.mp4" type="video/mp4" >
        </video>
      </div>
      <div class="content">
        <h3 data-aos="fade-up" data-aos-delay="500">Tentang Movie Rental</h3>
        <p data-aos="fade-up" data-aos-delay="500">Movie Rental adalah platform penyewaan film terpercaya yang menyediakan film berkualitas, mulai dari film lokal hingga film hollywood terbaik. Kami berkomitmen menghadirkan hiburan yang menyenangkan dan berkualitas tinggi untuk hiburan anda.</p>
        <p data-aos="fade-up" data-aos-delay="500">Dengan seleksi film yang ketat dan harga kompetitif, kami memastikan Anda tetap terhibur. Kepercayaan pelanggan adalah prioritas utama kami, sehingga kami selalu siap menjadi mitra terbaik Anda dalam penyewaan film.</p>
      </div>
    </div>
  </section>
  <!-- About Section end -->

  <!-- Menu Section start -->
  <section id="menu" class="menu">
    <h2 data-aos="fade-up"><span>Coming</span> Soon</h2>
    <p data-aos="fade-up" data-aos-delay="600">Bersiaplah untuk pengalaman sinematik yang tak terlupakan! Kami menghadirkan deretan film terbaru yang akan segera tayang. Dari aksi yang mendebarkan, drama yang menyentuh hati, hingga petualangan epik yang penuh kejutan semua ada di sini!
    </p>

    <div class="row">
      <div class="menu-card">
        <img src="img/anak kunti.jpeg" alt="Anak Kunti" class="menu-card-img" data-aos="fade-up" data-aos-delay="500">
        <h3 class="menu-card-title" data-aos="fade-up" data-aos-delay="500">-  Anak Kunti -</h3>
        <p class="menu-card-price" data-aos="fade-up" data-aos-delay="600">IDR 40K</p>
      </div>
      <div class="menu-card">
        <img src="img/the bayou.jpeg" alt="The Bayou" class="menu-card-img" data-aos="fade-up" data-aos-delay="500">
        <h3 class="menu-card-title" data-aos="fade-up" data-aos-delay="500">- The Bayou -</h3>
        <p class="menu-card-price" data-aos="fade-up" data-aos-delay="600">IDR 30K</p>
      </div>
      <div class="menu-card">
        <img src="img/brave new world.jpeg" alt="Captain Amerika: Brave New World" class="menu-card-img" data-aos="fade-up" data-aos-delay="500">
        <h3 class="menu-card-title" data-aos="fade-up" data-aos-delay="500">- Captain Amerika: Brave New World -</h3>
        <p class="menu-card-price" data-aos="fade-up" data-aos-delay="600">IDR 30K</p>
      </div>
    </div>
  </section>
  <!-- Menu Section end -->

  <!-- Products Section start -->
  <section class="products" id="products" x-data="products" x-init="fetchProducts()">
    <h2 data-aos="fade-up" data-aos-delay="300"><span>Film</span> Kami</h2>
    <p data-aos="fade-up" data-aos-delay="300">Berikut film-film yang tersedia beserta harga sewa per hari. Anda hanya dapat menyewa 1 film pertransaksi, jika anda ingin menyewa film lainnya, silakan melakukan transaksi baru.</p>

    <div class="row">
      <template x-for="item in items" x-key="item.film_id">
        <div class="product-card" data-aos="fade-up" data-aos-delay="200">
        <div class="product-icons" data-aos="fade-up" data-aos-delay="200">
          <a href="#" @click.prevent="$store.cart.add(item)">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#shopping-cart" />
            </svg>
          </a>
          <a href="#" class="item-detail-button" @click.prevent="$store.modal.add(item)">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <use href="img/feather-sprite.svg#eye" />
            </svg>
          </a>
        </div>
        <div class="product-image" data-aos="fade-up" data-aos-delay="200">
          <img :src="`img/${item.gambar}`" :alt="item.judul_film">
        </div>
        <div class="product-content">
          <h3 x-text="item.judul_film"></h3>
          <div class="product-stars" data-aos="fade-up" data-aos-delay="200">
            <span x-html="generateStars(item.rating)"></span>
          </div>
          <div class="product-price" data-aos="fade-up" data-aos-delay="200"><span x-text="rupiah(item.harga_sewa)"></span></div>
        </div>
        </div>
      </template>
    </div>

  </section>
  <!-- Products Section end -->

  <!-- Contact Section start -->
  <section id="contact" class="contact">
    <h2 data-aos="fade-up" data-aos-delay="200"><span>Kontak</span> Kami</h2>
    <p data-aos="fade-up" data-aos-delay="200">Kami siap membantu bila anda ingin menyewa film.
    </p>

    <div class="row" data-aos="fade-up" data-aos-delay="200">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.3162085764457!2d104.54147677423941!3d0.9090040628357762!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d96c8b61440b13%3A0xdc142cab464b148a!2sSMK%20Negeri%204%20Tanjungpinang!5e0!3m2!1sid!2sid!4v1743475742019!5m2!1sid!2sid" width="600" height="593" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

      <form action="">
        <div class="input-group" data-aos="fade-up" data-aos-delay="200">
          <i data-feather="user" data-aos="fade-right" data-aos-delay="500"></i>
          <input type="text" placeholder="Nama" data-aos="fade-left" data-aos-delay="500">
        </div>
        <div class="input-group" data-aos="fade-up" data-aos-delay="200">
          <i data-feather="mail" data-aos="fade-right" data-aos-delay="500"></i>
          <input type="text" placeholder="Email" data-aos="fade-left" data-aos-delay="500">
        </div>
        <div class="input-group" data-aos="fade-up" data-aos-delay="200">
          <i data-feather="phone" data-aos="fade-right" data-aos-delay="500"></i>
          <input type="text" placeholder="No. HP" data-aos="fade-left" data-aos-delay="500">
        </div>
        <button type="submit" class="btn" data-aos="fade-up" data-aos-delay="200">Kirim Pesan</button>
      </form>

    </div>
  </section>
  <!-- Contact Section end -->

  <!-- Footer start -->
  <footer>

    <div class="links">
      <a href="#home">Home</a>
      <a href="#about">Tentang Kami</a>
      <a href="#menu">Coming Soon</a>
      <a href="#products">Film Kami</a>
      <a href="#contact">Kontak</a>
    </div>

    <div class="floating-contact-form">
        <div class="form-container">
          <h3>Anda Seorang <span>Admin?</span> Silahkan <span>Login </span>Disini</h3>

          <form action="" method="post" >
            <div class="field-container">
              <i id="nameicon"></i>
              <label for="username">Username</label>
              <input class="form-input" type="text" id="name" placeholder="username" name="username" required />
            </div>
            
            <div class="field-container">
              <i id="emailicon"></i>
              <label for="password">Password</label>
              <input class="form-input" type="password" id="email" placeholder="Masukkan Password" name="password" required />
            </div>

            <input type="submit" name="login" value="login" />
          </form>
        </div>

        <div class="contact-icon">
          <i class="fas fa-sign-in-alt"></i>
        </div>
      </div>

    <div class="credit">
      <p>Created by <a href="">Kelompok Movie Rental</a>. | &copy; 2025.</p>
    </div>
  </footer>
  <!-- Footer end -->

  <!-- Modal Box Item Detail start -->
  <div class="modal" id="item-detail-modal" x-data>
    <div class="modal-container">
      <a href="#" class="close-icon"><i data-feather="x"></i></a>
      <template x-for="(item, index) in $store.modal.items" x-keys="index">
      <div class="modal-content">
        <img :src="`img/${item.gambar}`" :alt="item.judul_film">
        <div class="product-content">
          <h3 x-text="item.judul_film" id="judul"></h3>
          <p x-text="item.deskripsi"></p>
          <div class="product-stars" x-html="$store.modal.generateStars()">
            <!-- <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star" class="star-full"></i>
            <i data-feather="star"></i> -->
          </div>
          <div class="product-price"><span x-text="rupiah(item.harga_sewa)"></span></div>
          <a href="#products" @click="$store.cart.add(item)"><i data-feather="shopping-cart"></i> <span>add to cart</span></a>
        </div>
      </div>
      </template>
    </div>
  </div>
  <!-- Modal Box Item Detail end -->

  <!-- Feather Icons -->
  <script>
    feather.replace();
    </script>
  <!-- aos js -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  <!-- My Javascript -->
  <script src="js/script.js"></script>
</body>

</html>