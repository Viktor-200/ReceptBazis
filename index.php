<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

$searchResults = [];
if (isset($_GET['search'])) {
    $conn = new mysqli("localhost", "root", "", "users_db");

    if (!$conn->connect_error) {
        $search = "%" . $_GET['search'] . "%";
        $stmt = $conn->prepare("SELECT * FROM recipes WHERE title LIKE ?");
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $searchResults = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Kezdőlap</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>

<div class="header">
    <div class="logo-container">
        <a href="index.php">
            <img id="logo" src="img/feketelogo.png" alt="Receptbázis">
        </a>

        <a href="konyhaisegedletek.php" class="helper-link">
            Konyhai Segédletek
        </a>
    </div>

    <div class="navbar">
        
        <button id="toggleBtn" class="theme-toggle-btn">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>

        <?php if (isset($_SESSION['username'])): ?>
            <a id="signoutBtn" href="index.php?logout=true">Kijelentkezés</a>
        <?php else: ?>
            <a id="signoutBtn" href="login.php">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['username'])): ?>
<div class="top-search">
    <form method="GET">
        <input type="text" name="search" placeholder="Keress receptek között..." required>
        <button type="submit">Keresés</button>
    </form>
</div>
<?php endif; ?>

<div class="content">
<?php if (isset($_SESSION['username'])): ?>
    <h1 id="welcomeMessage" class="fade-in">
        Üdvözlünk, <?php echo htmlspecialchars($_SESSION['username']); ?>!
    </h1>
<?php endif; ?>

<?php if (!empty($searchResults)): ?>
    <div class="results">
        <?php foreach ($searchResults as $recipe): ?>
            <p><?php echo htmlspecialchars($recipe['title']); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
</div>

<script>
window.addEventListener('load', () => {
    const msg = document.getElementById("welcomeMessage");
    if (msg) {
        setTimeout(() => msg.classList.add("fade-out"), 2000);
    }
});
</script>

<script src="script.js"></script>
</body>
</html>