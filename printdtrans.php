<?php
    require_once("connection.php");

    ob_start();

    if(!isset($_SESSION["admin"])){
        header("Location: index.php");
    }

    $bulan = "%%";
    $tahun = date("Y");
    if (isset($_REQUEST["bulan"])) {
        $bulan = $_REQUEST["bulan"];
        $tahun = $_REQUEST["tahun"];
    } else {
        header("Location: adminreport.php");
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
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan Barang Terjual.xls");
    ?>
    <table border 1px>
        <?php
            $grandtotal = 0;
            $totalqty = 0;
            $user_htrans = mysqli_query($conn, "SELECT * FROM dtrans JOIN htrans ON ht_id = dt_ht_id JOIN color ON co_id = dt_co_id JOIN kacamata ON kc_id = co_kc_id JOIN brand ON br_id = kc_br_id WHERE MONTH(ht_date) LIKE '$bulan' AND YEAR(ht_date) = '$tahun'");
            while ($row = mysqli_fetch_array($user_htrans)) {
        ?>
            <tr>
                <td><?= $row["co_id"] ?></td>
                <td><?= $row["dt_qty"] ?></td>
                <td><?= $row["dt_subtotal"] ?></td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>