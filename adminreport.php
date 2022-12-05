<?php
    require_once("connection.php");

    if(!isset($_SESSION["admin"])){
        header("Location: index.php");
    }

    if(isset($_POST["logout"])){
        header("Location: adminlogin.php");
    }

    if(isset($_POST["user"])){
        header("Location: admin.php");
    }

    if(isset($_POST["brand"])){
        header("Location: adminbrand.php");
    }

    if(isset($_POST["product"])){
        header("Location: adminproduct.php");
    }

    if(isset($_POST["color"])){
        header("Location: admincolor.php");
    }

    $bulan = "%%";
    $tahun = date("Y");
    if (isset($_POST["filtering"])) {
        $bulan = $_POST["bulan"];
        $tahun = $_POST["tahun"];
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
    <form method="POST">
        <h1>ADMIN</h1>
        <button type="submit" name="logout">LOGOUT</button>
        <button type="submit" name="user">MASTER USER</button>
        <button type="submit" name="brand">MASTER BRAND</button>
        <button type="submit" name="product">MASTER PRODUCT</button>
        <button type="submit" name="color">MASTER COLOR</button>
        <button type="submit" formaction="adminreport.php">REPORT</button><br><br>
        <button type="submit" formaction="adminreport.php">REFRESH PAGE</button>
        
        <h2>REPORT</h2>
        <div style="float: left; margin-right: 50px;">
            <table border 1px>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Invoice</th>
                    <th>Total</th>
                </tr>
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
                        <td><?= "Rp " . number_format($row["ht_total"], 0, "", ",") ?></td>
                    </tr>
                <?php
                    }
                ?>
            </table>
        </div>
        
        <div style="float: left;">
            <h3>FILTER</h3>
            BULAN : 
            <select name="bulan">
                <option value="%%">ALL</option>
                <option value="1">January</option>
                <option value="2">February</option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select><br><br>
            TAHUN : 
            <input type="number" name="tahun" value="2022" min="2000" max="<?= date("Y") ?>"><br><br>
            <button type="submit" name="filtering">Filter</button>
            <button><a style="color: black; text-decoration: none;" href="printreport.php?bulan=<?= $bulan ?>&tahun=<?= $tahun ?>">Print as excel</a></button>
        </div>
    </form>
</body>
</html>