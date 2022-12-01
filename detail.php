<?php
    require_once("connection.php");

    if (isset($_POST["logout"])) {
        unset($_SESSION["auth_user_id"]);
        header("Location: index.php");
    }

    if (isset($_POST["search-btn"])) {
        $br_name = $_POST["search-val"];
        $co_id = explode('-', $_POST["search-val"]);
        $co_id = $co_id[count($co_id) - 1];
    }

    $kc_id = "KC001";
    $co_id = "CO0001";
    if (isset($_GET["id"])) {
        $kc_id = $_GET["id"];
        $max_kc_id = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(SUBSTR(kc_id, 3)) FROM kacamata"))[0];
        if (substr($kc_id, 2) < 1 || substr($kc_id, 2) > $max_kc_id || substr($kc_id, 0, 2) != "KC" || strlen($kc_id) != 5) {
            header("Location: detail.php?id=KC001");
        }

        $color = mysqli_query($conn, "SELECT CONCAT('CO', LPAD(MIN(SUBSTR(co_id, 3)), 4, '0')) FROM color WHERE co_kc_id = '$kc_id'");

        if (isset($_GET["color"])) {
            $co_id = $_GET["color"];
            $max_co_id = mysqli_fetch_array(mysqli_query($conn, "SELECT MAX(SUBSTR(co_id, 3)) FROM color"))[0];
            if (substr($co_id, 2) < 1 || substr($co_id, 2) > $max_co_id || substr($co_id, 0, 2) != "CO" || strlen($co_id) != 6) {
                header("Location: detail.php?id=KC001");
            }
        } else {
            $co_id = mysqli_fetch_array(mysqli_query($conn, "SELECT co_id FROM color WHERE co_kc_id = '" . $_GET["id"] . "'"))[0];
        }
    }

    if ($kc_id != "") {
        $result = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id WHERE kc_id = '$kc_id' AND co_id = '$co_id'"));
        $kc_id = $result["kc_id"];
        $kc_price = $result["kc_price"];
        $kc_gender = $result["kc_gender"];
        $kc_weight = $result["kc_weight"];
        $co_stock = $result["co_stock"];
        $co_status = $result["co_status"];
        $co_id = $result["co_id"];
        $co_link = $result["co_link"];
        $br_name = $result["br_name"];
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
    <link rel="stylesheet" href="css/stylehome.css">
</head>
<style>
    ul{
        list-style-type: none;
    }

    .form{
        padding-bottom: 600px;
    }
    
    @media screen and (min-width: 1400px) {
        .form{
            padding-bottom: 370px;
        }
    }
</style>
<body onload="load_ajax()">
    <form method="POST" class="position-relative form" style="min-height: 100vh;">
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
                    <a href="cart.php" id="qtycart">
                        <!-- pake ajax -->
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

        <div class="container-fluid">
            <!-- Detail -->
            <?php
                if ($kc_id != "") {
            ?>
                <div class="row mt-4">
                    <div class="col-12 col-lg-4">
                        <div class="card text-center" style="border: none;">
                            <img src='<?= $co_link ?>' class="card-img-top w-50 mx-auto">
                            <ul class="pagination d-flex justify-content-center">
                                <?php
                                    $color = mysqli_query($conn, "SELECT * FROM color WHERE co_kc_id = '" . $kc_id . "'");
                                    $ctr = 1;
                                    while ($row = mysqli_fetch_array($color)) {
                                ?>
                                    <li class="page-item"><a class="page-link text-black" href="detail.php?id=<?= $kc_id ?>&color=<?= $row["co_id"] ?>"><?= $ctr++ ?></a></li>
                                <?php
                                    }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="card text-start" style="border: none;">
                            <div class="card-body">
                                <h4 class="card-title"><?= $br_name ?></h4>
                                <p class="fs-4 fw-bold"><?= "Rp " . number_format($kc_price, 0, "", ",") ?></p>
                                <hr>
                                <p class="card-text"><b>Pilihan warna : </b><?= $co_id ?></p>
                                <hr>
                                <b>Detail</b>
                                <hr>
                                <p>
                                    Kondisi : <b class="fw-semibold">Baru</b><br>
                                    Berat : <b class="fw-semibold"><?= $kc_weight ?> g</b><br>
                                    Gender : <b class="fw-semibold"><?php if ($kc_gender == "M") {echo "Men";} else {echo "Women";} ?></b><br>
                                </p>
                                <p>Kacamata produksi prima<br> Produk berkualitas dari brand <?= $br_name ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card text-start p-4">
                            <h6 class="fw-bold">Atur Jumlah</h6>
                            <div class="d-flex mt-2" style="align-items: center;">
                                <img src='<?= $co_link ?>' width="15%">
                                <p class="card-text ms-3"><?= $co_id ?></p>
                            </div>
                            <hr>
                            <input type="hidden" id="stock" value='<?= $co_stock ?>'>
                            <div class="d-flex justify-content-start mt-3 flex-wrap" style="align-items: center;">
                                <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                                    <button type="button" class="btn" onclick="Kurang()" style="border-right:2px gray solid; border-radius:0px;" <?php if ($co_status == 0) echo 'disabled' ?>>-</button>
                                    <span id='qty' class="px-3" style="font-size:16px; width: 50px;" name="qty">1</span>
                                    <input type="hidden" name="qtyhidden" value="1" id="qtyhidden">
                                    <button type="button" class="btn" onclick="Tambah()" style="border-left: 2px gray solid;border-radius:0px;" <?php if ($co_status == 0) echo 'disabled' ?>>+</button>
                                </div>
                                <span class="ms-2 <?php if ($co_stock == 0 || $co_status == 0) echo 'text-danger' ?>"><?php if ($co_stock >= 0 && $co_status == 1) {echo "Stok : $co_stock";} else {echo "Produk sedang nonaktif";} ?></span>
                                <?php
                                    if (isset($_SESSION["auth_user_id"])) {
                                ?>
                                        <button class="btn btn-success fw-bold mt-3 w-100" type="button" onclick="add_cart()" data-bs-toggle="modal" data-bs-target="#exampleModal" <?php if ($co_stock == 0 || $co_status == 0) echo 'disabled' ?>>+ Keranjang</button>
                                <?php
                                    } else {
                                ?>
                                        <button class="btn btn-success fw-bold mt-3 w-100" formaction="login.php" <?php if ($co_stock == 0 || $co_status == 0) echo 'disabled' ?>>+ Keranjang</button>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
            
        </div>

        <footer class="mt-5 bg-secondary bg-opacity-25 position-absolute bottom-0 w-100">
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

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Berhasil menambahkan kacamata ke keranjang</h1>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Lanjut Belanja</button>
                        <a href="cart.php"><button type="button" class="btn btn-secondary">Lihat Keranjang</button></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>
    // $("[type='number']").keypress(function (evt) {
    //      evt.preventDefault();
    //     // if($("[type='number']").val == ""){
    //     //     $("[type='number']").val = 1;
    //     // }
    // });
    function Tambah(obj){
        let t = document.getElementById("qty");
        let s = document.getElementById("stock");
        stock = s.value;
        stock = parseInt(stock);
        let q = document.getElementById("qtyhidden");
        
        //let x = document.getElementById("kuantitiHidden"+id);
        jmlh = t.innerHTML;
        jmlh = parseInt(jmlh);
        jmlh++;
        if(jmlh > stock){
            jmlh = stock;
        }
        //x.value = jmlh;
        t.innerHTML = jmlh;
        q.value = jmlh;
    }

    function Kurang(obj){
        let t = document.getElementById("qty");
        
        //let x = document.getElementById("kuantitiHidden"+id);
        jmlh = t.innerHTML;
        jmlh = parseInt(jmlh);
        jmlh--;
        if(jmlh < 1){
            jmlh = 1;
        }
        //x.value = jmlh;
        t.innerHTML = jmlh;
        q.value = jmlh;
    }

    function load_ajax() {
        fetch_cart();
    }

    function fetch_cart() {
        r = new XMLHttpRequest();
        
        r.onreadystatechange = function() {
            if ((this.readyState==4) && (this.status==200)) {
                document.getElementById("qtycart").innerHTML = this.responseText;
            }
        }
        
        r.open('GET', `tambahkeranjang.php`);
        r.send();
    }

    function ajax_func(method, url, callback, data = "") {
        r = new XMLHttpRequest();
        r.onreadystatechange = function() {
            callback(this);
        }
        r.open(method, url);
        if (method.toLowerCase() == "POST") {
            r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        }
        r.send(data);
    }

    function add_cart() {
        qty = document.getElementById("qtyhidden").value;
        ajax_func('GET', `tambahkeranjang.php?co_id=<?= $co_id ?>&qty=${qty}&kc_price=<?= $kc_price ?>`, refresh_table);
    }

    function refresh_table(xhttp) {
        if ((xhttp.readyState==4) && (xhttp.status==200)) {
            fetch_cart();
        }
    }
</script>
</html>