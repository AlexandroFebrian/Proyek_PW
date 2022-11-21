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
        
        <h2>MASTER COLOR</h2>
        <table border 1px>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Birth</th>
                <th>Gender</th>
                <th>Telephone</th>
                <th>Address</th>
            </tr>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM users");

                $ctr = 0;
                while($row = mysqli_fetch_array($result)){
                $ctr++;
            ?>
            <tr>
                <td><?= $ctr ?></td>
                <td><?= $row["us_username"] ?></td>
                <td><?= $row["us_email"] ?></td>
                <td><?= $row["us_name"] ?></td>
                <td><?= $row["us_birth"] ?></td>
                <td><?= $row["us_gender"] ?></td>
                <td><?= $row["us_phone"] ?></td>
                <td><?= $row["us_address"] ?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </form>
</body>
</html>