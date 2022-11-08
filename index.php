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
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <form action="">
        <nav class="navbar navbar-expand-lg bg-danger p-3 position-fixed position-absolute top-0 w-100">
            <div class="container-fluid">
                <a class="navbar-brand text-white" href="index.php"><h4>Optik Primadona</h4></a>
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

        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="..." class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="..." class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                <img src="..." class="d-block w-100" alt="...">
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




    <script src="script/bootstrap.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
</html>