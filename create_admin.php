<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Admin - Minecraft Server</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Créer un Admin</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Créer Admin">
        </form>
        <a href="login.php">Retour à la connexion</a>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'admin')");
            $stmt->execute(['username' => $username, 'password' => $password]);
            echo "<p style='text-align:center; color:#00ff00;'>Admin créé avec succès !</p>";
        } catch (PDOException $e) {
            echo "<p style='text-align:center; color:red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>
