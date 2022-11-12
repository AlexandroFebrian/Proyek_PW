<?php
    require_once("connection.php");

    $kc_id = "";
    if (isset($_GET["id"])) {
        $kc_id = $_GET["id"];
    }

    if ($kc_id != "") {
        $result = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kacamata JOIN color ON kc_id = co_kc_id JOIN brand ON kc_br_id = br_id WHERE kc_id = '$kc_id'"));
        $kc_id = $result["kc_id"];
        $kc_price = $result["kc_price"];
        $co_id = $result["co_id"];
        $co_link = $result["co_link"];
        $br_name = $result["br_name"];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        if ($kc_id != "") {
    ?>
            <div class="col text-center me-4 mb-5">
                <div class="card" style="width: 18rem; border: none;">
                    <img src='<?= $co_link ?>' class="card-img-top">
                    <div class="card-body">
                        <h4 class="card-title"><?= $br_name ?></h4>
                        <p class="card-text fs-5"><?= "SKU-" . $co_id ?>
                        <br><?= "Rp " . number_format($kc_price, 0, "", ",") ?></p>
                    </div>
                </div>
            </div>
    <?php
        }
    ?>
</body>
</html>