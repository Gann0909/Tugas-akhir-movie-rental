<?php
// koneksi ke database
$conn = mysqli_connect("localhost","root","","movierental");
// query untuk menyimpan data dan memasukkannya ke dalam format object
function query($query) {
    // mengambil variabel koneksi
    global $conn;
    // menyimpan hasil query sesuai dengan nilai dari variabel $query ke variabel $result
    $result = mysqli_query($conn, $query);
    $rows = [];
    // memasukkan isi dari $result ke object $rows
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
}
// mengembalikan isi dari $rows
return $rows;
}
// function untuk proses registrasi
function registrasi($data) {
    // mengambil variabel koneksi
    global $conn;
    // menampung isi dari $data kedalam variabel
    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string ($conn, $data["password"]);
    $password2 = mysqli_real_escape_string ($conn, $data["password2"]);

    // cek username sudah ada atau belum
    $result = mysqli_query($conn, "SELECT * FROM admin WHERE username = '$username'");

    if (mysqli_fetch_assoc($result) ) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Username Sudah Ada'
        ];
        return false;
        }

    // cek konfirmasi password
    if ($password !== $password2) {
        $_SESSION['notification'] = [
            'type' => 'danger',
            'message' => 'Konfirmasi Password Tidak Sesuai'
        ];
        return false;
        }

        // enkripsi password
        $password = password_hash($password, PASSWORD_DEFAULT);

        if (mysqli_query($conn, "INSERT INTO admin (username, password) VALUES ('$username', '$password')")) {
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'Data Admin Berhasil Ditambahkan'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Data Admin Gagal Ditambahkan: ' . mysqli_error($conn)
                ];
        }

        return mysqli_affected_rows( $conn );
    }
// function untuk mengubah data 
    function ubah($data) {
        global $conn;
        // ambil data dari tiap elemen dalam form
        $id = $data["id"];
        $username = htmlspecialchars($data["username"]);
        $password = htmlspecialchars($data["password"]);
        $password2 = htmlspecialchars($data["password2"]);
    
        // cek konfirmasi password
        if ($password !== $password2) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Konfirmasi Password Tidak Sesuai'
            ];
            return false;
            }
    
            // enkripsi password
            $password = password_hash($password, PASSWORD_DEFAULT);
    
    
        // query update data
        $query = "UPDATE admin SET
                    username = '$username',
                    password = '$password'
                    WHERE id = $id";
                    
    
                    $result = mysqli_query( $conn, $query );
                //    mysqli_query($conn, $query);

                   if ($result) {
                    $_SESSION['notification'] = [
                        'type' => 'primary',
                        'message' => 'Data Admin Berhasil Diganti'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Data Admin Gagal Diganti: ' . mysqli_error($conn)
                        ];
                }
    
                   return mysqli_affected_rows($conn);
    }
// function untuk menghapus data
    function hapus($id) {
        global $conn;
        mysqli_query($conn,"DELETE FROM admin WHERE id = $id");
        return mysqli_affected_rows($conn);
    }
// function untuk menambah data film
    function tambahFilm($data) {
        global $conn;
        // ambil data dari tiap elemen dalam form
        $judul = htmlspecialchars($data["judul"]);
        $genre = htmlspecialchars($data["genre"]);
        $harga = htmlspecialchars($data["harga"]);
        $rating = htmlspecialchars($data["rating"]);
        $tahun = htmlspecialchars($data["tahun"]);
        $deskripsi = htmlspecialchars($data["deskripsi"]);
       
        // upload gambar
    
        $gambar = uploadGambarFilm();
        if ( !$gambar ) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Pilih gambar terlebih dahulu!'
            ];
            return false;
        }
    
    
        // query insert data
        $query = "INSERT INTO film
                    VALUES
                    ('', '$judul', '$genre', '$harga', '$rating', '$gambar', '$tahun', '$deskripsi')";
    
                    //$result = mysqli_query( $conn, $query );
                   mysqli_query($conn, $query);
    
                   return mysqli_affected_rows($conn);
    }
    
    // function untuk menangani data gambar
    function uploadGambarFilm() {
        // memasukkan data gambar kedalam variable 
        $namaFile = $_FILES['gambar']['name'];
        $ukuranFile = $_FILES['gambar']['size'];
        $error = $_FILES['gambar']['error'];
        $tmpName = $_FILES['gambar']['tmp_name'];
    
        // Cek apakah tidak ada gambar yang diupload
        if ($error === 4) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Pilih gambar terlebih dahulu!'
            ];
            return false;
        }
    
        // Validasi ekstensi file
        $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
        $ekstensiGambar = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    
        if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Yang anda upload bukan gambar!'
            ];
            return false;
        }
    
        // Validasi ukuran file
        if ($ukuranFile > 1000000) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Ukuran gambar terlalu besar!'
            ];
            return false;
        }
    
        // Generate nama file unik
        $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    
        // Pastikan folder img/ ada, kalau tidak buat baru
        $folderUpload = __DIR__ . '/img/';
        if (!is_dir($folderUpload)) {
            mkdir($folderUpload, 0777, true);
        }
    
        // Pindahkan file ke folder img/
        $targetFile = $folderUpload . $namaFileBaru;
        if (!move_uploaded_file($tmpName, $targetFile)) {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Data Admin Gagal Diganti: ' . error_get_last()['message']
                ];
            return false; 
        }
    
        return $namaFileBaru;
    }
