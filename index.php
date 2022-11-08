<?php
    require_once("Connection.php");

    $result = mysqli_query($conn, "SELECT * FROM kacamata, color, brand WHERE kc_id = co_kc_id AND kc_br_id = br_id")
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
</head>
<body>
    <form action="">
        <nav class="navbar navbar-expand-lg bg-white p-3 position-fixed position-absolute top-0 w-100">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><h4>Optik Primadona</h4></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
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
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
                </div>
            </div>
        </nav>

        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel" style="margin-top: 79px; z-index: -1;">
            <div class="carousel-inner" style="height: 800px;">
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

        <!-- <div class="container-xxl" style="margin-top: 100px;">
            <div class="row">
                <div class="col-3">
                    <h1>test</h1>
                </div>
                <div class="col-9">
                    <?php
                        while($row = mysqli_fetch_array($result)){
                            $kc_id = $row["kc_id"];
                            $co_id = $row["co_id"];
                            $co_link = $row["co_link"];
                            $br_name = $row["br_name"]
                    ?>
                            <div style="width: 300px">
                                <img src='<?= $co_link ?>' style="width: 100%">
                                <h3><?= $br_name ?></h3>
                                <h4><?= $kc_id ?></h4>
                                <h5><?= $co_id ?></h5>
                            </div>
                    <?php
                        }
                    ?>

                </div>
            </div>
        </div> -->
    </form>




    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
</html>