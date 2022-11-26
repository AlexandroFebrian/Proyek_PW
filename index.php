<?php
    require_once("connection.php");

    unset($_SESSION["admin"]);

    $_SESSION["gender"] = "A";    
    $cart_item = [];

    if (isset($_GET["produkbaru"])) {
        $filter = [];
        $filter[] = $_GET["produkbaru"];
        $_SESSION["filter"] = $filter;
        //echo "<script>alert('" . $_GET["produkbaru"] . "')</script>";
        header("Location: product.php");
    }

    if (isset($_SESSION["auth_user_id"])) {
        $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "'");
        while ($row = mysqli_fetch_array($query)) {
            $cart_item[] = $row;
        }
    }
    
    if (isset($_POST["logout"])) {
        unset($_SESSION["auth_user_id"]);
        header("Location: index.php");
    }

    if (isset($_POST["search-btn"])) {
        $br_name = $_POST["search-val"];
        $co_id = explode('-', $_POST["search-val"]);
        $co_id = $co_id[count($co_id) - 1];
    }

    if(isset($_SESSION["filter"]) && !isset($_GET["produkbaru"])){
        unset($_SESSION["filter"]);
    }

    if(isset($_SESSION["harga-minimum"])){
        unset($_SESSION["harga-minimum"]);
    }

    if(isset($_SESSION["harga-maximum"])){
        unset($_SESSION["harga-maximum"]);
    }

    if(isset($_SESSION["search-val"])){
        unset($_SESSION["search-val"]);
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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="css/stylehome.css">
</head>
<style>
    ul{
        list-style-type: none;
    }
</style>
<body>
    <form method="POST">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg bg-white p-3 position-sticky top-0 w-100 border-bottom shadow" style="z-index: 5;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><h4 class="m-0">Optik Primadona</h4></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" role="button" href="product.php?page=1">Semua Produk</a>
                        </li>
                        <li class="nav-item">
                            <?php
                                if (!isset($_SESSION["auth_user_id"])) {
                            ?>
                                <a class="nav-link d-block d-lg-none" role="button" href="login.php">Masuk</a>
                                <a class="nav-link d-block d-lg-none" role="button" href="register.php">Daftar</a>
                            <?php
                                } else {
                            ?>
                                <a class="nav-link" role="button" href="transaksi.php?">Transaksi</a>
                                <button class="btn btn-danger d-block d-lg-none rounded-3 mt-3" type="submit" name="logout">Logout <img class="text-white" src="storage/icons/logout.ico" width="20px"></button>
                            <?php
                                }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="input-group mb-1 container-fluid mt-4 mt-lg-0">
                <input type="text" class="form-control" placeholder="Cari brand disini" name="search-val">
                <span class="rounded-end" style="background-color: lightgray;">
                    <button class="btn" type="submit" name="search-btn" formaction="product.php"><img src="storage/icons/search.png" width="18px" class="opacity-50"></button>
                </span>
                <div class="position-relative">
                    <a href="cart.php">
                        <?php
                            if (count($cart_item) != 0) {
                        ?>
                            <p class="position-absolute bg-danger text-white fw-bold rounded-5 start-50 px-2" style="z-index: 2; font-size: 12px;"><?= count($cart_item) ?></p>
                        <?php
                            }
                        ?>
                        <img src="storage/icons/cart.png" class="mx-lg-3 mx-0 ms-3 opacity-50" width="30px">
                    </a>
                </div>
                <div class="fs-3 pb-2 opacity-75 d-none d-lg-block">|</div>
                <?php
                    if (!isset($_SESSION["auth_user_id"])) {
                ?>
                    <button class="btn btn-outline-success mx-3 d-none d-lg-block rounded-3" type="submit" formaction="login.php">Masuk</button>
                    <button class="btn btn-success me-0 me-lg-2 d-none d-lg-block rounded-3" type="submit" formaction="register.php">Daftar</button>
                <?php
                    } else {
                ?>
                    <button class="btn btn-danger mx-3 d-none d-lg-block rounded-3" type="submit" name="logout">Logout <img class="text-white" src="storage/icons/logout.ico" width="20px"></button>
                <?php
                    }
                ?>
            </div>
        </nav>

        <!-- Jumbotron -->
        <div id="carouselExampleFade" class="carousel slide carousel-fade shadow" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                <a href="product.php"><img src="storage/img/fot1.webp" class="d-block w-100" style="height: 100%;"></a>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                <a href="index.php?produkbaru=BR006"><img src="storage/img/fot2.webp" class="d-block w-100" style="height: 100%;"></a>
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                <a href="index.php?produkbaru=BR012"><img src="storage/img/fot3.webp" class="d-block w-100" style="height: 100%;"></a>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container-fluid my-4">
            <!-- For Men -->
            <div class="row ms-2">
                <div class="col">
                    <h1>For Men</h1>
                </div>
            </div>
            <div class="container-fluid py-2">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    <?php
                        $result = [];
                        $tempresult = mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id WHERE kc_gender = 'M' GROUP BY co_kc_id ORDER BY kc_price DESC LIMIT 20");
                        while ($row = mysqli_fetch_array($tempresult)) {
                            $result[] = $row;
                        }

                        for ($i = 0; $i < count($result); $i++) {
                            if (isset($result[$i])) {
                                $kc_id = $result[$i]["kc_id"];
                                $kc_price = $result[$i]["kc_price"];
                                $co_id = $result[$i]["co_id"];
                                $co_link = $result[$i]["co_link"];
                                $br_name = $result[$i]["br_name"];
                    ?>
                                <div class="card card-block mx-2 shadow" style="min-width: auto;">
                                    <div class="col text-center me-4 mb-5">
                                        <a href='<?= "detail.php?id=" . $kc_id ?>' class="text-black text-decoration-none">
                                            <div class="card" style="width: 18rem; border: none;">
                                                <img src='<?= $co_link ?>' class="card-img-top">
                                                <div class="card-body">
                                                    <h4 class="card-title"><?= $br_name ?></h4>
                                                    <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                                                    <br><?= "Rp " . number_format($kc_price) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>

            <!-- For Woman -->
            <div class="row ms-2 mt-5">
                <div class="col">
                    <h1>For Woman</h1>
                </div>
            </div>
            <div class="container-fluid py-2">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    <?php
                        $result = [];
                        $tempresult = mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id WHERE kc_gender = 'W' GROUP BY co_kc_id ORDER BY kc_price DESC LIMIT 20");
                        while ($row = mysqli_fetch_array($tempresult)) {
                            $result[] = $row;
                        }
                        
                        for ($i = 0; $i < count($result); $i++) {
                            if (isset($result[$i])) {
                                $kc_id = $result[$i]["kc_id"];
                                $kc_price = $result[$i]["kc_price"];
                                $co_id = $result[$i]["co_id"];
                                $co_link = $result[$i]["co_link"];
                                $br_name = $result[$i]["br_name"];
                    ?>
                                <div class="card card-block mx-2 shadow" style="min-width: auto;">
                                    <div class="col text-center me-4 mb-5">
                                        <a href='<?= "detail.php?id=" . $kc_id ?>' class="text-black text-decoration-none">
                                            <div class="card" style="width: 18rem; border: none;">
                                                <img src='<?= $co_link ?>' class="card-img-top">
                                                <div class="card-body">
                                                    <h4 class="card-title"><?= $br_name ?></h4>
                                                    <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                                                    <br><?= "Rp " . number_format($kc_price) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>

            <!-- Popular Brand -->
            <div class="row ms-2 mt-5">
                <div class="col">
                    <h1>Popular Brand</h1>
                </div>
            </div>
            <div class="container-fluid py-2">
                <div class="d-flex flex-row flex-nowrap overflow-auto">
                    <?php
                        $result = [];
                        $tempresult = mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id WHERE br_id = 'BR008' AND kc_price > 1600000 GROUP BY co_kc_id ORDER BY kc_price DESC LIMIT 20");
                        while ($row = mysqli_fetch_array($tempresult)) {
                            $result[] = $row;
                        }

                        for ($i = 0; $i < count($result); $i++) {
                            if (isset($result[$i])) {
                                $kc_id = $result[$i]["kc_id"];
                                $kc_price = $result[$i]["kc_price"];
                                $co_id = $result[$i]["co_id"];
                                $co_link = $result[$i]["co_link"];
                                $br_name = $result[$i]["br_name"];
                    ?>
                                <div class="card card-block mx-2 shadow" style="min-width: auto;">
                                    <div class="col text-center me-4 mb-5">
                                        <a href='<?= "detail.php?id=" . $kc_id ?>' class="text-black text-decoration-none">
                                            <div class="card" style="width: 18rem; border: none;">
                                                <img src='<?= $co_link ?>' class="card-img-top">
                                                <div class="card-body">
                                                    <h4 class="card-title"><?= $br_name ?></h4>
                                                    <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                                                    <br><?= "Rp " . number_format($kc_price) ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                    <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-5 bg-secondary bg-opacity-25">
            <div class="container-xxl pt-5 d-flex d-flex justify-content-around">
                <ul>
                    <li><h6>Pencarian Produk</h6></li>
                    <li>Kacamata</li>
                    <li>Daftar Brand</li>
                    <li>Harga kacamata</li>
                </ul>
                <ul>
                    <li><h6>Optik Primadona</h6></li>
                    <li>Tentang kami</li>
                    <li>Mencari toko</li>
                    <li>Daftar layanan</li>
                    <li></li>
                    <li></li>
                </ul>
            
                <ul>
                    <li><h6>Bantuan dan Panduan</h6></li>
                    <li>FAQ</li>
                    <li>Syarat dan Ketentuan</li>
                    <li>Kebijakan Privasi</li>
                    <li>Mitra</li>
                    <li>Metode Pembayaran</li>
                </ul>
            
                <ul>
                    <li><h6>CONTACT US</h6></li>
                    <li><h5>031-5231452</h5></li><br>
                    <li><h6>E-MAIL</h6></li>
                    <li><h5>optikprimadona@official.co.id</h5></li>
                </ul>
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
    </form>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
</html>