<?php
include('table.php'); // Connexion à la base de données

// Vérification de la connexion
if (!$conn) {
    die("<div style='color:red; text-align:center; padding:20px;'>❌ Erreur : Connexion à la base de données impossible.</div>");
}

$resultat_recherche = "";

if (isset($_POST['rechercher_habitant'])) {

    // Récupération sécurisée des champs de recherche
    $nom_saisi    = trim($_POST['nom_saisi'] ?? '');
    $prenom_saisi = trim($_POST['prenom_saisi'] ?? '');
    $date_saisi   = trim($_POST['date_naiss'] ?? '');

    if (empty($nom_saisi) && empty($prenom_saisi)) {
        $resultat_recherche = "<div style='background:#fff3cd; color:#856404; padding:15px; border-radius:8px; border:1px solid #ffc107; margin-bottom:20px; text-align:center;'>
                                ⚠️ Veuillez entrer au moins un <strong>nom</strong> ou un <strong>prénom</strong>.
                              </div>";
    } else {
        // Requête préparée avec recherche sur nom, prénom et date
        $sql = "SELECT * FROM naissance WHERE 1=1";
        $types  = "";
        $params = [];

        if (!empty($nom_saisi)) {
            $sql    .= " AND nom LIKE ?";
            $types  .= "s";
            $params[] = "%" . $nom_saisi . "%";
        }
        if (!empty($prenom_saisi)) {
            $sql    .= " AND prenom LIKE ?";
            $types  .= "s";
            $params[] = "%" . $prenom_saisi . "%";
        }
        if (!empty($date_saisi)) {
            $sql    .= " AND date_naiss = ?";
            $types  .= "s";
            $params[] = $date_saisi;
        }

        $sql .= " ORDER BY n_reg DESC LIMIT 10";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $resultat_recherche = "";
                while ($row = mysqli_fetch_assoc($result)) {
                    $n_reg    = htmlspecialchars($row['n_reg']);
                    $p_enfant = htmlspecialchars($row['prenom']);
                    $n_enfant = htmlspecialchars($row['nom']);
                    $d_naiss  = date('d/m/Y', strtotime($row['date_naiss']));
                    $h_naiss  = htmlspecialchars($row['heure_naiss']);
                    $l_naiss  = htmlspecialchars($row['lieu_naiss']);
                    $p_pere   = htmlspecialchars($row['nom_pere']);
                    $p_mere   = htmlspecialchars($row['nom_mere']);
                    $email    = htmlspecialchars($row['email']);
                    $whatsapp = htmlspecialchars($row['whatsapp']);

                    $lien = "retraits.php?n_reg=" . urlencode($n_reg)
                          . "&p=" . urlencode($p_enfant)
                          . "&n=" . urlencode($n_enfant)
                          . "&e=" . urlencode($email)
                          . "&w=" . urlencode($whatsapp);

                    $resultat_recherche .= "
                    <div style='background:#eafaf1; border:2px solid #27ae60; padding:20px; border-radius:8px; margin-bottom:20px; border-left:10px solid #27ae60;'>
                        <h3 style='color:#27ae60; margin-top:0;'>✅ Acte trouvé : n° $n_reg</h3>
                        <p><strong>Identité :</strong> $p_enfant $n_enfant (Né le $d_naiss à $h_naiss)</p>
                        <p><strong>Lieu :</strong> $l_naiss</p>
                        <p><strong>Parents :</strong> $p_pere &amp; $p_mere</p>
                        <hr style='border:0; border-top:1px solid #ccc;'>
                        <a href='$lien'
                           style='display:inline-block; background:#27ae60; color:white; padding:12px 25px; text-decoration:none; border-radius:5px; font-weight:bold; margin-top:10px;'>
                           📄 ACCÉDER AU RETRAIT (PDF / WHATSAPP)
                        </a>
                    </div>";
                }
            } else {
                $nom_aff = htmlspecialchars($nom_saisi . ' ' . $prenom_saisi);
                $resultat_recherche = "<div style='background:#fdeaea; color:#eb5757; padding:15px; border-radius:8px; border:1px solid #eb5757; margin-bottom:20px; text-align:center;'>
                                        ❌ Aucun acte trouvé pour : <strong>$nom_aff</strong>
                                       </div>";
            }

            mysqli_stmt_close($stmt);
        } else {
            $resultat_recherche = "<div style='background:#f8d7da; color:#721c24; padding:15px; border-radius:8px; margin-bottom:20px;'>
                                    ❌ Erreur de requête : " . mysqli_error($conn) . "
                                   </div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mairie de SACRÉ-CŒUR - Demande</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; padding: 20px; }
        .container { background: white; max-width: 800px; margin: auto; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px; margin-bottom: 25px; text-transform: uppercase; font-size: 22px; }
        .section-title { background: #3498db; color: white; padding: 10px 15px; margin-top: 25px; margin-bottom: 15px; border-radius: 4px; font-size: 15px; font-weight: bold; }
        .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; font-size: 14px; color: #34495e; }
        input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-size: 14px; }
        input:focus { border-color: #3498db; outline: none; box-shadow: 0 0 5px rgba(52,152,219,0.3); }
        .btn-submit { background-color: #3498db; color: white; border: none; padding: 15px; border-radius: 5px; cursor: pointer; font-size: 17px; font-weight: bold; margin-top: 25px; width: 100%; transition: 0.3s; }
        .btn-submit:hover { background-color: #2980b9; }
        .back-link { display: block; text-align: center; margin-top: 20px; text-decoration: none; color: #7f8c8d; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Demande d'Extrait de Naissance</h1>

    <?php echo $resultat_recherche; ?>

    <form method="POST">
        <div class="section-title">🔍 Informations de la personne concernée</div>
        <div class="grid">
            <div>
                <label>Nom :</label>
                <input type="text" name="nom_saisi"
                       placeholder="Nom de famille"
                       value="<?php echo htmlspecialchars($_POST['nom_saisi'] ?? ''); ?>"
                       required>
            </div>
            <div>
                <label>Prénom(s) :</label>
                <input type="text" name="prenom_saisi"
                       placeholder="Prénom(s)"
                       value="<?php echo htmlspecialchars($_POST['prenom_saisi'] ?? ''); ?>">
            </div>
            <div>
                <label>Date de naissance :</label>
                <input type="date" name="date_naiss"
                       value="<?php echo htmlspecialchars($_POST['date_naiss'] ?? ''); ?>">
            </div>
            <div>
                <label>Lieu de naissance :</label>
                <input type="text" name="lieu_naiss" placeholder="Ville / Commune">
            </div>
        </div>

        <div class="section-title">👨‍👩‍👧 Filiation (Parents)</div>
        <div class="grid">
            <div>
                <label>Nom complet du père :</label>
                <input type="text" name="nom_pere" placeholder="Prénom et Nom du père">
            </div>
            <div>
                <label>Nom complet de la mère :</label>
                <input type="text" name="nom_mere" placeholder="Prénom et Nom de la mère">
            </div>
        </div>

        <div class="section-title">📲 Coordonnées de réception (Digital)</div>
        <div class="grid">
            <div>
                <label>Email :</label>
                <input type="email" name="email_demandeur" placeholder="exemple@mail.com">
            </div>
            <div>
                <label>Numéro WhatsApp :</label>
                <input type="text" name="whatsapp_demandeur" placeholder="221XXXXXXXXX">
            </div>
        </div>

        <button type="submit" name="rechercher_habitant" class="btn-submit">🔍 RECHERCHER L'ACTE</button>
    </form>

    <a href="index.php" class="back-link">⬅ Retour au menu principal</a>
</div>

</body>
</html>