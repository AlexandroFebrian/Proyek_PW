<?php
    require_once("Connection.php");

    $result = mysqli_query($conn, "SELECT * FROM kacamata, color WHERE kc_id = co_kc_id")
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
        <?php
            while($row = mysqli_fetch_array($result)){
                $kc_id = $row["kc_id"];
                $co_id = $row["co_id"];
                $co_link = $row["co_link"];
        ?>
                <div style="width: 300px">
                    <img src='<?= $co_link ?>' style="width: 100%">
                    <h3><?= $kc_id ?></h3>
                    <h4><?= $co_id ?></h4>
                </div>
        <?php
            }
        ?>
    </form>




    <script src="script/bootstrap.min.js"></script>
    <script src="script/jquery-3.6.1.min.js"></script>
</body>
</html>