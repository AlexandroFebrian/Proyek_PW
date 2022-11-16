<?php
    require_once("connection.php");

    $co_id = $_POST["id"];
    $ganti = $_POST["ganti"];

    $_SESSION["ganti"] = $ganti;

    $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id JOIN kacamata ON co_kc_id = kc_id JOIN brand ON kc_br_id = br_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "' AND ca_co_id = '".$co_id."'");

    $jum = 0;
    $stock = 0;
    while ($row = mysqli_fetch_array($query)) {
        $jum = $row["ca_qty"];
        $stock = $row["kc_stock"];
        $price = $row["kc_price"];
    }



    
    if($ganti == "-"){
        if($jum > 1){
            $jum--;

        }
    }else{
        if($jum < $stock){
            $jum++;

        }
    }

    $subtotal = $price*$jum;

    mysqli_query($conn, "UPDATE cart SET ca_qty = '$jum' WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "' AND ca_co_id = '".$co_id."'");
    mysqli_query($conn, "UPDATE cart SET ca_subtotal = '$subtotal' WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "' AND ca_co_id = '".$co_id."'");

?>