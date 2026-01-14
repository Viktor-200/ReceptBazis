<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: konyhaisegedletek.php");
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
    <title>Konyhai Segédletek</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        
        .header {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #17161B;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            z-index: 1000;
            transition: background-color 0.3s, color 0.3s;
        }

        body.light-mode .header {
            background-color: #fff;
            color: black;
        }

        .logo-container {
            display: flex;
            align-items: center;
        }

        #logo {
            max-height: 150px;
            width: auto;
        }

        .navbar {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #toggleBtn {
            padding: 10px;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px;
            border-radius: 50%;
        }

        #signoutBtn {
            padding: 10px 20px;
            background-color: #5e9cff;
            color: white;
            font-weight: bold;
            border-radius: 4px;
            text-decoration: none;
        }

        .fixed-search-container {
            position: fixed;
            top: 120px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1001;
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 800px;
        }

        .fixed-search-container form {
            display: flex;
            gap: 10px;
            width: 100%;
        }

        .fixed-search-container input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 4px;
            border: none;
            background-color: #555;
            color: white;
        }

        body.light-mode .fixed-search-container input[type="text"] {
            background-color: #eee;
            color: black;
        }

        .fixed-search-container button {
            padding: 10px 15px;
            background-color: #5e9cff;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            flex-shrink: 0;
        }

        .content {
            padding-top: 250px;
            width: 90%;
            max-width: 1200px;
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #5e9cff;
            color: white;
        }

        body.dark-mode td {
            color: white;
            border-color: #666;
        }

        body.light-mode td {
            color: black;
            border-color: #ccc;
        }
    </style>
</head>
<body class="dark-mode">

<div class="header">
    <div class="logo-container">
        <a href="index.php">
            <img id="logo" src="img/feketelogo.png" alt="Receptbázis">
        </a>
    </div>

    <div class="navbar">
        <button id="toggleBtn">
            <i id="themeIcon" class="fas fa-moon"></i>
        </button>

        <?php if (isset($_SESSION['username'])): ?>
            <a id="signoutBtn" href="konyhaisegedletek.php?logout=true">Kijelentkezés</a>
        <?php else: ?>
            <a id="signoutBtn" href="login.php">Bejelentkezés</a>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($_SESSION['username'])): ?>
<div class="fixed-search-container">
    <form method="GET">
        <input type="text" name="search" placeholder="Keress receptek között..." required>
        <button type="submit">Keresés</button>
    </form>
</div>
<?php endif; ?>

<div class="content">
    <h1>Konyhai Segédletek</h1>

    <table>
        <thead>
            <tr>
                <th>Fogalom</th>
                <th>Magyarázat</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Abálás</td><td>Folyamatos, forráspont alatti 95°C-on történő hőkezelést, főzést jelent. (abáljuk pl. a szalonnát, a hurkaféléket)</td></tr>
            <tr><td>Al dente</td><td>"ami jó a fognak" - a nem puhára, haraphatóra főzött tészta jelzője</td></tr>
            <tr><td>Angolos bundázás</td><td>A bundázás egyik ismert formája. Azt jelenti, hogy a húst, a halat vagy egyéb sütni kívánt alapanyagot előbb olvasztott vajba mártjuk, majd zsemlemorzsába forgatjuk és kisütjük.</td></tr>
            <tr><td>Angolos-ra (rare) sütés</td><td>Az egészen angolos pecsenyének csak a külső pereme sül át, a hús közepe felé haladva egyre nyersebb, a hús közepe pedig teljesen nyers, véres.</td></tr>
            <tr><td>Aszpik</td><td>A hidegkonyha egyik legfontosabb alapanyaga. Készíthető csontokból, bőrökből, zselatinból is. Célja hogy megvédje a tálra kitett pecsenyéket a kiszáradástól, emellett szép csillogó is lesz tőle az étel.</td></tr>
        </tbody>
    </table>
</div>

<script src="script.js"></script>
</body>
</html>