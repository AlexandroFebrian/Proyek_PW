<?php
    require_once("connection.php");

    if (isset($_REQUEST["ht_id"])) {
        $ht_id = $_REQUEST["ht_id"];
        $query = mysqli_query($conn, "UPDATE htrans SET ht_status = '3' WHERE ht_id = '$ht_id'");
        $_SESSION["email"] = $ht_id;
        require_once("mailer.php");
    }
?>