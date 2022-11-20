<?php
    require_once("connection.php");

    $cart_item = [];

    if (isset($_SESSION["auth_user_id"])) {
        $query = mysqli_query($conn, "SELECT * FROM cart JOIN color ON ca_co_id = co_id WHERE ca_us_id = '". $_SESSION["auth_user_id"] . "'");
        while ($row = mysqli_fetch_array($query)) {
            $cart_item[] = $row;
        }
    }
    
    if (isset($_SESSION["auth_user_id"])) {
        if (isset($_REQUEST["qty"])) {
            $co_id = $_REQUEST["co_id"];
            $qty = $_REQUEST["qty"];
            $kc_price = $_REQUEST["kc_price"];
            
            $items = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM cart WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'"));
            
            if (isset($items[0])) {
                $query = mysqli_query($conn, "UPDATE cart SET ca_qty = '" . $qty + $items["ca_qty"] . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
                $query = mysqli_query($conn, "UPDATE cart SET ca_subtotal = '" . ($qty + $items["ca_qty"]) * $kc_price . "' WHERE ca_co_id = '$co_id' AND ca_us_id = '" . $_SESSION["auth_user_id"] . "'");
            } else {
                $query = mysqli_query($conn, "INSERT INTO cart VALUES('" . $_SESSION["auth_user_id"] . "', '$co_id', '$qty', '" . $qty * $kc_price . "')");
            }
        }
    }
    if (count($cart_item) != 0) {
?>
        <p class="position-absolute bg-danger text-white fw-bold rounded-5 start-50 px-2" style="z-index: 2; font-size: 12px;"><?= count($cart_item) ?></p>
<?php
    }
?>
<img src="storage/icons/cart.png" class="mx-lg-3 mx-0 ms-3 opacity-50" width="30px">