// function untuk mengubah data film
    function ubahFilm($data) {
        global $conn;
        // ambil data dari tiap elemen dalam form
        $id = $data["id"];
        $judul = htmlspecialchars($data["judul"]);
        $genre = htmlspecialchars($data["genre"]);
        $harga = htmlspecialchars($data["harga"]);
        $rating = htmlspecialchars($data["rating"]);
        $tahun = htmlspecialchars($data["tahun"]);
        $gambarLama = htmlspecialchars($data["gambarLama"]);

        // cek jika user pilih gambar baru
    if ($_FILES['gambar']['error'] == 4) {
        $gambar = $gambarLama;
        } else {
            $gambar = uploadGambarFilm();
        }

    
    
        // query insert data
        $query = "UPDATE film SET
                    judul_film = '$judul',
                    genre = '$genre',
                    harga_sewa = '$harga',
                    rating = '$rating',
                    gambar = '$gambar',
                    tahun_rilis = '$tahun'
                    WHERE film_id = $id";


                    
    
                    //$result = mysqli_query( $conn, $query );
                   mysqli_query($conn, $query);

                   if (mysqli_query($conn, $query)) {
                    $_SESSION['notification'] = [
                        'type' => 'primary',
                        'message' => 'Data Film Berhasil Diganti'
                    ];
                } else {
                    $_SESSION['notification'] = [
                        'type' => 'danger',
                        'message' => 'Data Film Gagal Diganti: ' . mysqli_error($conn)
                        ];
                }
    
                   return mysqli_affected_rows($conn);
    }
// function untuk menghapus film
    function hapusFilm($id) {
        global $conn;
        mysqli_query($conn,"DELETE FROM film WHERE film_id = $id");
        return mysqli_affected_rows($conn);
    }
// function untuk menambah data customer
    function tambahCustomer($data) {
        global $conn;
        // ambil data dari tiap elemen dalam form
        $order = htmlspecialchars($data["order"]);
        $nama = htmlspecialchars($data["nama"]);
        $email = htmlspecialchars($data["email"]);
        $phone = htmlspecialchars($data["nomor"]);
        $status = htmlspecialchars($data["status"]);
    
    
        // query insert data
        $stmt = $conn->prepare("INSERT INTO customers (order_id, customer_name, customer_email, customer_phone, transaction_status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $order, $nama, $email, $phone, $status);
        $stmt->execute();
        $affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $affected_rows;

    }
 //   function untuk menghapus customer
    function hapusCustomer($id) {
        global $conn;
        mysqli_query($conn,"DELETE FROM customers WHERE id = $id");
        return mysqli_affected_rows($conn);
    }
//   function untuk mengubah data customer
    function ubahCustomer($data) {
        global $conn;
        // ambil data dari tiap elemen dalam form
        $id = $data["id"];
        $order = htmlspecialchars($data["order"]);
        $nama = htmlspecialchars($data["nama"]);
        $email = htmlspecialchars($data["email"]);
        $phone = htmlspecialchars($data["nomor"]);
        $status = htmlspecialchars($data["status"]);

        // query insert data
        $query = "UPDATE customers SET
                    order_id = '$order',
                    customer_name = '$nama',
                    customer_email = '$email',
                    customer_phone = '$phone',
                    transaction_status = '$status'
                    WHERE id = $id";

                   mysqli_query($conn, $query);
    
                   return mysqli_affected_rows($conn);
    }
    //   function untuk menghapus data transaksi
    function hapusTransaksi($id) {
        global $conn;
        mysqli_query($conn,"DELETE FROM transactions WHERE penyewaan_id = $id");
        return mysqli_affected_rows($conn);
    }
    
?>



