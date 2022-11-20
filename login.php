<?php
    require_once("connection.php");
    
    $msg = "";

    // if (isset($_SESSION["auth_user_id"])) {
    //     unset($_SESSION["auth_user_id"]);
    // }

    if (isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        if ($username != "" && $password != "") {
            $query = "SELECT * FROM users WHERE us_username LIKE ? OR us_email LIKE ?";
            $searching = $conn -> prepare($query);
            $searching -> bind_param("ss", $username, $username);
            $searching -> execute();
            $searching = $searching -> get_result();
            $searching = mysqli_fetch_array($searching);
            if (isset($searching[0])) {
                if (password_verify($password, $searching["us_password"])) {
                    $_SESSION["auth_user_id"] = $searching["us_id"];
                    header("Location: index.php");
                } else {
                    $msg = "Password salah";
                }
            } else {
                $msg = "Username tidak terdaftar";
            }
        } else {
            $msg = "Semua field harus diisi!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Optik Primadona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-white p-3 position-sticky top-0 w-100 border-bottom" style="z-index: 5;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"><h4 class="m-0">Optik Primadona</h4></a>
        </div>
    </nav>

    <div class="container w-75 bg-white p-5">
        <h2 class="fw-bold">Login</h2>
        <form method="POST">
            <div class="my-3">
                <input type="text" class="form-control bg-light" placeholder="Username / Email" name="username">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control bg-light" placeholder="Password" name="password">
            </div>
            <?php
                if ($msg != "") {
                    echo "<label class='form-label text-danger'>$msg</label><br>";
                }
            ?>
            <button type="submit" class="btn btn-success w-100" name="login">Login</button>
            <div class="mb-3">
                <label class="form-label">No account? <a href="register.php">Register</a></label>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>