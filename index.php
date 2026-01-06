<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

<div class="header">
    <div class="logo-container">
        <a href="index.php">
            <img id="logo" src="img/feketelogo.png" alt="Receptbázis Főoldal">
        </a>
    </div>

    <div class="navbar">
        <button id="toggleBtn">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>

        <?php if (isset($_SESSION['username'])): ?>
            <button id="signoutBtn"><a href="index.php?logout=true">Kijelentkezés</a></button>
        <?php else: ?>
            <a id="signoutBtn" href="login.php">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>

<div class="content">
    <?php if (isset($_SESSION['username'])): ?>
        <h1 id="welcomeMessage" class="fade-in">Üdvözlünk, <?php echo $_SESSION['username']; ?>!</h1>
    <?php else: ?>
        <h1 class="fade-in">Üdvözlünk a weboldalon!</h1>
    <?php endif; ?>
</div>

<script>
window.addEventListener('load', function() {
    const welcomeMessage = document.getElementById("welcomeMessage");
    if (welcomeMessage) {
        setTimeout(() => {
            welcomeMessage.classList.add("fade-out");
            setTimeout(() => {
                welcomeMessage.style.display = "none";
            }, 2000);
        }, 3000);
    }
});
</script>

<script src="script.js"></script>
</body>
</html>