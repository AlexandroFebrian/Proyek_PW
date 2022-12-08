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

    if(isset($_POST["yesimg"])){
        $id = $_POST["yesimg"];
        if (is_uploaded_file($_FILES['photoedit']['tmp_name'])) 
        { 
            //First, Validate the file name
            if(empty($_FILES['photoedit']['name']))
            {
                echo " File name is empty! ";
                exit;
            }
        
            $upload_file_name = $_FILES['photoedit']['name'];
        
            //Save the file
            $dest = __DIR__.'/storage/products/'.$upload_file_name;
            $dest2 = 'storage/products/'.$upload_file_name;
            if (move_uploaded_file($_FILES['photoedit']['tmp_name'], $dest)) 
            {

                mysqli_query($conn, "UPDATE color SET co_link = '$dest2' WHERE co_id = '$id'");

                echo "<script>alert('$id')</script>";
            }
        }else{
            echo "<script>alert('FIELD KOSONG')</script>";
        }

    }

    if(isset($_POST["yes"])){
        if($_POST["yes"] != ""){
            $id = $_POST["yes"];
            $stock = $_POST["after"];
            if($stock != ""){
                if($stock > 0){
                    mysqli_query($conn, "UPDATE color SET co_stock = '$stock', co_status = 1 WHERE co_id = '$id'");
                }else{
                    mysqli_query($conn, "UPDATE color SET co_stock = '$stock', co_status = 0 WHERE co_id = '$id'");
                }
    
                $_SESSION["msg"] = "BERHASIL GANTI STOCK";
            }else{
                $_SESSION["msg"] = "FIELD KOSONG";
            }
        }
    }

    if(isset($_POST["activate"])){
        $id = explode("-", $_POST["activate"])[0];
        $stock = explode("-", $_POST["activate"])[1];

        if($stock == 0){
            $_SESSION["msg"] = "GAGAL ACTIVATE";
        }else{
            mysqli_query($conn, "UPDATE color SET co_status = 1 WHERE co_id = '$id'");

            $_SESSION["msg"] = "BERHASIL ACTIVATE";
        }
    }

    if(isset($_POST["deactivate"])){
        $id = explode("-", $_POST["deactivate"])[0];
        $stock = explode("-", $_POST["deactivate"])[1];

        mysqli_query($conn, "UPDATE color SET co_status = 0 WHERE co_id = '$id'");

        $_SESSION["msg"] = "BERHASIL DEACTIVATE";
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
    <form action="" method="POST" enctype="multipart/form-data">
        <h1>ADMIN</h1>
        <button type="submit" name="logout">LOGOUT</button>
        <button type="submit" name="user">MASTER USER</button>
        <button type="submit" name="brand">MASTER BRAND</button>
        <button type="submit" name="product">MASTER PRODUCT</button>
        <button type="submit" name="color">MASTER COLOR</button>
        <button type="submit" formaction="adminreport.php">REPORT</button><br><br>
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

        SEARCH BY STATUS : 
        <select name="filterstat" id="">
            <option value=""></option>
            <option value='1'>Active</option>
            <option value='0'>Inactive</option>
        </select>
        <button type="submit" name="applystat">APPLY</button><br><br>

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
                    <th>ACTION</th>
                </tr>
            <?php
                        $query = "SELECT * FROM color WHERE co_kc_id = '".$_POST["filter"]."'";
                        if(isset($_POST["applystat"])){
                            if($_POST["filterstat"] != ""){
                                $query .= " AND co_status = '".$_POST["filterstat"]."'";
                            }
                        }
                        $result = mysqli_query($conn, $query);

                        while($row = mysqli_fetch_array($result)){
            ?>
                <tr>
                    <td><?= $row["co_id"] ?></td>
                    <td><img src="<?= $row['co_link'] ?>" width="100px"></td>
                    <td><?= $row["co_stock"] ?></td>
                    <td><?= $row["co_status"] ?></td>
                    <td>
                        <button type="button" value="<?= $row["co_id"].'-'.$row["co_stock"] ?>" onclick="edit(this)">Edit Stock</button>
                        <button type="button" value="<?= $row["co_id"].'|'.$row["co_link"] ?>" onclick="editimg(this)">Edit Image</button>
                        <?php 
                            if($row["co_status"] == 1){
                        ?>
                            <button name="deactivate" value='<?= $row["co_id"].'-'.$row["co_stock"] ?>'>Deactivate</button>
                        <?php
                            }else{
                        ?>
                            <button name="activate" value='<?= $row["co_id"].'-'.$row["co_stock"] ?>'>Activate</button>
                        <?php
                            }
                        ?>
                    </td>
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
                    <th>ACTION</th>
                </tr>
            <?php
                            $query = "SELECT * FROM color WHERE co_kc_id = '".$row1["kc_id"]."'";
                            if(isset($_POST["applystat"])){
                                if($_POST["filterstat"] != ""){
                                    $query .= " AND co_status = '".$_POST["filterstat"]."'";
                                }
                            }
                            $result2 = mysqli_query($conn, $query);

                            while($row2 = mysqli_fetch_array($result2)){
            ?>
                <tr>
                    <td><?= $row2["co_id"] ?></td>
                    <td><img src="<?= $row2['co_link'] ?>" width="100px"></td>
                    <td><?= $row2["co_stock"] ?></td>
                    <td><?= $row2["co_status"] ?></td>
                    <td>
                        <button type="button" value="<?= $row2["co_id"].'-'.$row2["co_stock"] ?>" onclick="edit(this)">Edit Stock</button>
                        <button type="button" value="<?= $row2["co_id"].'|'.$row2["co_link"] ?>" onclick="editimg(this)">Edit Image</button>
                        <?php 
                            if($row2["co_status"] == 1){
                        ?>
                            <button name="deactivate" value='<?= $row2["co_id"].'-'.$row2["co_stock"] ?>'>Deactivate</button>
                        <?php
                            }else{
                        ?>
                            <button name="activate" value='<?= $row2["co_id"].'-'.$row2["co_stock"] ?>'>Activate</button>
                        <?php
                            }
                        ?>
                    </td>
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
                    <th>ACTION</th>
                </tr>
            <?php
                        $query = "SELECT * FROM color WHERE co_kc_id = '".$row1["kc_id"]."'";
                        if(isset($_POST["applystat"])){
                            if($_POST["filterstat"] != ""){
                                $query .= " AND co_status = '".$_POST["filterstat"]."'";
                            }
                        }
                        $result2 = mysqli_query($conn, $query);

                        while($row2 = mysqli_fetch_array($result2)){
            ?>
                <tr>
                    <td><?= $row2["co_id"] ?></td>
                    <td><img src="<?= $row2['co_link'] ?>" width="100px"></td>
                    <td><?= $row2["co_stock"] ?></td>
                    <td><?= $row2["co_status"] ?></td>
                    <td>
                        <button type="button" value="<?= $row2["co_id"].'-'.$row2["co_stock"] ?>" onclick="edit(this)">Edit Stock</button>
                        <button type="button" value="<?= $row2["co_id"].'|'.$row2["co_link"] ?>" onclick="editimg(this)">Edit Image</button>
                        <?php 
                            if($row2["co_status"] == 1){
                        ?>
                            <button name="deactivate" value='<?= $row2["co_id"].'-'.$row2["co_stock"] ?>'>Deactivate</button>
                        <?php
                            }else{
                        ?>
                            <button name="activate" value='<?= $row2["co_id"].'-'.$row2["co_stock"] ?>'>Activate</button>
                        <?php
                            }
                        ?>
                    </td>
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

        <div style="float: left;">
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
            <br><br>
            <button><a style="color: black; text-decoration: none;" href="printcolor.php">Print Semua Color</a></button>
            
            <div id="edit" style="display: none;">
                <h3>EDIT STOCK</h3>
                <h4 id="idstock"></h4>
                BEFORE : 
                <input type="number" id="before" disabled><br><br>
                AFTER : 
                <input type="number" name="after" min=0><br><br>
                <button type="button" onclick="change()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br><br>
            <div id="editimg" style="display: none;">
                <h3>EDIT PHOTO</h3>
                <h4 id="idimg"></h4>
                BEFORE : 
                <img src="" alt="asd" id="beforeimg" style="width: 200px;"><br><br>
                CHANGE IMG :  
                <input type="file" name="photoedit" accept="image/png, image/jpeg, image/jpg,image/webp"><br><br>
                <button type="button" onclick="changeimg()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br><br>

            <div id="confirm" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                ARE YOU SURE?<br><br>
                <button type="submit" name="yes" id="yes" value="">YES</button>
                <button type="button" onclick="no()">NO</button>
            </div>
            <div id="confirmimg" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                ARE YOU SURE?<br><br>
                <button type="submit" name="yesimg" id="yesimg" value="">YES</button>
                <button type="button" onclick="no()">NO</button>
            </div>
        </div>
    </form>
</body>
<script>
    isi = document.getElementById("edit")
    isiimg = document.getElementById("editimg")
    conf = document.getElementById("confirm")
    confimg = document.getElementById("confirmimg")
    id = ""
    stock = 0

    function edit(obj){
        isi.style.display = "block"
        isiimg.style.display = "none"

        id = obj.value.split("-")[0]
        stock = obj.value.split("-")[1]

        document.getElementById("idstock").innerHTML = id
        document.getElementById("before").value = stock
    }

    function editimg(obj){
        isiimg.style.display = "block"
        isi.style.display = "none"

        id = obj.value.split("|")[0]
        link = obj.value.split("|")[1]

        document.getElementById("idimg").innerHTML = id
        document.getElementById("beforeimg").src = link
    }

    function cancel(){
        isi.style.display = "none"
        isiimg.style.display = "none"
        
        no()
    }

    function no(){
        conf.style.display = "none"
        confimg.style.display = "none"
        document.getElementById("yes").value = ""
    }

    function change(){
        conf.style.display = "block"

        document.getElementById("yes").value = id
    }

    function changeimg(){
        confimg.style.display = "block"

        document.getElementById("yesimg").value = id
    }
</script>
</html>