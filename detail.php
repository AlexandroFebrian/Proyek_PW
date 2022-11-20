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
        $kc_stock = $result["kc_stock"];
        $co_id = $result["co_id"];
        $co_link = $result["co_link"];
        $br_name = $result["br_name"];
    }

    // if (isset($_POST["keranjang"])) {
    //     if (isset($_SESSION["auth_user_id"])) {
    //         $items = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cart WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'"));
    //         $qty = $_POST["qtyhidden"];
    //         if ($qty == "") {
    //             $qty = 1;
    //         }
    //         if (isset($items[0])) {
    //             $query = mysqli_query($conn, "UPDATE cart SET ca_qty = '" . $qty + $items["ca_qty"] . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
    //             $query = mysqli_query($conn, "UPDATE cart SET ca_subtotal = '" . ($qty + $items["ca_qty"]) * $kc_price . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
    //         } else {
    //             $query = mysqli_query($conn, "INSERT INTO cart VALUES('" . $_SESSION["auth_user_id"] . "', '$co_id', '$qty', '" . $qty * $kc_price . "')");
    //         }
    //         header("Location: detail.php?id=$kc_id&color=$co_id");
    //     } else {
    //         header("Location: login.php");
    //     }
    // }
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
                    <div class="card text-center" style="border: none;">
                        <img src='<?= $co_link ?>' class="card-img-top w-50 mx-auto">
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
                        <span>Stok : <?= $kc_stock ?></span>
                        <input type="hidden" id="stock" value='<?= $kc_stock ?>'>
                        <div class="d-flex justify-content-center mt-3">
                            <div class="d-inline-block p-0 m-0" style="border: 2px gray solid; border-radius:5px; border-spacing: 0px;">
                                <button type="button" class="btn" onclick="Kurang()" style="border-right:2px gray solid; border-radius:0px;">-</button>
                                <span id='qty' class="px-3" style="font-size:16px; width: 50px;" name="qty">1</span>
                                <input type="hidden" name="qtyhidden" value="1" id="qtyhidden">
                                <button type="button" class="btn" onclick="Tambah()" style="border-left: 2px gray solid;border-radius:0px;">+</button>
                            </div>
                            <button class="btn btn-success fw-bold ms-4" type="button" onclick="add_cart()" data-bs-toggle="modal" data-bs-target="#exampleModal">+ Keranjang</button>
                        </div>
                    </div>
            <?php
                }
            ?>
            
        </div>

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