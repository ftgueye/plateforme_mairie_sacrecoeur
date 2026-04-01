<?php
$statut = "";

if (isset($_POST['valider_declaration'])) {
    
    // 1. Génération automatique du Numéro de Registre (basé sur le temps)
    $num_registre = "REG-" . date("Ymd-His"); 

    // 2. Récupération des données du formulaire
    $prenom = htmlspecialchars($_POST['prenom']);
    $nom    = htmlspecialchars($_POST['nom']);
    $date_naiss = $_POST['date_naiss'];
    $heure_naiss = $_POST['heure_naiss']; // Nouveau
    $lieu_naiss  = htmlspecialchars($_POST['lieu_naiss']);
    $nom_pere    = htmlspecialchars($_POST['nom_pere']);
    $nom_mere    = htmlspecialchars($_POST['nom_mere']);
    
    // Coordonnées pour l'envoi du PDF
    $email_parent = htmlspecialchars($_POST['email_parent']); // Nouveau
    $whatsapp_parent = htmlspecialchars($_POST['whatsapp_parent']); // Nouveau

    // 3. Création de la ligne de données (Ordre précis pour la lecture future)
    // Index : 0:Num | 1:Prénom | 2:Nom | 3:Date | 4:Heure | 5:Lieu | 6:Père | 7:Mère | 8:Email | 9:WhatsApp
    $ligne = $num_registre . " | " . $prenom . " | " . $nom . " | " . $date_naiss . " | " . $heure_naiss . " | " . $lieu_naiss . " | " . $nom_pere . " | " . $nom_mere . " | " . $email_parent . " | " . $whatsapp_parent . "\n";

    // 4. Écriture dans le fichier
    $fichier = fopen("registre.txt", "a");
    if ($fichier) {
        fwrite($fichier, $ligne);
        fclose($fichier);
        $statut = "<div style='background:#d4edda; color:#155724; padding:15px; border-radius:8px; border:1px solid #c3e6cb; margin-bottom:20px;'>
                    ✅ <strong>Succès !</strong> L'acte n° <strong>$num_registre</strong> a été créé.
                  </div>";
    }
}
?>
<?php
include('table.php'); // On inclut le fichier de connexion

if (isset($_POST['valider_declaration'])) {
     $n_reg=  "REG-" . date("Ymd-His");
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $date_naiss = $_POST['date_naiss'];
    $heure_naiss = $_POST['heure_naiss'];
    $lieu_naiss = mysqli_real_escape_string($conn, $_POST['lieu_naiss']);
    $nom_pere = mysqli_real_escape_string($conn, $_POST['nom_pere']);
    $nom_mere = mysqli_real_escape_string($conn, $_POST['nom_mere']);
    $email = mysqli_real_escape_string($conn, $_POST['email_parent']);
    $whatsapp = mysqli_real_escape_string($conn, $_POST['whatsapp_parent']);

    // Requête SQL d'insertion
    // Version recommandée : on laisse la base de données gérer l'ID automatique (n_reg)
$sql = "INSERT INTO naissance (n_reg,prenom, nom, date_naiss, heure_naiss, lieu_naiss, nom_pere, nom_mere, email, whatsapp) 
        VALUES ('$n_reg','$prenom', '$nom', '$date_naiss', '$heure_naiss', '$lieu_naiss', '$nom_pere', '$nom_mere', '$email', '$whatsapp')";

    if (mysqli_query($conn, $sql)) {
        echo "Enregistrement réussI dans la base de données SQL !";
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de SACRE KEUR - Déclaration</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 20px; }
        .container { background: white; max-width: 600px; margin: auto; padding: 25px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        label { display: block; margin-top: 10px; font-weight: bold; font-size: 13px; color: #34495e; }
        input { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        .section-title { background: #3498db; color: white; padding: 5px 10px; margin-top: 20px; border-radius: 4px; font-size: 14px; }
        .btn { background: #3498db; color: white; border: none; padding: 15px; width: 100%; border-radius: 5px; cursor: pointer; font-size: 16px; margin-top: 25px; font-weight: bold; }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>

<div class="container">
    <h2>Enregistrement de Naissance</h2>
    
    <?php echo $statut; ?>

    <form method="POST">
        <div class="section-title">Informations sur l'Enfant</div>
        <div class="grid">
            <div>
                <label>Prénom :</label>
                <input type="text" name="prenom" required>
            </div>
            <div>
                <label>Nom :</label>
                <input type="text" name="nom" required>
            </div>
        </div>
        
        <div class="grid">
            <div>
                <label>Date de naissance :</label>
                <input type="date" name="date_naiss" required>
            </div>
            <div>
                <label>Heure de naissance :</label>
                <input type="time" name="heure_naiss" required>
            </div>
        </div>

        <label>Lieu de naissance :</label>
        <input type="text" name="lieu_naiss" value="Mairie de Sacré Cœur" required>

        <div class="section-title">Filiation (Parents)</div>
        <label>Prénom et Nom du Père :</label>
        <input type="text" name="nom_pere" required>
        <label>Prénom et Nom de la Mère :</label>
        <input type="text" name="nom_mere" required>

        <div class="section-title">Coordonnées de Retrait (Digital)</div>
        <div class="grid">
            <div>
                <label>Email :</label>
                <input type="email" name="email_parent" placeholder="exemple@mail.com" required>
            </div>
            <div>
                <label>Numéro WhatsApp :</label>
                <input type="text" name="whatsapp_parent" placeholder="77XXXXXXX" required>
            </div>
        </div>

        <button type="submit" name="valider_declaration" class="btn">ENREGISTRER L'ACTE OFFICIEL</button>
    </form>
    <p style="text-align:center;"><a href="index.php" style="color:#7f8c8d; text-decoration:none; font-size:14px;">⬅ Retour</a></p>
</div>

</body>
</html>