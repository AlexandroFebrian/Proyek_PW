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
        $weight = $_POST["weight"];
        
        if($price == "" || $weight == "" || !isset($_POST["gender"])){
            $_SESSION["msg"] = "FIELD KOSONG";
        }else{
            $gender = $_POST["gender"];
            $result = mysqli_query($conn, "SELECT MAX(kc_id) FROM kacamata");

            $count = substr(mysqli_fetch_array($result)[0], 2);
            $count++;

            $kc_id = "KC".str_pad($count, 3, "0", STR_PAD_LEFT);

            mysqli_query($conn, "INSERT INTO kacamata VALUES ('$kc_id', '$price', '$gender', '$weight', '$br_id')");
            $_SESSION["msg"] = "BERHASIL ADD PRODUCT";
        }
    }

    if(isset($_POST["yesprice"])){
        if($_POST["yesprice"] != ""){
            $id = $_POST["yesprice"];
            $price = $_POST["after"];
            if($price != ""){
                mysqli_query($conn, "UPDATE kacamata SET kc_price = '$price' WHERE kc_id = '$id'");
    
                $_SESSION["msg"] = "BERHASIL GANTI HARGA";
            }else{
                $_SESSION["msg"] = "FIELD KOSONG";
            }
        }
    }

    if(isset($_POST["yesgender"])){
        if($_POST["yesgender"] != ""){
            $id = $_POST["yesgender"];
            $gender = $_POST["genderedit"];
            mysqli_query($conn, "UPDATE kacamata SET kc_gender = '$gender' WHERE kc_id = '$id'");

            $_SESSION["msg"] = "BERHASIL GANTI GENDER";
        }
    }

    if(isset($_POST["yesweight"])){
        if($_POST["yesweight"] != ""){
            $id = $_POST["yesweight"];
            $weight = $_POST["afterweight"];
            if($weight != ""){
                mysqli_query($conn, "UPDATE kacamata SET kc_weight = '$weight' WHERE kc_id = '$id'");
    
                $_SESSION["msg"] = "BERHASIL GANTI BERAT";
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
                <th>Weight</th>
                <th>Brand Name</th>
                <th>Action</th>
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
                <td id="g"><?= $row["kc_gender"] ?></td>
                <td><?= $row["kc_weight"] ?>g</td>
                <td><?= $row["br_name"] ?></td>
                <td>
                    <button type="button" name="price" onclick="editprice(this)" value='<?= $row["kc_id"].'-'.$row["kc_price"] ?>'>Edit Price</button>
                    <button type="button" name="gender" onclick="editgender(this)" value='<?= $row["kc_id"].'-'.$row["kc_gender"] ?>'>Edit Gender</button>
                    <button type="button" name="gender" onclick="editweight(this)" value='<?= $row["kc_id"].'-'.$row["kc_weight"] ?>'>Edit Weight</button>
                </td>
            </tr>
            <?php
                }
            ?>
        </table>

        <div style="float:left;">
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
            WEIGHT : 
            <input type="number" name="weight" min=1><br><br>
            GENDER : 
            <input type="radio" name="gender" id="M" value="M">
            <label for="M">MAN</label>
            <input type="radio" name="gender" id="W" value="W">
            <label for="W">WOMAN</label><br><br>
    
            <button type="submit" name="add">ADD</button>
            <br><br>
            <button><a style="color: black; text-decoration: none;" href="printproduct.php">Print Semua Product</a></button>
                
            <div id="editprice" style="display: none;">
                <h3>EDIT PRICE</h3>
                <h4 id="idprice"></h4>
                BEFORE : 
                <input type="number" id="before" disabled><br><br>
                AFTER : 
                <input type="number" name="after" min=1><br><br>
                <button type="button" onclick="changeprice()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br>
            <div id="confirmprice" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                ARE YOU SURE?<br><br>
                <button type="submit" name="yesprice" id="yesprice" value="">YES</button>
                <button type="button" onclick="no()">NO</button>
            </div>

            <div id="editgender" style="display: none;">
                <h3>EDIT GENDER</h3>
                <h4 id="idgender"></h4>
                <input type="radio" name="genderedit" id="ME" value="M">
                <label for="ME">MAN</label>
                <input type="radio" name="genderedit" id="WE" value="W">
                <label for="WE">WOMAN</label><br><br>
                <button type="button" onclick="changegender()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br>
            <div id="confirmgender" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                ARE YOU SURE?<br><br>
                <button type="submit" name="yesgender" id="yesgender" value="">YES</button>
                <button type="button" onclick="no()">NO</button>
            </div>
            
            <div id="editweight" style="display: none;">
                <h3>EDIT PRICE</h3>
                <h4 id="idweight"></h4>
                BEFORE : 
                <input type="number" id="beforeweight" disabled><br><br>
                AFTER : 
                <input type="number" name="afterweight" min=1><br><br>
                <button type="button" onclick="changeweight()">Change</button>
                <button type="button" onclick="cancel()">Cancel</button>
            </div><br>
            <div id="confirmweight" style="display: none; border: 1px solid black; border-radius: 5px; padding: 20px;">
                <h2 style="margin: 0px;">CONFIRM CHANGE</h2><br>
                ARE YOU SURE?<br><br>
                <button type="submit" name="yesweight" id="yesweight" value="">YES</button>
                <button type="button" onclick="no()">NO</button>
            </div>
        </div>
    </form>
</body>
<script>
    isiprice = document.getElementById("editprice")
    isigender = document.getElementById("editgender")
    isiweight = document.getElementById("editweight")
    id = ""
    price = ""
    gender = ""
    weight = ""

    function editprice(obj){
        isiprice.style.display = "block"
        isigender.style.display = "none"
        isiweight.style.display = "none"

        id = obj.value.split("-")[0]
        price = obj.value.split("-")[1]

        document.getElementById("idprice").innerHTML = id
        document.getElementById("before").value = price
    }

    function editgender(obj){
        isigender.style.display = "block"
        isiprice.style.display = "none"
        isiweight.style.display = "none"

        id = obj.value.split("-")[0]
        gender = obj.value.split("-")[1]

        document.getElementById("idgender").innerHTML = id
        if(gender == "M"){
            document.getElementById("ME").checked = true
        }else{
            document.getElementById("WE").checked = true
        }
    }

    function editweight(obj){
        isiweight.style.display = "block"
        isigender.style.display = "none"
        isiprice.style.display = "none"

        id = obj.value.split("-")[0]
        weight = obj.value.split("-")[1]

        document.getElementById("idweight").innerHTML = id
        document.getElementById("beforeweight").value = weight
    }

    function cancel(){
        isigender.style.display = "none"
        isiprice.style.display = "none"
        isiweight.style.display = "none"

        no()
    }

    function changeprice(){
        document.getElementById("confirmprice").style.display = "block"
        document.getElementById("yesprice").value = id
    }

    function changegender(){
        document.getElementById("confirmgender").style.display = "block"
        document.getElementById("yesgender").value = id
    }

    function changeweight(){
        document.getElementById("confirmweight").style.display = "block"
        document.getElementById("yesweight").value = id
    }

    function no(){
        document.getElementById("confirmprice").style.display = "none"
        document.getElementById("confirmgender").style.display = "none"
        document.getElementById("confirmweight").style.display = "none"

        document.getElementById("yesprice").value = ""
        document.getElementById("yesgender").value = ""
        document.getElementById("yesweight").value = ""
    }
</script>
</html>