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
        header("Content-Disposition: attachment; filename=Optik Primadona Financial Report.xls");
    ?>
    <table border 1px>
        <?php
            $grandtotal = 0;
            $totalqty = 0;
            $user_htrans = mysqli_query($conn, "SELECT * FROM htrans WHERE MONTH(ht_date) LIKE '$bulan' AND YEAR(ht_date) = '$tahun'");
            while ($row = mysqli_fetch_array($user_htrans)) {
        ?>
            <tr>
                <td><?= $row["ht_id"] ?></td>
                <td><?= $row["ht_date"] ?></td>
                <td><?= $row["ht_invoice"] ?></td>
                <td><?= $row["ht_total"] ?></td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>