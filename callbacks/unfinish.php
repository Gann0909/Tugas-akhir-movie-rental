<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD"
      crossorigin="anonymous"
    />
    <link id="favicon" rel="shortcut icon" href="../img/logo1.png" />
    <link rel="stylesheet" href="callbacks.css" />
    <title>Transaksi Unfinish - Movie Rental Callbacks</title>
  </head>
  <body>
    <section class="error d-flex flex-column align-items-center justify-content-center">
      <div class="lottie">
        <lottie-player
          src="https://assets8.lottiefiles.com/packages/lf20_vzj1xd0x.json"
          background="transparent"
          speed="1"
          style="width: 420px; height: 420px;"
          loop
          autoplay
        >
        </lottie-player>
      </div>
      <div class="text text-center">
        <p class="text-center">Waduh, Kayanya Transaksi Kamu Belum Diselesaikan.</p>
        <div class="btn btn-outline-primary">
          <a class="text-center" href="index.php">Harap Selesaikan Transaksi Terlebih Dahulu!</a>
        </div>
      </div>
    </section>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  </body>
</html>
