<?php
$resultat_recherche = "";

if (isset($_POST['rechercher_habitant'])) {
    $nom_saisi = trim($_POST['nom_saisi']);
    $trouve = false;

    if (file_exists("registre.txt")) {
        $f = fopen("registre.txt", "r");
        while (($ligne = fgets($f)) !== false) {
            // On cherche le nom dans la ligne
            if (stripos($ligne, $nom_saisi) !== false) {
                $trouve = true;
                $infos = explode(" | ", $ligne);
                
                // On récupère les 10 infos selon ton nouveau registre
                $n_reg    = trim($infos[0]);
                $p_enfant = trim($infos[1]);
                $n_enfant = trim($infos[2]);
                $d_naiss  = trim($infos[3]);
                $h_naiss  = trim($infos[4]);
                $l_naiss  = trim($infos[5]);
                $p_pere   = trim($infos[6]);
                $p_mere   = trim($infos[7]);
                $email    = trim($infos[8]);
                $whatsapp = trim($infos[9]);

                // Affichage du résultat stylé en vert
                $resultat_recherche = "
                    <div style='background: #eafaf1; border: 2px solid #27ae60; padding: 20px; border-radius: 8px; margin-bottom: 25px; border-left: 10px solid #27ae60;'>
                        <h3 style='color: #27ae60; margin-top: 0;'>✅ Acte trouvé : n° $n_reg</h3>
                        <p><strong>Identité :</strong> $p_enfant $n_enfant (Né le $d_naiss à $h_naiss)</p>
                        <p><strong>Parents :</strong> $p_pere & $p_mere</p>
                        <hr style='border:0; border-top:1px solid #ccc;'>
                        <a href='retrait.php?n_reg=$n_reg&p=$p_enfant&n=$n_enfant&e=$email&w=$whatsapp' 
                           style='display: inline-block; background: #27ae60; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top:10px;'>
                           ACCÉDER AU RETRAIT (PDF / WHATSAPP)
                        </a>
                    </div>";
                break; 
            }
        }
        fclose($f);
    }

    if (!$trouve && !empty($nom_saisi)) {
        $resultat_recherche = "<div style='background: #fdeaea; color: #eb5757; padding: 15px; border-radius: 8px; border: 1px solid #eb5757; margin-bottom: 20px; text-align: center;'>
                                ❌ Aucun acte trouvé pour le nom : <strong>$nom_saisi</strong>
                              </div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de SACRE KEUR - Demande</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { background: white; max-width: 800px; margin: auto; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        
        h1 { color: #2c3e50; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase; font-size: 24px; }
        
        .section-title { background: #3498db; color: white; padding: 10px 15px; margin-top: 25px; margin-bottom: 15px; border-radius: 4px; font-size: 16px; font-weight: bold; }
        
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; color: #34495e; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 15px; }
        
        .btn-submit { background-color: #3498db; color: white; border: none; padding: 15px; border-radius: 5px; cursor: pointer; font-size: 18px; font-weight: bold; margin-top: 30px; width: 100%; transition: 0.3s; }
        .btn-submit:hover { background-color: #2980b9; }
        
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #7f8c8d; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Demande d'Extrait de Naissance</h1>

    <?php echo $resultat_recherche; ?>

    <form method="POST">
        <div class="section-title">Informations de la personne concernée</div>
        <div class="grid">
            <div>
                <label>Nom :</label>
                <input type="text" name="nom_saisi" placeholder="Nom de l'habitant" required>
            </div>
            <div>
                <label>Prénom(s) :</label>
                <input type="text" placeholder="Prénom(s)">
            </div>
            <div>
                <label>Date de naissance :</label>
                <input type="date">
            </div>
            <div>
                <label>Lieu de naissance :</label>
                <input type="text" placeholder="Ville / Commune">
            </div>
        </div>

        <div class="section-title">Filiation (Parents)</div>
        <div class="grid">
            <div>
                <label>Nom complet du père :</label>
                <input type="text" placeholder="Prénom et Nom du père">
            </div>
            <div>
                <label>Nom complet de la mère :</label>
                <input type="text" placeholder="Prénom et Nom de la mère">
            </div>
        </div>

        <div class="section-title">Coordonnées de réception (Digital)</div>
        <div class="grid">
            <div>
                <label>Email :</label>
                <input type="email" placeholder="exemple@mail.com">
            </div>
            <div>
                <label>Numéro WhatsApp :</label>
                <input type="text" placeholder="77XXXXXXX">
            </div>
        </div>

        <button type="submit" name="rechercher_habitant" class="btn-submit">SOUMETTRE LA DEMANDE</button>
    </form>

    <a href="index.php" class="back-link">⬅ Retour au menu principal</a>
</div>

</body>
</html>