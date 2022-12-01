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

    if(isset($_POST["yes"])){
        if($_POST["yes"] != ""){
            $id = $_POST["yes"];
            $name = $_POST["after"];
            if($name != ""){
                mysqli_query($conn, "UPDATE brand SET br_name = '$name' WHERE br_id = '$id'");
    
                $_SESSION["msg"] = "BERHASIL GANTI NAMA";
            }else{
                $_SESSION["msg"] = "FIELD KOSONG";
            }
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
        <button type="submit" name="color">MASTER COLOR</button>
        <button type="submit" formaction="adminreport.php">REPORT</button><br><br>
        <button type="submit" formaction="adminbrand.php">REFRESH PAGE</button>
        
        <h2>MASTER BRAND</h2>
        <div style="float: left; margin-right: 50px;">
            <table border 1px>
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
                    <td><button type="button" value="<?= $row["br_id"].'-'.$row["br_name"] ?>" onclick="edit(this)">Edit</button></td>
                </tr>
                <?php
                    }
                ?>
            </table>

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
                    <button type="button" onclick="no()">NO</button>
            </div>

        </div>

    </form>
</body>
<script>
    isi = document.getElementById("edit")
    conf = document.getElementById("confirm")
    id = ""
    name = ""

    function edit(obj){
        isi.style.display = "block"

        id = obj.value.split("-")[0]
        name = obj.value.split("-")[1]

        document.getElementById("before").value = name
    }

    function cancel(){
        isi.style.display = "none"
        no()
    }
    
    function no(){
        conf.style.display = "none"
        document.getElementById("yes").value = ""
    }

    function change(){
        conf.style.display = "block"

        document.getElementById("yes").value = id
    }
</script>
</html>