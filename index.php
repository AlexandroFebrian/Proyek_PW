<?php
    require_once("Connection.php");
    
    $result = [];
    $tempresult = mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id GROUP BY co_kc_id");
    while ($row = mysqli_fetch_array($tempresult)) {
        $result[] = $row;
    }

    $page = 1;
    $maxpage = intval(count($result) / 30);
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
    }

    if ($page < 1) {
        header("Location: index.php?page=1");
    } else if ($page > $maxpage) {
        header("Location: index.php?page=$maxpage");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="css/stylehome.css">
    <script src="jshome.js"></script>
</head>
<body>
    <form>
        <nav class="navbar navbar-expand-lg bg-white p-3 position-fixed position-absolute top-0 w-100 border-bottom" style="z-index: 5;">
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
                <!-- <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form> -->
                </div>
            </div>
        </nav>

        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel" style="margin-top: 73px;">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="5000">
                <img src="img/fot1.png" class="d-block w-100" alt="..." style="height: 100%;">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                <img src="img/fot2.png" class="d-block w-100" alt="..." style="height: 100%;">
                </div>
                <div class="carousel-item" data-bs-interval="5000">
                <img src="img/fot3.png" class="d-block w-100" alt="..." style="height: 100%;">
                </div>
            </div>
        </div>

        <div class="container-xxl">
            <div class="row">
                <div class="col">
                    <h1>Popular Brand</h1>
                </div>
            </div>
            
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
                <?php
                        }
                    }
                ?>
            </div>
            <div class="row justify-content-center">
                <div class="col-4">
                    <ul class="pagination">
                        <li class="page-item">
                        <a class="page-link" href='index.php?page=<?php if ($page - 1 > 0) echo $page - 1; else echo "1";?>' aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>
                        <?php
                            if ($page < 5 && $maxpage > 5) {
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=2">2</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=3">3</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=4">4</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=5">5</a></li>
                            <li class="page-item"><a class="page-link">...</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                        <?php
                            } else if ($maxpage <= 5) {
                                for ($i = 0; $i < $maxpage; $i++) {
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$i + 1?>"><?=$i + 1?></a></li>
                        <?php
                                }
                            } else if ($page > 4 && $page < $maxpage - 3) {
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                            <li class="page-item"><a class="page-link">...</a></li>
                            <li class="page-item"><a class="page-link" href='index.php?page=<?=$page - 1?>'><?= $page - 1 ?></a></li>
                            <li class="page-item"><a class="page-link" href='index.php?page=<?=$page?>'><?= $page ?></a></li>
                            <li class="page-item"><a class="page-link" href='index.php?page=<?=$page + 1?>'><?= $page + 1 ?></a></li>
                            <li class="page-item"><a class="page-link">...</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                        <?php
                            } else {
                        ?>
                            <li class="page-item"><a class="page-link" href="index.php?page=1">1</a></li>
                            <li class="page-item"><a class="page-link">...</a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage-4?>"><?=$maxpage-4?></a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage-3?>"><?=$maxpage-3?></a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage-2?>"><?=$maxpage-2?></a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage-1?>"><?=$maxpage-1?></a></li>
                            <li class="page-item"><a class="page-link" href="index.php?page=<?=$maxpage?>"><?=$maxpage?></a></li>
                        <?php
                            }
                        ?>
                        <a class="page-link" href='index.php?page=<?php if ($page + 1 < $maxpage) echo $page + 1; else echo $maxpage;?>' aria-label="Next">
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

</html>