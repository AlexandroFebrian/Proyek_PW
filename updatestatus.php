<?php
    require_once("connection.php");

    if (isset($_REQUEST["ht_id"])) {
        $ht_id = $_REQUEST["ht_id"];
        $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '1' WHERE ht_id = '$ht_id'");
        $_SESSION["email"] = "OK";
        require_once("mailer.php");
    }
?>