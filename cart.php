<?php
    require_once("connection.php");

    $_SESSION["gender"] = "A";
    $cart_item = [];
    $sukses = false;
    
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

    if (isset($_POST["beli"]) && count($cart_item) != 0) {
        /* HTRANS */
        // Generate ID
        $query = mysqli_query($conn, "SELECT MAX(SUBSTR(ht_id, 3)) FROM htrans");
        $new_htrans_id = "HT" . str_pad(mysqli_fetch_array($query)[0] + 1, 4, "0", STR_PAD_LEFT);

        // Generate Invoice
        $new_invoice = "OP";
        $new_invoice .= str_pad(date("y"), 2, "0", STR_PAD_LEFT);
        $new_invoice .= str_pad(date("m"), 2, "0", STR_PAD_LEFT);
        $new_invoice .= str_pad(date("d"), 2, "0", STR_PAD_LEFT);

        $query = mysqli_query($conn, "SELECT MAX(SUBSTR(ht_invoice, 9) + 1) FROM htrans WHERE ht_invoice LIKE '$new_invoice%'");
        if (!$row = mysqli_fetch_array($query)[0]) {
            $new_invoice .= "001";
        } else {
            $new_invoice .= str_pad($row, 3, "0", STR_PAD_LEFT);
        }

        // Get Total
        $query = mysqli_query($conn, "SELECT SUM(ca_subtotal) FROM cart WHERE ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
        $total = mysqli_fetch_array($query)[0];

        //Insert HTrans
        $query = mysqli_query($conn, "INSERT INTO htrans VALUES('$new_htrans_id', '" . date("Y-m-d h:i:s") . "', '$new_invoice', '$total', '1', '" . $_SESSION["auth_user_id"] . "')");

        /* DTRANS */
        // Insert DTrans
        foreach ($cart_item as $key => $value) {
            $query = mysqli_query($conn, "INSERT INTO dtrans VALUES('" . $value["co_id"] . "', '" . $value["ca_qty"] . "', '" . $value["ca_subtotal"] . "', '$new_htrans_id')");
        }

        // Clear Cart
        $query = mysqli_query($conn, "DELETE FROM cart WHERE ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
        $cart_item = [];

        $sukses = true;
        unset($_POST["beli"]);
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
<body onload="load()" onclick="hide_popup()">
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
                            <form method="POST">
                                <button class="btn btn-danger d-block d-lg-none rounded-3 mt-3" type="submit" name="logout">Logout <img class="text-white" src="storage/icons/logout.ico" width="20px"></button>
                            </form>
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
                <form method="POST">
                    <button class="btn btn-danger mx-3 d-none d-lg-block rounded-3" type="submit" name="logout">Logout <img class="text-white" src="storage/icons/logout.ico" width="20px"></button>
                </form>
            <?php
                }
            ?>
        </div>
    </nav>

    <!-- Print berhasil beli -->
    <div id="popup" class="shadow text-center bg-white rounded-4 position-fixed top-50 start-50" style="width: 350px; height: 400px; border: 1px solid black; margin-top: -200px; margin-left: -175px; display: 
    <?php
        if ($sukses == true) {
            echo "block";
        } else {
            echo "none";
        }
    ?>;">
        <?php
            $query = mysqli_query($conn, "SELECT us_name FROM users WHERE us_id = '" . $_SESSION["auth_user_id"] . "'");
            $us_name = mysqli_fetch_array($query)[0];
            $us_name = strtok($us_name, " ");
        ?>
        <h3 class="fw-bold mt-5">Terimakasih <?= $us_name ?><br>Sudah Berbelanja</h5><br>
        <img src="storage/icons/success.png" width="100px">
        <p class="mt-4">Invoice : <?= $new_invoice ?></p>
        <p>Email : optikprimadona@official.co.id</p>
        <p>Phone : (031) 5231452</p>
    </div>

    <!-- Print keranjang kosong / isi keranjang -->
    <div class="container-fluid text-center" id="isicart"></div>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Apakah anda ingin menghapus kacamata ini dari keranjang?</h1>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="condelete" data-bs-dismiss="modal" onclick="delete_cart(this)">Hapus</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                </div>
            </div>
        </div>
    </div>
    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>
    isicart
    function load(){
        isicart = document.getElementById("isicart")
        
        fetch_isicart();
        fetch_cart();
    }

    function Tambah(obj){
        // let t = document.getElementById(obj.value);
        // //let x = document.getElementById("kuantitiHidden"+id);
        // jmlh = t.innerHTML;
        // jmlh = parseInt(jmlh);
        // jmlh++;
        // //x.value = jmlh;
        // t.innerHTML = jmlh;

        r = new XMLHttpRequest();
        r.onreadystatechange = function() {
            if ((this.readyState==4) && (this.status==200)) {
                fetch_isicart(); 
            }
        }
        
        // POST ke file user_insert.php
        r.open('POST', 'editcart.php');	
        // Pakai ini kalau data dalam bentuk string, kalau json beda lagi
        r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        r.send(`id=${obj.value}&ganti=+`);
    }

    function Kurang(obj){
        // let t = document.getElementById(obj.value);
        // //let x = document.getElementById("kuantitiHidden"+id);
        // jmlh = t.innerHTML;
        // jmlh = parseInt(jmlh);
        // jmlh--;
        // if(jmlh < 1){
        //     jmlh = 1;
        // }
        // //x.value = jmlh;
        // t.innerHTML = jmlh;

        r = new XMLHttpRequest();
        r.onreadystatechange = function() {
            if ((this.readyState==4) && (this.status==200)) {
                fetch_isicart(); 
            }
        }
        
        // POST ke file user_insert.php
        r.open('POST', 'editcart.php');	
        // Pakai ini kalau data dalam bentuk string, kalau json beda lagi
        r.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        r.send(`id=${obj.value}&ganti=-`);
    }

    function fetch_isicart(){
        r = new XMLHttpRequest();
        r.onreadystatechange = function() {
            if ((this.readyState==4) && (this.status==200)) {
                isicart.innerHTML = this.responseText;
            }
        }
        
        r.open('GET', 'isicart.php');
        r.send();
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

    function confirm_delete(obj) {
        document.getElementById("condelete").value = obj.value;
    }

    function delete_cart(obj) {
        co_id = obj.value;
        ajax_func('GET', `deletecart.php?co_id=${co_id}`, refresh_table);
    }

    function refresh_table(xhttp) {
        if ((xhttp.readyState==4) && (xhttp.status==200)) {
            fetch_isicart();
            fetch_cart();
        }
    }

    function hide_popup() {
        $('#popup').hide();
    }
</script>
</html>