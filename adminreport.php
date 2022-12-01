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
    <form action="" method="POST">
        <h1>ADMIN</h1>
        <button type="submit" name="logout">LOGOUT</button>
        <button type="submit" name="user">MASTER USER</button>
        <button type="submit" name="brand">MASTER BRAND</button>
        <button type="submit" name="product">MASTER PRODUCT</button>
        <button type="submit" name="color">MASTER COLOR</button>
        <button type="submit" formaction="adminreport.php">REPORT</button><br><br>
        <button type="submit" formaction="adminreport.php">REFRESH PAGE</button>
        
        <h2>Report</h2>
        <div style="float: left; margin-right: 50px;">
            
                <?php
                    $user_htrans = mysqli_query($conn, "SELECT * FROM htrans JOIN users ON us_id = ht_us_id GROUP BY ht_us_id");
                    while ($us = mysqli_fetch_array($user_htrans)) {
                ?>
                    <table border 1px>
                            <tr>
                                <th style="text-align: left;" colspan="5"><?= $us["us_name"] ?></th>
                            </tr>
                            <?php
                                $htrans = mysqli_query($conn, "SELECT * FROM htrans WHERE ht_us_id = '" . $us["us_id"] . "'");
                                while ($ht = mysqli_fetch_array($htrans)) {
                            ?>
                                <tr>
                                    <th style="text-align: left;" colspan="5"><?= $ht["ht_id"] ?></th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Brand Name</th>
                                    <th>Color</th>
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
                                        <td><?= $dt["dt_qty"] ?></td>
                                        <td><?= "Rp " . number_format($dt["dt_subtotal"], 0, "", ",") ?></td>
                                    </tr>
                        <?php
                                }
                        ?>
                                <tr>
                                    <th colspan="4">TOTAL</th>
                                    <td><?= "Rp" . number_format($ht["ht_total"], 0, "", ",") ?></td>
                                </tr>
                    <?php
                            }
                    ?>
                    </table>
                    <br>
                <?php
                    }
                ?>
        </div>
        
        <div style="float: left;">
            <h3>ADD BRAND</h3>
            NEW BRAND : 
            <input type="text" name="br_name" id="">
            <button type="submit" name="add">Add Brand</button><br><br>
    
            <div id="edit" style="display: none;">
                <h3>EDIT BRAND NAME</h3>
                BEFORE : 
                <input type="text" id="before" disabled><br><br>
                AFTER : 
                <input type="text" name="after"><br><br>
                <button type="button" onclick="change()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br><br>
            <div id="confirm" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                    <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                    ARE YOU SURE?<br><br>
                    <button type="submit" name="yes" id="yes" value="">YES</button>
                    <button type="button" onclick="cancel()">NO</button>
            </div>

        </div>

    </form>
</body>
</html>