<?php
    require_once("connection.php");
    
    $cart_item = [];
    $transaksi = [];

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
    } else {
        header("Location: index.php");
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
    <script src="jshome.js"></script>
</head>
<body onload="load_ajax()">
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

        <div class="container-fluid">
            <!-- Detail -->
            <?php
                if (count($transaksi) != 0) {
            ?>
                    <div class="card p-5">
                        <h5 class="fw-bold m-0">Selesai</h5>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p>No. Invoice</p>
                            </div>
                            <div class="col-6 text-end">
                                <p class="fw-bold text-success"><?= $transaksi[0]["ht_invoice"] ?></p>
                            </div>
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
                } else {
                    echo "<h1 class='text-center fw-bold mt-5 text-danger'>404 NOT FOUND</h1>";
                }
            ?>
            
        </div>

        <!-- Footer -->
        <footer>
          <div class="bg-dark mt-5" id="scrollspyHeading5">
            <div class="container-fluid bg-dark pt-3 pb-2 text-white">
              <div class="container">
                <div class="row">
                  <div class="col-12 col-lg-6 mb-4">
                    <h2 class="fw-bold text-center">Send us Mail!</h2>
                    <form action="halaman2.html">
                      <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                      </div>
                      <div class="mb-3">
                        <label class="form-label">Mail</label>
                        <textarea name="mail" cols="30" rows="8" class="form-control"></textarea>
                      </div>
                      <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Let us send you an email</label>
                      </div>
                      <button type="submit" class="btn btn-primary fw-bold">Submit</button>
                    </form>
                  </div>
                  <div class="col-12 col-lg-6 text-center mt-auto mb-auto">
                    <p>
                      <a class="btn btn-success fw-bold" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Customer Care
                      </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                      <div class="card card-body text-success fw-bold mb-3">
                        Email : careprimadona@care.co.id
                      </div>
                    </div>
                    <p>
                      <a class="btn btn-danger fw-bold" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Contact Person
                      </a>
                    </p>
                    <div class="collapse" id="collapseExample">
                      <div class="card card-body text-danger fw-bold">
                        Email : optikprimadona@official.co.id <br>
                        Phone : (031) 5231452
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <p class="text-center fw-bold">Copyright &copy; 2021 Optik Primadona, Inc.</p>
              </div>
            </div>
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
</html>