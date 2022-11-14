<?php
  require_once("connection.php");

  $_SESSION["gender"] = "A";
  
  if (isset($_POST["search-btn"])) {
      $br_name = $_POST["search-val"];
      $co_id = explode('-', $_POST["search-val"]);
      $co_id = $co_id[count($co_id) - 1];
  }

    $result = [];
    $tempresult = mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id AND br_id = 'BR008' AND kc_price > 1600000 GROUP BY co_kc_id ORDER BY kc_price DESC ");
    while ($row = mysqli_fetch_array($tempresult)) {
        $result[] = $row;
    }

    if(isset($_SESSION["filter"])){
      unset($_SESSION["filter"]);
    }

    if(isset($_SESSION["harga-minimum"])){
      unset($_SESSION["harga-minimum"]);
    }

    if(isset($_SESSION["harga-maximum"])){
      unset($_SESSION["harga-maximum"]);
    }

    if(isset($_SESSION["search-val"])){
      unset($_SESSION["search-val"]);
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
                          <a class="nav-link d-block d-lg-none" role="button" href="login.php?page=1">Masuk</a>
                          <a class="nav-link d-block d-lg-none" role="button" href="register.php?page=1">Daftar</a>
                      </li>
                  </ul>
              </div>
          </div>
          <div class="input-group mb-1 container-fluid mt-4 mt-lg-0">
              <input type="text" class="form-control" placeholder="Cari brand disini" name="search-val">
              <span class="rounded-end" style="background-color: lightgray;">
                  <button class="btn" type="submit" name="search-btn" formaction="product.php"><img src="storage/icons/search.png" width="18px" class="opacity-50"></button>
              </span>
              <img src="storage/icons/cart.png" class="mx-lg-3 mx-0 ms-3 opacity-50" width="30px">
              <div class="fs-3 pb-2 opacity-75 d-none d-lg-block">|</div>
              <button class="btn btn-outline-success mx-3 d-none d-lg-block rounded-3" type="submit" formaction="login.php">Masuk</button>
              <button class="btn btn-success me-0 me-lg-2 d-none d-lg-block rounded-3" type="submit" formaction="register.php">Daftar</button>
          </div>
      </nav>

      <!-- Jumbotron -->
      <div id="carouselExampleFade" class="carousel slide carousel-fade shadow" data-bs-ride="carousel">
          <div class="carousel-inner">
              <div class="carousel-item active" data-bs-interval="5000">
              <img src="storage/img/fot1.png" class="d-block w-100" style="height: 100%;">
              </div>
              <div class="carousel-item" data-bs-interval="5000">
              <img src="storage/img/fot2.png" class="d-block w-100" style="height: 100%;">
              </div>
              <div class="carousel-item" data-bs-interval="5000">
              <img src="storage/img/fot3.png" class="d-block w-100" style="height: 100%;">
              </div>
          </div>
      </div>

      <div class="container-xxl my-4">
          <div class="row ms-2">
              <div class="col">
                  <h1>Popular Brand</h1>
              </div>
          </div>
          <div class="container-fluid py-2">
              <div class="d-flex flex-row flex-nowrap overflow-auto">
                  <?php
                      for ($i = 0; $i < count($result); $i++) {
                          if (isset($result[$i])) {
                              $kc_id = $result[$i]["kc_id"];
                              $kc_price = $result[$i]["kc_price"];
                              $co_id = $result[$i]["co_id"];
                              $co_link = $result[$i]["co_link"];
                              $br_name = $result[$i]["br_name"];
                  ?>
                              <div class="card card-block mx-2 shadow" style="min-width: auto;">
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
                              </div>
                  <?php
                          }
                      }
                  ?>
              </div>
          </div>
      </div>
      <footer>
        <div class="bg-dark" id="scrollspyHeading5">
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
    </form>



    <script src="script/bootstrap.bundle.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>

</html>