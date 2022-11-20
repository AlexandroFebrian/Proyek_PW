<?php
    require_once("connection.php");

    if (!isset($_REQUEST["co_id"])) {
        header("Location: index.php");
    }

    $us_id = $_SESSION["auth_user_id"];
    $co_id = $_REQUEST["co_id"];

    $query = mysqli_query($conn, "DELETE FROM cart WHERE ca_us_id = '$us_id' AND ca_co_id = '$co_id'");
?>