<?php
    require_once("connection.php");
    
    $msg = "";

    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($username != "" && $password != "") {
            $query = "SELECT * FROM users WHERE us_username LIKE ? OR us_email LIKE ?";
            $searching = $conn -> prepare($query);
            $searching -> bind_param("ss", $username, $username);
            $searching -> execute();
            $searching = $searching -> get_result();
            $searching = mysqli_fetch_array($searching);
            if (isset($searching[0])) {
                if (password_verify($password, $searching["us_password"])) {
                    if ($searching["us_status"] == 1) {
                        $_SESSION["auth_user_id"] = $searching["us_id"];
                        header("Location: index.php");
                    } else {
                        $msg = "Akun anda telah diblokir, hubungi kontak kami untuk info lebih!";
                    }
                } else {
                    $msg = "Password salah";
                }
            } else {
                $msg = "Username tidak terdaftar";
            }
        } else {
            $msg = "Semua field harus diisi!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optik Primadona</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<style>
    ul{
        list-style-type: none;
    }
</style>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white p-3 position-sticky top-0 w-100 border-bottom" style="z-index: 5;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><h4 class="m-0">Optik Primadona</h4></a>
        </div>
    </nav>

    <div class="container w-75 bg-white p-5" style="height: 495px;">
        <h2 class="fw-bold">Login</h2>
        <form method="POST">
            <div class="my-3">
                <input type="text" class="form-control bg-light" placeholder="Username / Email" name="username">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control bg-light" placeholder="Password" name="password">
            </div>
            <?php
                if ($msg != "") {
                    echo "<label class='form-label text-danger'>$msg</label><br>";
                }
            ?>
            <button type="submit" class="btn btn-success w-100" name="login">Login</button>
            <div class="mb-3">
                <label class="form-label">No account? <a href="register.php">Register</a></label>
            </div>
        </form>
    </div>
    <footer class="mt-5 bg-secondary bg-opacity-25">
        <div class="container-xxl pt-5">
            <div class="row">
                <div class="col-xxl-3 col-md-4 d-md-block d-none">
                    <div class="d-flex justify-content-center">
                        <ul>
                            <li><h6>Pencarian Produk</h6></li>
                            <li>Kacamata</li>
                            <li>Daftar Brand</li>
                            <li>Harga kacamata</li>
                        </ul>
                    </div>
                    
                </div>
                <div class="col-xxl-3 col-md-4 col-6">
                    <div class="d-flex justify-content-center">
                        <ul>
                            <li><h6>Optik Primadona</h6></li>
                            <li>Tentang kami</li>
                            <li>Mencari toko</li>
                            <li>Daftar layanan</li>
                            <li></li>
                            <li></li>
                        </ul>

                    </div>

                </div>
                <div class="col-xxl-3 col-md-4 col-6">
                    <div class="d-flex justify-content-center">
                        <ul>
                            <li><h6>Bantuan dan Panduan</h6></li>
                            <li>FAQ</li>
                            <li>Syarat dan Ketentuan</li>
                            <li>Kebijakan Privasi</li>
                            <li>Mitra</li>
                            <li>Metode Pembayaran</li>
                        </ul>

                    </div>

                </div>
                <div class="col-xxl-3 col-lg-12 text-center text-xxl-start">
                    <div class="d-flex justify-content-center">
                        <ul>
                            <li><h6>CONTACT US</h6></li>
                            <li><h5>031-5231452</h5></li><br>
                            <li><h6>E-MAIL</h6></li>
                            <li><h5>optikprimadona@official.co.id</h5></li>
                        </ul>

                    </div>

                </div>
            </div>
        
        
        </div>
        <div class="container-xxl pb-5 mt-2">
            <div class="justify-content-center d-flex">
                <a href="#">
                    <img src="storage/icons/facebook.webp" alt="" style="width: 20px;" class="mx-2">
                </a>
                <a href="#">
                    <img src="storage/icons/instagram.png" alt="" style="width: 20px;" class="mx-2">
                </a>
                <a href="#">
                    <img src="storage/icons/twitter.png" alt="" style="width: 20px;" class="mx-2">
                </a>
            </div>
            <hr>
            <div class="text-secondary text-center">Disclaimer cuman tugas proyek kuliah</div>
        </div>
    </footer>
    <script src="script/bootstrap.bundle.min.js"></script>
</body>
</html>