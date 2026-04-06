<?php
include('table.php'); // Connexion à la base de données

// Vérification de la connexion
if (!$conn) {
    die("<div style='color:red; text-align:center; padding:20px;'>❌ Erreur : Connexion à la base de données impossible.</div>");
}

// Récupération sécurisée du terme de recherche
$search = "";
if (isset($_POST['recherche'])) {
    $search = trim($_POST['recherche']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Retrait d'Extrait - Mairie de Sacré-Cœur</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f7f6; padding: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; text-align: center; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .search-box { margin-bottom: 20px; text-align: center; }
        input[type="text"] { padding: 10px; width: 60%; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #2980b9; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; color: #2c3e50; }
        tr:hover { background-color: #f1f1f1; }
        .btn-pdf { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-wa  { background: #25D366; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-left: 5px; }
        .btn-pdf:hover { background: #c0392b; }
        .btn-wa:hover  { background: #1da851; }
        .no-result { text-align: center; color: #7f8c8d; font-style: italic; }
        .error-msg { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Délivrance des Extraits de Naissance</h2>

    <div class="search-box">
        <form method="POST">
            <input type="text" name="recherche"
                   placeholder="Entrez le n° de registre ou le nom..."
                   value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">🔍 Rechercher</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Registre</th>
                <th>Prénom &amp; Nom</th>
                <th>Date Naiss.</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Requête préparée pour éviter les injections SQL
            $sql = "SELECT * FROM naissance 
                    WHERE n_reg LIKE ? OR nom LIKE ? OR prenom LIKE ?
                    ORDER BY n_reg DESC";

            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                $param = "%" . $search . "%";
                mysqli_stmt_bind_param($stmt, "sss", $param, $param, $param);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $n_reg    = htmlspecialchars($row['n_reg']);
                        $prenom   = htmlspecialchars($row['prenom']);
                        $nom      = htmlspecialchars($row['nom']);
                        $date     = date('d/m/Y', strtotime($row['date_naiss']));
                        $whatsapp = htmlspecialchars($row['whatsapp']);
                        $msg      = urlencode("Bonjour, l'extrait de $prenom $nom est prêt. N° Registre : $n_reg");

                        echo "<tr>";
                        echo "<td><strong>$n_reg</strong></td>";
                        echo "<td>$prenom $nom</td>";
                        echo "<td>$date</td>";
                        echo "<td>
                                <a href='generer_pdf.php?id=$n_reg' target='_blank' class='btn-pdf'>📄 PDF</a>
                                <a href='https://wa.me/$whatsapp?text=$msg' target='_blank' class='btn-wa'>💬 WhatsApp</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='no-result'>Aucun résultat trouvé pour \"" . htmlspecialchars($search) . "\".</td></tr>";
                }

                mysqli_stmt_close($stmt);
            } else {
                echo "<tr><td colspan='4' class='error-msg'>❌ Erreur de requête : " . mysqli_error($conn) . "</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <p style="text-align:center; margin-top:20px;">
        <a href="declaration.php" style="color:#7f8c8d; text-decoration:none;">⬅ Retour à l'enregistrement</a>
    </p>
</div>

</body>
</html>