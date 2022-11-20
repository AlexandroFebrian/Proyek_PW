<?php
    require_once("connection.php");

    $msg = "";

    // if (isset($_SESSION["auth_user_id"])) {
    //     unset($_SESSION["auth_user_id"]);
    // }

    if (isset($_POST["register"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];
        $name = $_POST["name"];
        $birth = $_POST["birth"];
        $gender = $_POST["gender"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];

        if ($username != "" && $email != "" && $password != "" && $cpassword != "" && $name != "" && $birth != "" && $gender != "" && $phone != "" && $address != "") {
            if ($password == $cpassword) {
                // Username & Email unique
                $query = "SELECT * FROM users WHERE us_username LIKE ? OR us_email LIKE ?";
                $searching = $conn -> prepare($query);
                $searching -> bind_param("ss", $username, $email);
                $searching -> execute();
                $searching = $searching -> get_result();
                if (!isset(mysqli_fetch_array($searching)[0])) {
                    // Generate Users ID
                    $query = mysqli_query($conn, "SELECT MAX(SUBSTR(us_id, 3)) FROM users");
                    $new_us_id = "US" . str_pad(mysqli_fetch_array($query)[0] + 1, 4, "0", STR_PAD_LEFT);

                    // Insert DB
                    $password = password_hash($password, PASSWORD_BCRYPT);

                    $query = "INSERT INTO users VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $inserting = $conn -> prepare($query);
                    $inserting -> bind_param("sssssssss", $new_us_id, $username, $email, $password, $name, $birth, $gender, $phone, $address);
                    $inserting -> execute();

                    // Count length awal
                    $query = mysqli_query($conn, "SELECT COUNT(us_id) FROM users");
                    $count1 = mysqli_fetch_array($query)[0];

                    // Delete injection DB
                    $query = mysqli_query($conn, "DELETE FROM users WHERE us_username LIKE '%<?%' OR us_username LIKE '%<script>%' OR us_name LIKE '%<?%' OR us_name LIKE '%<script>%' OR us_phone LIKE '%<?%' OR us_phone LIKE '%<script>%' OR us_address LIKE '%<?%' OR us_address LIKE '%<script>%'");

                    // Count length akhir
                    $query = mysqli_query($conn, "SELECT COUNT(us_id) FROM users");
                    $count2 = mysqli_fetch_array($query)[0];

                    // Header ke mana
                    if ($count1 == $count2) {
                        header("Location: login.php");
                    } else {
                        header("Location: tanganeglitis.php");
                    }
                } else {
                    $msg = "Username / Email sudah dipakai!";
                }
            } else {
                $msg = "Confirmation password salah!";
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
    
    <!-- Register Form -->
    <div class="container w-75 bg-white p-5">
        <h2 class="fw-bold">Register</h2>
        <form method="POST">
            <div class="my-3">
                <input type="text" class="form-control bg-light" placeholder="Username" name="username">
            </div>
            <div class="my-3">
                <input type="email" class="form-control bg-light" placeholder="Email" name="email">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control bg-light" placeholder="Password" name="password">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control bg-light" placeholder="Confirmation Password" name="cpassword">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control bg-light" placeholder="Name" name="name">
            </div>
            <div class="mb-3">
                <label class="form-label">Date of birth</label><br>
                <input type="date" class="form-control bg-light" name="birth">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" class="bg-light" name="gender" value="M" checked> Laki-laki
                <input type="radio" class="bg-light" name="gender" value="W"> Perempuan
            </div>
            <div class="mb-3">
                <input type="tel" class="form-control bg-light" placeholder="Phone Number" name="phone">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control bg-light" placeholder="Address" name="address">
            </div>
            <?php
                if ($msg != "") {
                    echo "<label class='form-label text-danger'>$msg</label><br>";
                }
            ?>
            <button type="submit" class="btn btn-success w-100" name="register">Register</button>
            <div class="mb-3">
                <label class="form-label">Have an account? <a href="login.php">Login</a></label>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</body>
</html>