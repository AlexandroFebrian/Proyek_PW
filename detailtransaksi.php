<?php
    require_once("connection.php");
    require_once(dirname(__FILE__) . '/midtrans.php');

    \Midtrans\Config::$serverKey = "SB-Mid-server-vKCiBWxuxq4MuWn5dbexzL2u";

    $cart_item = [];
    $transaksi = [];
    $status = [];

    if (!isset($_SESSION["auth_user_id"])) {
        header("Location: index.php");
    }

    if (isset($_POST["logout"])) {
        unset($_SESSION["auth_user_id"]);
        header("Location: index.php");
    }

    $ht_id = "";
    if (isset($_GET["ht_id"])) {
        $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "'");
        while ($row = mysqli_fetch_array($query)) {
            $cart_item[] = $row;
        }

        $ht_id = $_GET["ht_id"];
        $query = mysqli_query($conn, "SELECT * FROM htrans JOIN dtrans ON dt_ht_id = ht_id JOIN users ON ht_us_id = us_id JOIN color ON dt_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ht_us_id = '" . $_SESSION["auth_user_id"] . "' AND ht_id = '$ht_id'");
        while ($row = mysqli_fetch_array($query)) {
            $transaksi[] = $row;
        }
        if (count($transaksi) != 0) {
            $order_id = $transaksi[0]["ht_order_id"];
            try {
                $status = \Midtrans\Transaction::status($order_id);
                $status = (array)$status;
                if ($status["transaction_status"] == "settlement" && $transaksi[0]["ht_status"] == 2) {
                    $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '1' WHERE ht_id = '$ht_id'");
                    header("Location: detailtransaksi.php?ht_id=$ht_id");
                }
            } catch (Exception $ex) {}
        }
    } else {
        header("Location: index.php");
    }

    if (isset($_REQUEST["condelete"])) {
        $ht_id = $_REQUEST["condelete"];
        
        $ht_id = $_GET["ht_id"];
        $query = mysqli_query($conn, "SELECT * FROM htrans JOIN dtrans ON dt_ht_id = ht_id JOIN users ON ht_us_id = us_id JOIN color ON dt_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ht_us_id = '" . $_SESSION["auth_user_id"] . "' AND ht_id = '$ht_id'");
        while ($row = mysqli_fetch_array($query)) {
            $transaksi[] = $row;
        }
        $order_id = $transaksi[0]["ht_order_id"];
        try {
            $status = \Midtrans\Transaction::status($order_id);
            $status = (array)$status;
            if ($status["transaction_status"] == "settlement" && $transaksi[0]["ht_status"] == 2) {
                $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '1' WHERE ht_id = '$ht_id'");
                header("Location: detailtransaksi.php?ht_id=$ht_id");
            } else {
                $cancel = \Midtrans\Transaction::cancel($order_id);
                $query = mysqli_query($conn, "SELECT * FROM dtrans JOIN htrans ON dt_ht_id = ht_id WHERE ht_id = '$ht_id' AND ht_status != '0'");
                while ($row = mysqli_fetch_array($query)) {
                    $update = mysqli_query($conn, "UPDATE color SET co_stock = co_stock + '" . $row["dt_qty"] . "' WHERE co_id = '" . $row["dt_co_id"] . "'");
                }
                $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '0' WHERE ht_id = '$ht_id'");
                header("Location: transaksi.php");
            }
        } catch (Exception $ex) {
            try {
                $cancel = \Midtrans\Transaction::cancel($order_id);
            } catch (Exception $ex) {}
            $query = mysqli_query($conn, "SELECT * FROM dtrans JOIN htrans ON dt_ht_id = ht_id WHERE ht_id = '$ht_id' AND ht_status != '0'");
            while ($row = mysqli_fetch_array($query)) {
                $update = mysqli_query($conn, "UPDATE color SET co_stock = co_stock + '" . $row["dt_qty"] . "' WHERE co_id = '" . $row["dt_co_id"] . "'");
            }
            $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '0' WHERE ht_id = '$ht_id'");
            header("Location: transaksi.php");
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
    </form>

    <div class="position-relative form" style="min-height: 100vh;">
        <div class="container-fluid">
            <!-- Detail -->
            <?php
                if (count($transaksi) != 0) {
            ?>
                    <div class="card p-5 mt-5">
                        <h5 class="fw-bold m-0"><?php if ($transaksi[0]["ht_status"] == 1) { echo '<b class="text-success">Selesai</b>'; } elseif ($transaksi[0]["ht_status"] == 2) { echo '<b style="color: orange;">Menunggu Pembayaran</b>'; } else { echo '<b class="text-danger">Dibatalkan</b>'; } ?></h5>
                        <hr>
                        <div class="row">
                            <?php
                                if ($transaksi[0]["ht_status"] == 1) {
                            ?>
                                    <div class="col-6">
                                        <p>No. Invoice</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="fw-bold text-success"><?= $transaksi[0]["ht_invoice"] ?></p>
                                    </div>
                            <?php
                                } else {
                            ?>
                                    <div class="col-6">
                                        <p>Order ID</p>
                                    </div>
                                    <div class="col-6 text-end">
                                        <p class="fw-bold text-success"><?= strrev(str_replace("OP", "", $transaksi[0]["ht_invoice"])) ?></p>
                                    </div>
                            <?php
                                }
                            ?>
                            <div class="col-6">
                                <p>Tanggal Pembelian</p>
                            </div>
                            <div class="col-6 text-end">
                                <p><?= date_format(date_create($transaksi[0]["ht_date"]),"d F Y, h:i A") ?></p>
                            </div>
                            <hr>
                        </div>
                        <h5 class="fw-bold">Detail Produk</h5>
                        <?php
                        $total_qty = 0;
                            for ($i = 0; $i < count($transaksi); $i++) {
                                $total_qty += $transaksi[$i]["dt_qty"];
                        ?>
                                <div class="row border ps-5 pt-4 pb-3 py-lg-0 rounded-4 mb-2 justify-content-end" style="align-items: center;">
                                    <div class="col-4 col-lg-2">
                                        <img src='<?= $transaksi[$i]["co_link"] ?>' class="card-img-top">
                                    </div>
                                    <div class="col-8 col-lg-4 text-start">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $transaksi[$i]["br_name"] ?></h5>
                                            <p class="m-0"><?= " SKU-" . $transaksi[$i]["co_id"] ?></p>
                                            <p class="m-0" style="font-size: 12px;"><?= $transaksi[$i]["dt_qty"] ?> barang x <?= "Rp " . number_format($transaksi[$i]["kc_price"], 0, "", ",") ?></p>
                                        </div>
                                    </div>
                                    <div class="col-7 me-2 col-lg-6 m-lg-0">
                                        <p>Total Harga</p>
                                        <h5><?= "Rp " . number_format($transaksi[$i]["dt_subtotal"], 0, "", ",") ?></h5>
                                    </div>
                                </div>
                        <?php
                            }
                        ?>
                        <hr>
                        <h5 class="fw-bold">Rincian Pembayaran</h5>
                        <div class="row">
                            <div class="col-6">
                                <p>Total Harga <br class="d-block d-lg-none">(<?= $total_qty ?> barang)</p>
                            </div>
                            <div class="col-6 text-end">
                                <p><?= "Rp " . number_format($transaksi[0]["ht_total"], 0, "", ",") ?></p>
                            </div>
                            <div class="col-6">
                                <p>Total Ongkos Kirim</p>
                            </div>
                            <div class="col-6 text-end">
                                <p>Rp 20,000</p>
                            </div>
                            <hr>
                            <div class="col-6">
                                <h5 class="fw-bold">Total Belanja</h5>
                            </div>
                            <div class="col-6 text-end">
                                <h5 class="fw-bold"><?= "Rp " . number_format($transaksi[0]["ht_total"] + 20000, 0, "", ",") ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php
                        if ($transaksi[0]["ht_status"] == 2) {
                    ?>
                        <div class="card p-2 mt-2">
                            <button class="btn btn-success fw-bold" onclick="updateStatus()">Bayar</button>
                            <input type="hidden" name="status" id="status" value="2">
                        </div>
                        <div class="card p-2 mt-2">
                            <button class="btn btn-danger fw-bold" onclick="confirm_delete(this)" data-bs-toggle="modal" data-bs-target="#exampleModal" value='<?= $transaksi[0]["ht_id"] ?>'>Batalkan</button>
                        </div>
                    <?php
                        }
                    ?>
            <?php
                } else {
                    echo "<h1 class='text-center fw-bold mt-5 text-danger'>404 NOT FOUND</h1>";
                }
            ?>
            
        </div>
    
        <!-- Footer -->
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

    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Apakah anda ingin membatalkan transaksi ini?</h1>
                </div>
                <div class="modal-footer">
                    <form method="POST">
                        <button type="submit" class="btn btn-danger" id="condelete" name="condelete">Batalkan Transaksi</button>
                    </form>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-TKV1zRASAGX4eBcv"></script>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>
    function updateStatus() {
        snap.pay('<?= $transaksi[0]["ht_token"] ?>', {
            onSuccess: function(result) {
                r = new XMLHttpRequest();
                r.onreadystatechange = function() {
                    if ((this.readyState==4) && (this.status==200)) {
                        location.reload();
                    }
                }
                r.open('GET', 'updatestatus.php?ht_id=<?= $transaksi[0]["ht_id"] ?>');
                r.send();
            },
            onPending: function(result) {
                location.reload();
            },
            onError: function(result) {},
            onClose: function(result) {
                location.reload();
            }
        });
    }

    function confirm_delete(obj) {
        document.getElementById("condelete").value = obj.value;
    }
</script>
</html>