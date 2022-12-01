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
            
                <?php
                    $grandtotal = 0;
                    $totalqty = 0;
                    $user_htrans = mysqli_query($conn, "SELECT * FROM htrans JOIN users ON us_id = ht_us_id WHERE YEAR(ht_date) = '$tahun' GROUP BY ht_us_id");
                    while ($us = mysqli_fetch_array($user_htrans)) {
                ?>
                    <table border 1px>
                            <tr>
                                <th style="text-align: left;" colspan="6"><?= $us["us_name"] ?></th>
                            </tr>
                            <?php
                                $htrans = mysqli_query($conn, "SELECT * FROM htrans WHERE MONTH(ht_date) LIKE '$bulan' AND YEAR(ht_date) = '$tahun' AND ht_us_id = '" . $us["us_id"] . "'");
                                while ($ht = mysqli_fetch_array($htrans)) {
                                    $ht_date = date_create($ht["ht_date"]);
                                    $ht_date = date_format($ht_date,"d F Y H:i:s");
                            ?>
                                <tr>
                                    <th style="text-align: left;" colspan="6"><?= $ht["ht_id"] . " (" . $ht_date . ")" ?></th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Brand Name</th>
                                    <th>Color</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Subtotal</th>
                                </tr>
                                <?php
                                    $dtrans = mysqli_query($conn, "SELECT * FROM dtrans JOIN color ON co_id = dt_co_id JOIN kacamata ON kc_id = co_kc_id JOIN brand ON br_id = kc_br_id WHERE dt_ht_id = '" . $ht["ht_id"] . "'");
                                    $nomor = 1;
                                    while ($dt = mysqli_fetch_array($dtrans)) {
                                ?>
                                    <tr>
                                        <td><?= $nomor++ ?></td>
                                        <td><?= $dt["br_name"] ?></td>
                                        <td><?= $dt["co_id"] ?></td>
                                        <td><?= "Rp " . number_format($dt["kc_price"], 0, "", ",") ?></td>
                                        <td><?= $dt["dt_qty"] ?></td>
                                        <td><?= "Rp " . number_format($dt["dt_subtotal"], 0, "", ",") ?></td>
                                    </tr>
                        <?php
                                    $totalqty += $dt["dt_qty"];
                                }
                                $grandtotal += $ht["ht_total"];
                        ?>
                                <tr>
                                    <th colspan="5">TOTAL</th>
                                    <td><?= "Rp " . number_format($ht["ht_total"], 0, "", ",") ?></td>
                                </tr>
                    <?php
                            }
                    ?>
                    </table>
                    <br>
                <?php
                    }
                ?>
                <table border 1px>
                    <tr>
                        
                        <th colspan="5">TOTAL QTY</th>
                        <td><?= $totalqty ?></td>
                    </tr>
                    <tr>
                        <th colspan="5">GRAND TOTAL</th>
                        <td><?= "Rp" . number_format($grandtotal, 0, "", ",") ?></td>
                    </tr>
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