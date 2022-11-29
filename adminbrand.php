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

    if(isset($_POST["add"])){
        $br_name = $_POST["br_name"];

        if($br_name != ""){
            $result = mysqli_query($conn, "SELECT MAX(br_id) FROM brand");

            $count = substr(mysqli_fetch_array($result)[0], 2);
            $count++;

            $br_id = "BR".str_pad($count, 3, "0", STR_PAD_LEFT);

            mysqli_query($conn, "INSERT INTO brand VALUES('$br_id', '$br_name')");
            $_SESSION["msg"] = "BERHASIL ADD BRAND";
        }else{
            $_SESSION["msg"] = "FIELD KOSONG";
        }
    }

    if(isset($_SESSION["msg"])){
        echo "<script>alert('".$_SESSION["msg"]."')</script>";
        unset($_SESSION["msg"]);
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
        <button type="submit" name="color">MASTER COLOR</button><br><br>
        <button type="submit" formaction="adminbrand.php">REFRESH PAGE</button>
        
        <h2>MASTER BRAND</h2>
        <table border 1px style="float: left; margin-right: 50px;">
            <tr>
                <th>ID</th>
                <th>Brand Name</th>
                <th>Action</th>
            </tr>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM brand");

                while($row = mysqli_fetch_array($result)){
            ?>
            <tr>
                <td><?= $row["br_id"] ?></td>
                <td><?= $row["br_name"] ?></td>
                <td><button type="submit" name="action" value="<?= $row["br_id"] ?>">Edit</button></td>
            </tr>
            <?php
                }
            ?>
        </table>
        
        <h3>ADD BRAND</h3>
        NEW BRAND : 
        <input type="text" name="br_name" id="">
        <button type="submit" name="add">Add Brand</button>
    </form>
</body>
</html>