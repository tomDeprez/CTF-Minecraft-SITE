<?php
include 'db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Minecraft Server</title>
    <link rel="stylesheet" href="css/styleform.css">
</head>
<body>
    <div class="container">
        <h1>Connexion</h1>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
    </div>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: admin.php');
                exit;
            } else {
                echo "<p style='text-align:center; color:red;'>Nom d'utilisateur ou mot de passe incorrect.</p>";
            }
        } catch (PDOException $e) {
            echo "<p style='text-align:center; color:red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }
    ?>
</body>
</html>
