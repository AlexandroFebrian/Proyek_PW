<?php
    require_once("connection.php");

    $cart_item = [];

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
        $kc_stock = $result["kc_stock"];
        $co_id = $result["co_id"];
        $co_link = $result["co_link"];
        $br_name = $result["br_name"];
    }

    if (isset($_POST["keranjang"])) {
        if (isset($_SESSION["auth_user_id"])) {
            $items = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cart WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'"));
            $qty = $_POST["qty"];
            if ($qty == "") {
                $qty = 1;
            }
            if (isset($items[0])) {
                $query = mysqli_query($conn, "UPDATE cart SET ca_qty = '" . $qty + $items["ca_qty"] . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
                $query = mysqli_query($conn, "UPDATE cart SET ca_subtotal = '" . ($qty + $items["ca_qty"]) * $kc_price . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
            } else {
                $query = mysqli_query($conn, "INSERT INTO cart VALUES('" . $_SESSION["auth_user_id"] . "', '$co_id', '$qty', '" . $qty * $kc_price . "')");
            }
            header("Location: detail.php?id=$kc_id&color=$co_id");
        } else {
            header("Location: login.php");
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

        <!-- Detail -->
        <?php
            if ($kc_id != "") {
        ?>
                <div class="col-4 text-center me-4 mb-5 mt-3">
                    <div class="card" style="width: 18rem; border: none;">
                        <img src='<?= $co_link ?>' class="card-img-top">
                        <div class="card-body">
                            <h4 class="card-title"><?= $br_name ?></h4>
                            <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                            <br><?= "Rp " . number_format($kc_price, 0, "", ",") ?></p>
                        </div>
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
                        <p>Stok : <?= $kc_stock ?></p>
                        <div class="d-flex justify-content-center">
                            <input class="form-control text-center w-25" type="number" value="1" min="1" max="<?= $kc_stock ?>" step="1" name="qty">
                            <button class="btn btn-success fw-bold w-50 ms-3" type="submit" name="keranjang">+ Keranjang</button>
                        </div>
                    </div>
                </div>
        <?php
            }
        ?>
    </form>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>
    $("[type='number']").keypress(function (evt) {
         evt.preventDefault();
        // if($("[type='number']").val == ""){
        //     $("[type='number']").val = 1;
        // }
    });
</script>
</html>