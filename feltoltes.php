<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$conn = new mysqli("localhost", "root", "", "users_db");
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    $target_dir = "uploads/";
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $filename = time() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $message = "Hiba történt a kép feltöltésekor.";
            }
        } else {
            $message = "A feltöltött fájl nem kép.";
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO receptek (title, description, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $description, $image_path);
        
        if ($stmt->execute()) {
            $message = "Recept sikeresen feltöltve!";
        } else {
            $message = "Adatbázis hiba: " . $conn->error;
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recept Feltöltés</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body class="dark-mode">

    <?php include 'header.php'; ?>

    <div class="content" style="display: flex; justify-content: center; padding-top: 50px;">
        <div class="form-container" style="max-width: 600px;">
            <h2>Új recept feltöltése</h2>
            
            <?php if ($message): ?>
                <p style="color: #5e9cff; margin-bottom: 15px;"><?php echo $message; ?></p>
            <?php endif; ?>

            <form method="POST" action="feltoltes.php" enctype="multipart/form-data">
                <label for="title">Étel neve (Cím):</label>
                <input type="text" id="title" name="title" required placeholder="Pl. Rakott krumpli">

                <label for="description">Leírás (Elkészítés):</label>
                <textarea id="description" name="description" rows="6" required placeholder="Ide írd a recept leírását..." style="width: 100%; background: #444; color: white; border: none; padding: 10px; border-radius: 4px; margin-bottom: 20px;"></textarea>

                <label for="image">Kép feltöltése:</label>
                <input type="file" id="image" name="image" accept="image/*" style="padding: 10px 0;">

                <button type="submit">Feltöltés</button>
            </form>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>
</html>