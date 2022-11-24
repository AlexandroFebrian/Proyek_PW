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
        $br_id = $_POST["filter_add"];
        $price = $_POST["price"];
        
        if($price == "" || !isset($_POST["gender"])){
            $_SESSION["msg"] = "FIELD KOSONG";
        }else{
            $gender = $_POST["gender"];
            $result = mysqli_query($conn, "SELECT MAX(kc_id) FROM kacamata");

            $count = substr(mysqli_fetch_array($result)[0], 2);
            $count++;

            $kc_id = "KC".str_pad($count, 3, "0", STR_PAD_LEFT);

            mysqli_query($conn, "INSERT INTO kacamata VALUES ('$kc_id', '$price', '$gender', '$br_id')");
            $_SESSION["msg"] = "BERHASIL ADD PRODUCT";
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
        <button type="submit" formaction="adminproduct.php">REFRESH PAGE</button>
        
        <h2>MASTER PRODUCT</h2>
        SEARCH BY BRAND : 
        <select name="filter" id="">
            <option value=""></option>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM brand");

                while($row = mysqli_fetch_array($result)){
            ?>
            <option value='<?= $row["br_id"] ?>'><?= $row["br_name"] ?></option>
            <?php
                }
            ?>
        </select>
        <button type="submit" name="apply">APPLY</button><br><br>
        <table border 1px style="float: left; margin-right: 50px">
            <tr>
                <th>ID</th>
                <th>Price</th>
                <th>Gender</th>
                <th>Brand Name</th>
            </tr>
            <?php
                $query = "SELECT * FROM kacamata JOIN brand ON kc_br_id = br_id";

                if(isset($_POST["apply"])){
                    if($_POST["filter"] != ""){
                        $query .= " WHERE br_id = '".$_POST["filter"]."'";
                    }
                }

                $query .= " ORDER BY br_id ASC";

                $result = mysqli_query($conn, $query);

                $ctr = 0;
                while($row = mysqli_fetch_array($result)){
                $ctr++;
            ?>
            <tr>
                <td><?= $row["kc_id"] ?></td>
                <td><?= $row["kc_price"] ?></td>
                <td><?= $row["kc_gender"] ?></td>
                <td><?= $row["br_name"] ?></td>
            </tr>
            <?php
                }
            ?>
        </table>

        <h3>ADD PRODUCT</h3>
        BRAND : 
        <select name="filter_add" id="">
            <?php
                $result = mysqli_query($conn, "SELECT * FROM brand");

                while($row = mysqli_fetch_array($result)){
            ?>
            <option value='<?= $row["br_id"] ?>'><?= $row["br_name"] ?></option>
            <?php
                }
            ?>
        </select><br><br>
        PRICE : 
        <input type="number" name="price" min=1><br><br>
        GENDER : 
        <input type="radio" name="gender" id="M" value="M">
        <label for="M">MAN</label>
        <input type="radio" name="gender" id="W" value="W">
        <label for="W">WOMAN</label><br><br>

        <button type="submit" name="add">ADD</button>
    </form>
</body>
</html>