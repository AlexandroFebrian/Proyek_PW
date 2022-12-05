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
        header("Content-Disposition: attachment; filename=Data Brand.xls");
    ?>
    <table border 1px>
        <?php
            $query = mysqli_query($conn, "SELECT * FROM brand");
            while ($row = mysqli_fetch_array($query)) {
        ?>
            <tr>
                <td><?= $row["br_id"] ?></td>
                <td><?= $row["br_name"] ?></td>
            </tr>
        <?php
            }
        ?>
    </table>
</body>
</html>