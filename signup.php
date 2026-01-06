<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "users_db";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Regisztráció</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="dark-mode">

<div class="form-container">
    <h2>Regisztráció</h2>
    <form method="POST" action="signup.php">
        <label for="username">Felhasználónév:</label>
        <input type="text" id="username" name="username" required>

        <label for="password" >Jelszó:</label><br>
        <input type="password" id="password" name="password" required>

        <button type="submit">Regisztráció</button>
    </form>
    <p><br>Már van fiókod? <a href="login.php">Jelentkezz be</a></p>
</div>

</body>
</html>