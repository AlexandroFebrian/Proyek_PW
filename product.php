<?php
    require_once("Connection.php");

    $result = [];

    $query = "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id ";
    
    if(isset($_GET["apply-filter"])){
        $filter_brand = mysqli_query($conn, "SELECT * FROM brand");
        
        $tempfilter = [];
        while($row = mysqli_fetch_array($filter_brand)){
            if(isset($_GET[$row["br_id"]])){
                $tempfilter[] = $row;
            }
        }

        if(sizeof($tempfilter) > 0){
            $query .= "WHERE ";

            for($i = 0; $i < sizeof($tempfilter); $i++){
                $query .= "br_id = '".$tempfilter[$i]["br_id"]."'";
                if($i != sizeof($tempfilter)-1){
                    $query .= " OR ";
                }else{
                    $query .= " ";
                }
            }
        }

        if(isset($_GET["harga-minimum"])){
            if($_GET["harga-minimum"] != ""){
                $query .= "AND kc_price >= ".$_GET["harga-minimum"]." ";
            }
        }
        if(isset($_GET["harga-minimum"]) && isset($_GET["harga-maximum"])){
            if(($_GET["harga-minimum"] != "" && $_GET["harga-maximum"] != "") || ($_GET["harga-minimum"] == "" && $_GET["harga-maximum"] != "")){
                $query .= "AND ";
            }
        }
        if(isset($_GET["harga-maximum"])){
            if($_GET["harga-maximum"] != ""){
                $query .= "kc_price <= ".$_GET["harga-maximum"]." ";
            }

        }
    }

    $query .= "GROUP BY co_kc_id";
    
    $tempresult = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($tempresult)) {
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
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="css/product.css">
    <script src="jshome.js"></script>
</head>
<body>
    <form method="GET">
        <nav class="navbar navbar-expand-lg bg-white p-3 position-fixed position-sticky top-0 w-100 border-bottom" style="z-index: 10;">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><h4 class="m-0">Optik Primadona</h4></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" role="button" href="product.php">Semua Produk</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Dropdown
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                </ul>
                </div>
            </div>
        </nav>

        
        <div class="container-fluid pt-4">

            <!-- TOMBOL GANTI PAGE ATAS -->
            <div class="row justify-content-center">
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
                        <a class="page-link" href='product.php?page=<?php if ($page + 1 < $maxpage) echo $page + 1; else echo $maxpage;?>' aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- FILTER WIDTH KECIL -->
            <div class="d-lg-none d-block">
                <h1>test</h1>
            </div>    

            <div class="row d-flex justify-content-center">
                <!-- <div class="col-1 col-lg-1"></div> -->

                <!-- FILTER WIDTH BESAR -->
                <div class="d-xxl-block d-none" style="width: 320px;">
                    <h2 class="mt-2 ms-3">Filter</h2>
                    <div class="border">
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button collapsed border-bottom rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                                    Brand
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse border-bottom rounded-0" aria-labelledby="panelsStayOpen-headingOne" data-bs-parent="#accordionpanelsStayOpenExample">
                                    <div class="accordion-body">
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
                                                        if(isset($_GET[$row["br_id"]])){
                                                            echo "checked='checked'";
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

                            <div class="accordion-item" style="margin-top: -1px;">
                                <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                                <button class="accordion-button collapsed border-bottom rounded-0border-bottom rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                    Harga
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse rounded-0" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionpanelsStayOpenExample">
                                    <div class="accordion-body">
                                        <div>
                                            <button class="p-2 px-3 border border-1" style="margin-right: -5px" type="input" >Rp</button>
                                            <input type="number" min="0" name="harga-minimum" placeholder="Harga Minimum" class="p-2 w-70 border border-1"
                                            <?php
                                                //BIAR HARGA TETAP ADA SETELAH REFRESH
                                                if(isset($_GET["harga-minimum"])){
                                                    if($_GET["harga-minimum"] != ""){
                                                        echo "value='".$_GET["harga-minimum"]."'";
                                                    }

                                                }
                                            ?>
                                            >

                                        </div>
                                        <br>
                                        <div>
                                            <button class="p-2 px-3 border border-1" style="margin-right: -5px" type="input" >Rp</button>
                                            <input type="number" min="0" name="harga-maximum" placeholder="Harga Maximum" class="p-2 w-70" 
                                            <?php
                                                //BIAR HARGA TETAP ADA SETELAH REFRESH
                                                if(isset($_GET["harga-maximum"])){
                                                    if($_GET["harga-maximum"] != ""){
                                                        echo "value='".$_GET["harga-maximum"]."'";
                                                    }

                                                }
                                            ?>
                                            >

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn w-100 h-100 border-top rounded-0" name="apply-filter">Apply</button>
                        
                    </div>
                </div>

                <!-- PRINT ITEM -->
                <div class="" style="width: 1000px;">
                    <div class="row">
                        <?php
                            for ($i = ($page - 1) * 30; $i < $page * 30; $i++) {
                                if (isset($result[$i])) {
                                    $kc_id = $result[$i]["kc_id"];
                                    $kc_price = $result[$i]["kc_price"];
                                    $co_id = $result[$i]["co_id"];
                                    $co_link = $result[$i]["co_link"];
                                    $br_name = $result[$i]["br_name"];
                        ?>
                                    <div class="col-xxl-4 col-lg-4 col-6 d-flex justify-content-center">
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
                        ?>

                    </div>

                </div>


                <!-- <div class="col-1 col-lg-1"></div> -->
            </div>

            <!-- TOMBOL GANTI PAGE BAWAH -->
            <div class="row justify-content-center">
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
                        <a class="page-link" href='product.php?page=<?php if ($page + 1 < $maxpage) echo $page + 1; else echo $maxpage;?>' aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
    <footer class="p-3" style="background-color: gray;">
        <h2>ini footer</h2>
    </footer>



    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
<script>

</script>
</html>