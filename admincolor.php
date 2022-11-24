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

    // if(isset($_POST["add"])){
    //     if(is_uploaded_file($_FILES['photo']['name'])){
    //         if(!empty($_FILES['photo']['name']))
    //         {
    //             $target_dir = "";
    //             $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    //             move_uploaded_file($_FILES['photo']['tmp_name'], $target_file);
    //         }

    //     }
    // }

    if(isset($_POST["add"])){
        $stock = $_POST["stock"];

        if (is_uploaded_file($_FILES['photo']['tmp_name']) && $stock != "") 
        { 
            //First, Validate the file name
            if(empty($_FILES['photo']['name']))
            {
                echo " File name is empty! ";
                exit;
            }
        
            $upload_file_name = $_FILES['photo']['name'];
        
            //Save the file
            $dest = __DIR__.'/storage/products/'.$upload_file_name;
            $dest2 = 'storage/products/'.$upload_file_name;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) 
            {
                $result = mysqli_query($conn, "SELECT MAX(co_id) FROM color");

                $count = substr(mysqli_fetch_array($result)[0], 2);
                $count++;

                $co_id = "CO".str_pad($count, 4, "0", STR_PAD_LEFT);

                mysqli_query($conn, "INSERT INTO color VALUES ('$co_id', '".$_POST["filter_add"]."', '$dest2', '$stock', '1')");

                echo "<script>alert('BERHASIL ADD COLOR')</script>";
            }
        }else{
            echo "<script>alert('FIELD KOSONG')</script>";
        }

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
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>ADMIN</h1>
        <button type="submit" name="logout">LOGOUT</button>
        <button type="submit" name="user">MASTER USER</button>
        <button type="submit" name="brand">MASTER BRAND</button>
        <button type="submit" name="product">MASTER PRODUCT</button>
        <button type="submit" name="color">MASTER COLOR</button><br><br>
        <button type="submit" formaction="admincolor.php">REFRESH PAGE</button>
        
        <h2>MASTER COLOR</h2>
        SEARCH BY PRODUCT : 
        <select name="filter" id="">
            <option value=""></option>
            <?php
                $result = mysqli_query($conn, "SELECT * FROM kacamata");

                while($row = mysqli_fetch_array($result)){
            ?>
            <option value='<?= $row["kc_id"] ?>'><?= $row["kc_id"] ?></option>
            <?php
                }
            ?>
        </select>
        <button type="submit" name="apply">APPLY</button><br><br>
        <div style="float: left; margin-right: 50px;">
            <?php
                if(isset($_POST["apply"])){
                    if($_POST["filter"] != ""){
            ?>
            <table border 1px>
                <tr>
                    <th><?= $_POST["filter"] ?></th>
                    <th>LINK</th>
                    <th>STOCK</th>
                    <th>STATUS</th>
                </tr>
            <?php
                        $result = mysqli_query($conn, "SELECT * FROM color WHERE co_kc_id = '".$_POST["filter"]."'");

                        while($row = mysqli_fetch_array($result)){
            ?>
                <tr>
                    <td><?= $row["co_id"] ?></td>
                    <td><?= $row["co_link"] ?></td>
                    <td><?= $row["co_stock"] ?></td>
                    <td><?= $row["co_status"] ?></td>
                </tr>
            <?php
                        }
            ?>
            </table>
            <?php
                    }else{
                        $result1 = mysqli_query($conn, "SELECT * FROM kacamata");
        
                        while($row1 = mysqli_fetch_array($result1)){
            ?>
            <table border 1px>
                <tr>
                    <th><?= $row1["kc_id"] ?></th>
                    <th>LINK</th>
                    <th>STOCK</th>
                    <th>STATUS</th>
                </tr>
            <?php
                            $result2 = mysqli_query($conn, "SELECT * FROM color WHERE co_kc_id = '".$row1["kc_id"]."'");

                            while($row2 = mysqli_fetch_array($result2)){
            ?>
                <tr>
                    <td><?= $row2["co_id"] ?></td>
                    <td><?= $row2["co_link"] ?></td>
                    <td><?= $row2["co_stock"] ?></td>
                    <td><?= $row2["co_status"] ?></td>
                </tr>
            <?php
                            }
            ?>
            </table><br>
            <?php
                        }
                    }
                }else{
                    $result1 = mysqli_query($conn, "SELECT * FROM kacamata");
        
                    while($row1 = mysqli_fetch_array($result1)){
            ?>
            <table border 1px>
                <tr>
                    <th><?= $row1["kc_id"] ?></th>
                    <th>LINK</th>
                    <th>STOCK</th>
                    <th>STATUS</th>
                </tr>
            <?php
                        $result2 = mysqli_query($conn, "SELECT * FROM color WHERE co_kc_id = '".$row1["kc_id"]."'");

                        while($row2 = mysqli_fetch_array($result2)){
            ?>
                <tr>
                    <td><?= $row2["co_id"] ?></td>
                    <td><?= $row2["co_link"] ?></td>
                    <td><?= $row2["co_stock"] ?></td>
                    <td><?= $row2["co_status"] ?></td>
                </tr>
            <?php
                        }
            ?>
            </table><br>
            <?php
                    }
                }
            ?>

        </div>
        <h3>ADD COLOR</h3>
        PRODUCT : 
        <select name="filter_add" id="">
            <?php
                $result = mysqli_query($conn, "SELECT * FROM kacamata");

                while($row = mysqli_fetch_array($result)){
            ?>
            <option value='<?= $row["kc_id"] ?>'><?= $row["kc_id"] ?></option>
            <?php
                }
            ?>
        </select><br><br>
        STOCK : 
        <input type="number" name="stock" min=1><br><br>
        PHOTO : 
        <input type="file" name="photo" accept="image/png, image/jpeg, image/jpg,image/webp"><br><br>
        <button type="submit" name="add">ADD</button>
    </form>
</body>
<?php
    
?>
</html>