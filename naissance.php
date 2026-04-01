<?php
if (isset($_POST['declarer'])) {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $nom_pere = $_POST['nom_pere']
    $nom_mere = $_POST['nom_mere']
    $date_naissance = $_POST['date_naissance'];
    $lieu_naissance = $_POST['lieu_naissance'];
    // Génération d'un numéro de registre unique basé sur le temps (ex: 171563420)
$num_registre = time(); 

// On ajoute ce numéro au début de la ligne [cite: 8, 13]
$ligne = $num_registre . " | " . $prenom . " | " . $nom . " | " . $date_naissance . " | " . $lieu_naissance . " | " . $parents . "\n";

// Utilisation de fopen et fwrite comme demandé [cite: 12, 13]
$monFichier = fopen("registre.txt", "a");
fwrite($monFichier, $ligne);
fclose($monFichier);

    // 1. Utilisation de fopen() pour ouvrir le fichier en mode "a" (append / ajout)
    // Si le fichier registre.txt n'existe pas, il sera créé automatiquement.
    $monFichier = fopen("registre.txt", "a"); [cite: 12]

    if ($monFichier) {
        // Préparation de la ligne à inscrire (séparée par des points-virgules pour plus de clarté)
        $donnees = $prenom . " | " . $nom . " | " . $date_naissance . " | " . $lieu_naissance . " | " . $parents . "\n";

        // 2. Utilisation de fwrite() pour enregistrer la déclaration [cite: 13]
        fwrite($monFichier, $donnees); 

        // Fermeture du fichier
        fclose($monFichier);

        echo "<p style='color: green;'>La déclaration de naissance pour $prenom $nom a été enregistrée avec succès !</p>";
    } else {
        echo "<p style='color: red;'>Erreur lors de l'ouverture du registre.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de SACRE KEUR - Déclaration de naissance</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; }
        label { display: block; margin-top: 10px; }
        input { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #27ae60; color: white; padding: 10px; border: none; width: 100%; margin-top: 20px; cursor: pointer; border-radius: 4px; }
        button:hover { background-color: #219150; }
        .back-link { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #34495e; }
    </style>
</head>
<body>

<div class="container">
    [cite_start]<h2>Déclarer un Nouveau-Né</h2> [cite: 5]
    <form method="POST" action="">
        <label>Prénom de l'enfant :</label>
        <input type="text" name="prenom" required>

        <label>Nom de l'enfant :</label>
        <input type="text" name="nom" required>

        <label>Date de naissance :</label>
        <input type="date" name="date_naissance" required>

        <label>Lieu de naissance :</label>
        <input type="text" name="lieu_naissance" placeholder="Ex: Sacré Cœur" required>

        <label>Noms des parents :</label>
        <input type="text" name="parents" placeholder="Père et Mère" required>

        <button type="submit" name="declarer">Enregistrer la déclaration</button>
    </form>
    <a href="index.php" class="back-link">Retour à l'accueil</a>
</div>

</body>
</html>