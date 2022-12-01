<?php
    require_once("connection.php");

    unset($_SESSION["admin"]);

    if(isset($_POST["login"])){
        $password = $_POST["password"];

        if(password_verify($password, '$2a$12$D2wkqnqLVPD1kYXeMKAcYuTWEqpO6f76sil0VJCXD7abXd7rmJgdW')){
            $_SESSION["admin"] = true;
            header("Location: admin.php");
        }
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
        <h1>LOGIN ADMIN</h1>
        PASSWORD : <input type="password" name="password">
        <button type="submit" name="login">LOGIN</button>
    </form>
</body>
</html>