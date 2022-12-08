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

    if(isset($_POST["semua"]) || isset($_POST["reset-filter"])){
        unset($_SESSION["filter"]);
        unset($_SESSION["harga-minimum"]);
        unset($_SESSION["harga-maximum"]);
        unset($_SESSION["search-val"]);
        $_SESSION["gender"] = "A";
        $_SESSION["urutkan"] = "asc";
    }

    if (!isset($_SESSION["gender"])) {
        $_SESSION["gender"] = "A";
    }

    if (!isset($_SESSION["urutkan"])) {
        $_SESSION["urutkan"] = "asc";
    }
    
    $result = [];

    $query = "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id ";

    if(isset($_POST["apply-filter"])){
        unset($_SESSION["search-val"]);
    }

    if(isset($_POST["search-btn"])){
        $glitis1 = substr($_POST["search-val"], 0, 8);
        $glitis2 = substr($_POST["search-val"], 0, 5);
        if($glitis1 == "<script>" || $glitis2 == "<?php"){
            header("Location: tanganeglitis.php");
        }
        if($_POST["search-val"] != ""){
            $_SESSION["search-val"] = $_POST["search-val"];
            $query .= "WHERE br_name LIKE ? OR co_id LIKE ? ";
        }else{
            unset($_SESSION["search-val"]);
        }
    }else if(isset($_SESSION["search-val"])){
        $query .= "WHERE br_name LIKE ? OR co_id LIKE ? ";
    }
    
    if(isset($_POST["apply-filter"])){
        $filter_brand = mysqli_query($conn, "SELECT * FROM brand");
        
        $tempfilter = [];
        
        while($row = mysqli_fetch_array($filter_brand)){
            if(isset($_POST[$row["br_id"]])){
                $tempfilter[] = $row["br_id"];
            }
        }
        
        $_SESSION["filter"] = $tempfilter;
        
        if(sizeof($tempfilter) > 0){
            if(!isset($_SESSION["search-val"])){
                $query .= "WHERE (";

            }else{
                $query .= "AND (";
            }
            
            for($i = 0; $i < sizeof($tempfilter); $i++){
                $query .= "br_id = '".$tempfilter[$i]."'";
                if($i != sizeof($tempfilter)-1){
                    $query .= " OR ";
                }
            }

            $query .= ") ";
        }

        if(isset($_POST["harga-minimum"])){
            if($_POST["harga-minimum"] != ""){
                $_SESSION["harga-minimum"] = $_POST["harga-minimum"];
                if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"])){
                    $query .= "WHERE ";
                }else{
                    $query .= "AND ";
                }
                $query .= "kc_price >= ".$_POST["harga-minimum"]." ";
            }else{
                unset($_SESSION["harga-minimum"]);
            }
        }
        if(isset($_POST["harga-maximum"])){
            if($_POST["harga-maximum"] != ""){
                $_SESSION["harga-maximum"] = $_POST["harga-maximum"];
                if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"]) && !isset($_SESSION["harga-minimum"])){
                    $query .= "WHERE ";
                }else{
                    $query .= "AND ";
                }
                $query .= "kc_price <= ".$_POST["harga-maximum"]." ";
            }else{
                unset($_SESSION["harga-maximum"]);
            }
        }

        if($_POST["gender"] != "A"){
            $_SESSION["gender"] = $_POST["gender"];
            if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"]) && !isset($_SESSION["harga-minimum"]) && !isset($_SESSION["harga-maximum"])){
                $query .= "WHERE ";
            }else{
                $query .= "AND ";
            }
            $query .= "kc_gender = '".$_SESSION["gender"]."' ";
        }else{
            $_SESSION["gender"] = "A";
        }

        $query .= "GROUP BY co_kc_id";

        if($_POST["urutkan"] != "asc"){
            $_SESSION["urutkan"] = "desc";
            $query .= " ORDER BY kc_price ".$_SESSION["urutkan"]." ";
        }else{
            $_SESSION["urutkan"] = "asc";
            $query .= " ORDER BY kc_price ".$_SESSION["urutkan"]." ";
        }
    }
    else if(isset($_SESSION["filter"])){
        if(sizeof($_SESSION["filter"]) > 0){
            if(!isset($_SESSION["search-val"])){
                $query .= "WHERE (";

            }else{
                $query .= "AND (";
            }

            for($i = 0; $i < sizeof($_SESSION["filter"]); $i++){
                $query .= "br_id = '".$_SESSION["filter"][$i]."'";
                if($i != sizeof($_SESSION["filter"])-1){
                    $query .= " OR ";
                }else{
                    $query .= " ";
                }
            }

            $query .= ") ";
        }

        if(isset($_SESSION["harga-minimum"])){
            if($_SESSION["harga-minimum"] != ""){
                if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"])){
                    $query .= "WHERE ";
                }else{
                    $query .= "AND ";
                }
                $query .= "kc_price >= ".$_SESSION["harga-minimum"]." ";
            }

        }
        if(isset($_SESSION["harga-maximum"])){
            if($_SESSION["harga-maximum"] != ""){
                if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"]) && !isset($_SESSION["harga-minimum"])){
                    $query .= "WHERE ";
                }else{
                    $query .= "AND ";
                }
                $query .= "kc_price <= ".$_SESSION["harga-maximum"]." ";
            }
            
        }

        if($_SESSION["gender"] != "A"){
            if(sizeof($_SESSION["filter"]) == 0 && !isset($_SESSION["search-val"]) && !isset($_SESSION["harga-minimum"]) && !isset($_SESSION["harga-maximum"])){
                $query .= "WHERE ";
            }else{
                $query .= "AND ";
            }
            $query .= "kc_gender = '".$_SESSION["gender"]."' ";

        }

        $query .= "GROUP BY co_kc_id";

        if($_SESSION["urutkan"] != "asc"){
            $query .= " ORDER BY kc_price ".$_SESSION["urutkan"]." ";
        } else {
            $query .= " ORDER BY kc_price ".$_SESSION["urutkan"]." ";
        }

    }
    
    if (!str_contains($query, "GROUP BY")) {
        $query .= "GROUP BY co_kc_id";
    }
    
    $tempresult = $conn->prepare($query);
    if(isset($_SESSION["search-val"])){
        $search = "%".$_SESSION["search-val"]."%";
        $tempresult->bind_param('ss', $search, $search);
    }
    $tempresult->execute();

    $exeresult = $tempresult->get_result();
    while ($row = mysqli_fetch_array($exeresult)) {
        $result[] = $row;
    }

    $page = 1;
    $maxpage = intval(count($result) / 30) + 1;
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }

    if ($page < 1) {
        header("Location: product.php?page=1");
    } else if ($page > $maxpage) {
        header("Location: product.php?page=$maxpage");
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
    <link rel="stylesheet" href="css/product.css">
</head>
<style>
    .form{
        padding-bottom: 550px;
    }
    
    @media screen and (min-width: 1400px) {
        .form{
            padding-bottom: 370px;
        }
    }
</style>
<body>
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
        
        <div class="container-fluid pt-4">

            <div class="row d-flex justify-content-center">
                <!-- FILTER WIDTH BESAR -->
                <div class="" style="width: 320px;">
                    <h2 class="mt-2 ms-3">Filter</h2>
                    <div class="border">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <!-- BRAND -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button collapsed rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                    Brand
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse border-bottom rounded-0" aria-labelledby="panelsStayOpen-headingOne" data-bs-parent="#accordionpanelsStayOpenExample">
                                    <div class="accordion-body pb-0">
                                        <!-- MUNCULIN BRAND -->
                                        <ul>
                                            <?php
                                                $filter_brand = mysqli_query($conn, "SELECT * FROM brand");

                                                while($row = mysqli_fetch_array($filter_brand)){
                                            ?>        
                                                <li>
                                                    <input type="checkbox" name='<?= $row["br_id"] ?>' value='<?= $row["br_id"] ?>' class="me-2" id='<?= $row["br_id"] ?>' style="width: 20px; height: 20px" 
                                                    <?php
                                                        //BIAR BISA TETEP KECENTANG SETELAH REFRESH PAGE

                                                        if(isset($_SESSION["filter"])){
                                                            for($i = 0; $i < sizeof($_SESSION["filter"]); $i++){
                                                                if($_SESSION["filter"][$i] == $row["br_id"]){
                                                                    echo "checked='checked'";
                                                                    break;
                                                                }
                                                            }

                                                        }
                                                    ?>
                                                    >
                                                    <label for='<?= $row["br_id"] ?>'><?= $row["br_name"] ?></label>
                                                </li>
                                                
                                            <?php
                                                }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- GENDER -->
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button collapsed border-bottom rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                    Gender
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse border-bottom rounded-0" aria-labelledby="panelsStayOpen-headingTwo" data-bs-parent="#accordionpanelsStayOpenExample">
                                    <div class="accordion-body pb-0">
                                        <ul>      
                                            <li>
                                                <input type="radio" name='gender' value='M' class="me-2" id='M' style="width: 20px; height: 20px"
                                                <?php
                                                    if($_SESSION["gender"] == "M"){
                                                        echo "checked";
                                                    }
                                                ?>
                                                >
                                                <label for='M'>Men</label>
                                            </li>
                                            <li>
                                                <input type="radio" name='gender' value='W' class="me-2" id='W' style="width: 20px; height: 20px"
                                                <?php
                                                    if($_SESSION["gender"] == "W"){
                                                        echo "checked";
                                                    }
                                                ?>>
                                                <label for='W'>Women</label>
                                            </li>
                                            <li>
                                                <input type="radio" name='gender' value='A' class="me-2" id='A' style="width: 20px; height: 20px"
                                                <?php
                                                    if($_SESSION["gender"] == "A"){
                                                        echo "checked";
                                                    }
                                                ?>
                                                >
                                                <label for='A'>All</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- HARGA  -->
                            <div class="accordion-item border-bottom" style="margin-top: -1px;">
                                <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                                <button class="accordion-button collapsed rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                    Harga
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse rounded-0" aria-labelledby="flush-headingThree" data-bs-parent="#accordionpanelsStayOpenExample">
                                    <div class="accordion-body">
                                        Urutkan berdasarkan : <br>
                                        <input type="radio" name="urutkan" value="desc"
                                        <?php
                                            //BIAR SORT HARGA TETAP ADA SETELAH REFRESH
                                            if(isset($_SESSION["urutkan"])){
                                                if($_SESSION["urutkan"] == "desc"){
                                                    echo " checked";
                                                }

                                            }
                                        ?>> Harga Tertinggi <br>
                                        <input type="radio" name="urutkan" value="asc"
                                        <?php
                                            //BIAR SORT HARGA TETAP ADA SETELAH REFRESH
                                            if(isset($_SESSION["urutkan"])){
                                                if($_SESSION["urutkan"] == "asc"){
                                                    echo " checked";
                                                }

                                            }
                                        ?>> Harga Terendah <br><br>
                                        <div>
                                            <button class="p-2 px-3 border border-1" style="margin-right: -5px" type="input" >Rp</button>
                                            <input type="number" min="0" name="harga-minimum" placeholder="Harga Minimum" class="p-2 w-75 border border-1"
                                            <?php
                                                //BIAR HARGA TETAP ADA SETELAH REFRESH
                                                if(isset($_SESSION["harga-minimum"])){
                                                    if($_SESSION["harga-minimum"] != ""){
                                                        echo "value='".$_SESSION["harga-minimum"]."'";
                                                    }

                                                }
                                            ?>
                                            >
                                        </div>
                                        <br>
                                        <div>
                                            <button class="p-2 px-3 border border-1" style="margin-right: -5px" type="input" >Rp</button>
                                            <input type="number" min="0" name="harga-maximum" placeholder="Harga Maximum" class="p-2 w-75" 
                                            <?php
                                                //BIAR HARGA TETAP ADA SETELAH REFRESH
                                                if(isset($_SESSION["harga-maximum"])){
                                                    if($_SESSION["harga-maximum"] != ""){
                                                        echo "value='".$_SESSION["harga-maximum"]."'";
                                                    }

                                                }
                                            ?>
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 h-100 rounded-0" name="apply-filter">Apply</button>
                        <button type="submit" class="btn btn-danger w-100 h-100 rounded-0" name="reset-filter">Reset</button>
                        
                    </div>
                </div>
                <div class="" style="width: 1000px;">
                    <!-- TOMBOL GANTI PAGE ATAS -->
                    <div class="row justify-content-center mt-3">
                        <div class="col-4">
                            <ul class="pagination d-flex justify-content-center">
                                <li class="page-item">
                                <a class="page-link" href='product.php?page=<?php if ($page - 1 > 0) echo $page - 1; else echo "1";?>' aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                </li>
                                <?php
                                    if ($page < 5 && $maxpage > 5) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=2">2</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=3">3</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=4">4</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=5">5</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    } else if ($maxpage <= 5) {
                                        for ($i = 0; $i < $maxpage; $i++) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$i + 1?>"><?=$i + 1?></a></li>
                                <?php
                                        }
                                    } else if ($page > 4 && $page < $maxpage - 3) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page - 1?>'><?= $page - 1 ?></a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page?>'><?= $page ?></a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page + 1?>'><?= $page + 1 ?></a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    } else {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-4?>"><?=$maxpage-4?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-3?>"><?=$maxpage-3?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-2?>"><?=$maxpage-2?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-1?>"><?=$maxpage-1?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    }
                                ?>
                                <a class="page-link rounded-end" href='product.php?page=<?php if ($page + 1 < $maxpage) echo $page + 1; else echo $maxpage;?>' aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- PRINT ITEM -->
                    <div class="row">
                        <?php
                            $ada = false;
                            for ($i = ($page - 1) * 30; $i < $page * 30; $i++) {
                                if (isset($result[$i])) {
                                    $ada = true;
                                    $kc_id = $result[$i]["kc_id"];
                                    $kc_price = $result[$i]["kc_price"];
                                    $co_id = $result[$i]["co_id"];
                                    $co_link = $result[$i]["co_link"];
                                    $br_name = $result[$i]["br_name"];
                        ?>
                                    <div class="col-lg-4 col-12 d-flex justify-content-center">
                                        <a href='<?= "detail.php?id=" . $kc_id ?>' class="text-black text-decoration-none">
                                            <div class="card text-center" style="width: 18rem; border: none;">
                                                <img src='<?= $co_link ?>' class="card-img-top">
                                                <div class="card-body">
                                                    <h4 class="card-title"><?= $br_name ?></h4>
                                                    <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                                                    <br><?= "Rp " . number_format($kc_price) ?></p>
                                                </div>
                                            </div>

                                        </a>
                                    </div>
                        <?php
                                }
                            }
                            if(!$ada){
                        ?>
                        <h1 class="text-center">MAAF BARANG TIDAK DITEMUKAN</h1>
                        <?php
                            }
                        ?>

                    </div>
                    <!-- TOMBOL GANTI PAGE BAWAH -->
                    <div class="row justify-content-center my-5">
                        <div class="col-4">
                            <ul class="pagination d-flex justify-content-center">
                                <li class="page-item">
                                <a class="page-link" href='product.php?page=<?php if ($page - 1 > 0) echo $page - 1; else echo "1";?>' aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                                </li>
                                <?php
                                    if ($page < 5 && $maxpage > 5) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=2">2</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=3">3</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=4">4</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=5">5</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    } else if ($maxpage <= 5) {
                                        for ($i = 0; $i < $maxpage; $i++) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$i + 1?>"><?=$i + 1?></a></li>
                                <?php
                                        }
                                    } else if ($page > 4 && $page < $maxpage - 3) {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page - 1?>'><?= $page - 1 ?></a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page?>'><?= $page ?></a></li>
                                    <li class="page-item"><a class="page-link" href='product.php?page=<?=$page + 1?>'><?= $page + 1 ?></a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    } else {
                                ?>
                                    <li class="page-item"><a class="page-link" href="product.php?page=1">1</a></li>
                                    <li class="page-item"><a class="page-link">...</a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-4?>"><?=$maxpage-4?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-3?>"><?=$maxpage-3?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-2?>"><?=$maxpage-2?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage-1?>"><?=$maxpage-1?></a></li>
                                    <li class="page-item"><a class="page-link" href="product.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                                <?php
                                    }
                                ?>
                                <a class="page-link rounded-end" href='product.php?page=<?php if ($page + 1 < $maxpage) echo $page + 1; else echo $maxpage;?>' aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
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
    </form>



    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
</html>