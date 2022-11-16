<?php
    require_once("connection.php");

    $_SESSION["gender"] = "A";
    $cart_item = [];
    
    if (isset($_POST["logout"])) {
        unset($_SESSION["auth_user_id"]);
        header("Location: index.php");
    }

    if (isset($_POST["search-btn"])) {
        $br_name = $_POST["search-val"];
        $co_id = explode('-', $_POST["search-val"]);
        $co_id = $co_id[count($co_id) - 1];
    }

    if (isset($_SESSION["auth_user_id"])) {
        $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "'");
        while ($row = mysqli_fetch_array($query)) {
            $cart_item[] = $row;
        }
    }

    if (isset($_POST["remove"])) {
        $us_id = explode("~", $_POST["remove"])[0];
        $co_id = explode("~", $_POST["remove"])[1];
        $query = mysqli_query($conn, "DELETE FROM cart WHERE ca_us_id = '$us_id' AND ca_co_id = '$co_id'");
        header("Location: cart.php");
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
    <script src="jshome.js"></script>
</head>
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
                            <?php
                                if (!isset($_SESSION["auth_user_id"])) {
                            ?>
                                <a class="nav-link d-block d-lg-none" role="button" href="login.php">Masuk</a>
                                <a class="nav-link d-block d-lg-none" role="button" href="register.php">Daftar</a>
                            <?php
                                } else {
                            ?>
                                <button class="btn btn-danger d-block d-lg-none rounded-3" type="submit" name="logout">Logout <img class="text-white" src="storage/icons/logout.ico" width="20px"></button>
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

        <!-- Keranjang kosong -->
        <div class="container-fluid text-center">
            <?php
                if (count($cart_item) == 0) {
            ?>
                <div class="container-fuild">
                    <img src="storage/icons/empty.png" class="mt-5" width="150px">
                    <h2 class="mt-2">Keranjangmu masih kosong nih</h2>
                    <p class="mt-2">Yuk, isi keranjangmu dengan kacamata favoritmu!</p>
                    <button class="btn btn-success mt-2 fw-bold" formaction="product.php">Mulai Belanja</button>
                </div>
            <?php
                } else {
                    $qtytotal = 0;
                    $hargatotal = 0;
                    for ($i = 0; $i < count($cart_item); $i++) {
                        $qtytotal += $cart_item[$i]["ca_qty"];
                        $hargatotal += $cart_item[$i]["ca_subtotal"];
                        $co_id = $cart_item[$i]["ca_co_id"];
            ?>
                        <div class="row justify-content-center border rounded-4 p-5">
                            <div class="col-6 col-lg-4">
                                <img src='<?= $cart_item[$i]["co_link"] ?>' class="card-img-top">
                            </div>
                            <div class="col-12 col-lg-4 mt-5">
                                <div class="card-body">
                                    <h4 class="card-title"><?= $cart_item[$i]["br_name"] ?></h4>
                                    <p class="card-text fs-5"><?= "SKU-" . $cart_item[$i]["co_id"] ?>
                                    <br><?= "Rp " . number_format($cart_item[$i]["kc_price"], 0, "", ",") ?></p>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4 mt-5">
                                <p>Stok : <?= $cart_item[$i]["kc_stock"] ?></p>
                                <div class="d-flex justify-content-center">
                                     <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                                        <button type="button" class="btn" onclick="xixixiKurang(this)" value='<?= $co_id ?>' style="border-right:2px gray solid; border-radius:0px;">-</button>
                                        <span id='<?= $co_id ?>' class="px-3" style="font-size:16px;"><?= $cart_item[$i]["ca_qty"] ?></span>
                                        <!-- <input type="hidden" name="kuantiti" value="0" id="kuantitiHidden<?=$ctr?>"> -->
                                        <button type="button" class="btn" onclick="xixixiTambah(this)" value='<?= $co_id ?>' style="border-left: 2px gray solid;border-radius:0px;">+</button>
                                    </div>
                                    <!-- <input class="form-control text-center w-25" type="number" value='<?= $cart_item[$i]["ca_qty"] ?>' min="1" max="<?= $cart_item[$i]["kc_stock"] ?>" step="1" name="qty"> -->
                                    <button class="btn btn-danger fw-bold w-50 ms-3" type="submit" name="remove" value='<?= $cart_item[$i]["ca_us_id"] . "~" . $cart_item[$i]["ca_co_id"] ?>'>Remove</button>
                                </div>
                                <h5 class="mt-4">Total : <?= "Rp " . number_format($cart_item[$i]["ca_subtotal"], 0, "", ",") ?></h5>
                            </div>
                        </div>
            <?php
                    }
            ?>
                    <div class="row justify-content-center border rounded-4 pb-5">
                        <div class="d-none d-lg-block col-lg-8"></div>
                        <div class="col-12 col-lg-4">
                            <h5 class="mt-4">Qty Total : <?= "Rp " . number_format($qtytotal, 0, "", ",") ?></h5>
                            <h5 class="mt-4">Harga Total : <?= "Rp " . number_format($hargatotal, 0, "", ",") ?></h5>
                            <button class="btn btn-success px-5">Beli</button>
                        </div>
                    </div>
            <?php
                }
            ?>
        </div>
    </form>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>
    function xixixiTambah(obj){
        let t = document.getElementById(obj.value);
        //let x = document.getElementById("kuantitiHidden"+id);
        jmlh = t.innerHTML;
        jmlh = parseInt(jmlh);
        jmlh++;
        //x.value = jmlh;
        t.innerHTML = jmlh;
    }

    function xixixiKurang(obj){
        let t = document.getElementById(obj.value);
        //let x = document.getElementById("kuantitiHidden"+id);
        jmlh = t.innerHTML;
        jmlh = parseInt(jmlh);
        jmlh--;
        if(jmlh < 1){
            jmlh = 1;
        }
        //x.value = jmlh;
        t.innerHTML = jmlh;
    }
</script>
</html>