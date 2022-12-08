<?php
    require_once("connection.php");

    if(!isset($_SESSION["admin"])){
        header("Location: adminlogin.php");
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

    if(isset($_POST["action"])){
        $id = explode("-", $_POST["action"])[0];
        $status = explode("-", $_POST["action"])[1];

        if($status == 1){
            mysqli_query($conn, "UPDATE users SET us_status = 0 WHERE us_id = '$id'");
        }else{
            mysqli_query($conn, "UPDATE users SET us_status = 1 WHERE us_id = '$id'");
        }
    }

    if (isset($_POST["selesai"])) {
        mysqli_query($conn, "UPDATE htrans SET ht_status = '1' WHERE ht_id = '" . $_POST["selesai"] . "'");
        $_SESSION["email"] = $_POST["selesai"];
        require_once("mailer.php");
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
        <button type="submit" formaction="admin.php">REFRESH PAGE</button>
        
        <h2>MASTER USER</h2>
        <button><a style="color: black; text-decoration: none;" href="printuser.php">Print Semua User</a></button>
        <br><br>
        <table border 1px>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Birth</th>
                <th>Gender</th>
                <th>Telephone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM users");
                
                while($row = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?= $row["us_id"] ?></td>
                <td><?= $row["us_username"] ?></td>
                <td><?= $row["us_email"] ?></td>
                <td><?= $row["us_name"] ?></td>
                <td><?= $row["us_birth"] ?></td>
                <td><?= $row["us_gender"] ?></td>
                <td><?= $row["us_phone"] ?></td>
                <td><?= $row["us_address"] ?></td>
                <td><?= $row["us_status"] ?></td>
            <?php
                if($row["us_status"] == 1){
            ?>
                <td><button name="action" type="submit" value="<?= $row["us_id"].'-'.$row["us_status"] ?>">Block</button></td>
            <?php
                }else{
            ?>
                <td><button name="action" type="submit" value="<?= $row["us_id"].'-'.$row["us_status"] ?>">Unblock</button></td>
            <?php
                }
            ?>
            </tr>
            <?php
                }
            ?>
        </table>
        <br><br>
        <h2>TRANSAKSI BERJALAN</h2>
        <table border 1px>
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Invoice</th>
                <th>Total Belanja</th>
                <th>Nama Pembeli</th>
                <th>Action</th>
            </tr>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM htrans JOIN users ON us_id = ht_us_id WHERE ht_status = '3'");

                while($row = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?= $row["ht_id"] ?></td>
                <td><?= date_format(date_create($row["ht_date"]),"d F Y") ?></td>
                <td><?= $row["ht_invoice"] ?></td>
                <td><?= "Rp " . number_format($row["ht_total"], 0, "", ",") ?></td>
                <td><?= $row["us_name"] ?></td>
                <td><button name="selesai" type="submit" value="<?= $row["ht_id"] ?>">Sudah Dikirim</button></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </form>
</body>
</html>