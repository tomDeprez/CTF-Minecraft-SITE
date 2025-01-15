<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Minecraft Server</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</h1>
        <p>Rôle : <?php echo htmlspecialchars($_SESSION['role']); ?></p>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="create_admin.php">Créer un nouvel admin</a>
        <?php endif; ?>
        
        <a href="logout.php">Déconnexion</a>
        <h1>Tableau des Équipes</h1>
  <div class="team blue">
    <h2>Équipe Bleue</h2>
    <p>Joueurs : <span id="blue-count">0</span></p>
    <ul id="blue-players"></ul>
  </div>
  <div class="team red">
    <h2>Équipe Rouge</h2>
    <p>Joueurs : <span id="red-count">0</span></p>
    <ul id="red-players"></ul>
  </div>
  <div id="teams-container"></div>
  <script>
    async function fetchTeamInfo() {
      try {
        const response = await fetch("http://192.168.1.37:4567/api/teams");
        const data = await response.json();
    
        const teamsContainer = document.getElementById("teams-container");
        teamsContainer.innerHTML = ""; // Réinitialise le contenu
    
        // Fonction pour créer un bloc pour chaque joueur
        const createPlayerBlock = (playerName, teamColor) => {
          const playerDiv = document.createElement("div");
          playerDiv.style = `border: 2px solid ${teamColor}; background: rgba(0, 0, 0, 0.8); padding: 10px; margin: 10px; border-radius: 8px; color: #FFF; text-align: center;`;
          playerDiv.innerHTML = `
            <p>${playerName}</p>
            <div class="resources">
              <img src="https://minecraft.wiki/images/Oak_Wood_%28UD%29_JE5_BE2.png?74743" alt="Bois" title="Donner du bois" onclick="giveItem('${playerName}', 'OAK_WOOD')">
              <img src="https://static.wikia.nocookie.net/sevtechages_gamepedia_en/images/4/42/Stone-block-png-3.png" alt="Pierre" title="Donner de la pierre" onclick="giveItem('${playerName}', 'STONE')">
              <img src="https://static.wikia.nocookie.net/minecraft_gamepedia/images/5/58/Coal_JE4_BE3.png" alt="Charbon" title="Donner du charbon" onclick="giveItem('${playerName}', 'COAL')">
            </div>
          `;
          return playerDiv;
        };
    
        // Ajouter les joueurs de l'équipe bleue
        const blueTitle = document.createElement("h2");
        blueTitle.textContent = "Équipe Bleue";
        blueTitle.style = "color: #1E90FF;";
        teamsContainer.appendChild(blueTitle);
    
        data.blue.players.forEach(player => {
          teamsContainer.appendChild(createPlayerBlock(player, "#1E90FF"));
        });

        document.getElementById("blue-count").innerHTML=data.blue.count; 
        document.getElementById("red-count").innerHTML=data.blue.count; 
    
        // Ajouter les joueurs de l'équipe rouge
        const redTitle = document.createElement("h2");
        redTitle.textContent = "Équipe Rouge";
        redTitle.style = "color: #FF4500;";
        teamsContainer.appendChild(redTitle);
    
        data.red.players.forEach(player => {
          teamsContainer.appendChild(createPlayerBlock(player, "#FF4500"));
        });
      } catch (error) {
        console.error("Erreur lors de la récupération des données :", error);
      } finally {
        setTimeout(fetchTeamInfo, 5000); // Rafraîchir toutes les 5 secondes
      }
    }
    
    // Fonction pour appeler l'API /api/give
    function giveItem(player, item) {
      fetch("http://o96ohwp8.fbxos.fr:4567/api/give", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `player=${player}&item=${item}`,
      })
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            alert("Erreur : " + data.error);
          } else {
            alert(data.success);
          }
        })
        .catch(error => {
          console.error("Erreur lors de l'envoi de l'item :", error);
        });
    }
    
    // Lancement initial
    fetchTeamInfo();
    </script>
    </div>
</body>
</html>
