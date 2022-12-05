<?php
    require_once("connection.php");

    ob_start();

    if(!isset($_SESSION["admin"])){
        header("Location: index.php");
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
    <?php
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Data User.xls");
    ?>
    <table border 1px>
        <?php
            $query = mysqli_query($conn, "SELECT * FROM users");
            while ($row = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $row["us_id"] ?></td>
                <td><?= $row["us_username"] ?></td>
                <td><?= $row["us_email"] ?></td>
                <td><?= $row["us_name"] ?></td>
                <td><?= $row["us_birth"] ?></td>
                <td><?= $row["us_gender"] ?></td>
                <td><?= $row["us_phone"] ?></td>
                <td><?= $row["us_address"] ?></td>
                <td><?= $row["us_status"] ?></td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>