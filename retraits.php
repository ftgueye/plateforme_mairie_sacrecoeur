<?php
include('table.php'); // Connexion à la base de données (etat_civil)

$search = "";
if (isset($_POST['recherche'])) {
   // Remplacez la ligne 6 par celle-ci :
$search = isset($_POST['recherche']) ? mysqli_real_escape_string($conn, $_POST['recherche']) : "";
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
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f8f9fa; }
        .btn-pdf { background: #e74c3c; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; }
        .btn-wa { background: #25D366; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-size: 13px; margin-left: 5px; }
        .btn-pdf:hover { background: #c0392b; }
    </style>
</head>
<body>

<div class="container">
    <h2>Délivrance des Extraits de Naissance</h2>

    <div class="search-box">
        <form method="POST">
            <input type="text" name="recherche" placeholder="Entrez le n° de registre ou le nom..." value="<?php echo $search; ?>">
            <button type="submit">Rechercher</button>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>N° Registre</th>
                <th>Prénom & Nom</th>
                <th>Date Naiss.</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Requête SQL de recherche
            $sql = "SELECT * FROM naissance WHERE n_reg LIKE '%$search%' OR nom LIKE '%$search%' ORDER BY n_reg DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td><strong>" . $row['n_reg'] . "</strong></td>";
                    echo "<td>" . $row['prenom'] . " " . $row['nom'] . "</td>";
                    echo "<td>" . date('d/m/Y', strtotime($row['date_naiss'])) . "</td>";
                    echo "<td>";
                        // LE BOUTON GÉNÉRER PDF (Lien vers generer_pdf.php)
                        echo "<a href='generer_pdf.php?id=" . $row['n_reg'] . "' target='_blank' class='btn-pdf'>📄 PDF</a>";
                        
                        // LE BOUTON WHATSAPP
                        $msg = "Bonjour, l'extrait de " . $row['prenom'] . " est prêt.";
                        echo "<a href='https://wa.me/" . $row['whatsapp'] . "?text=" . urlencode($msg) . "' target='_blank' class='btn-wa'>💬 WhatsApp</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Aucun résultat trouvé.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <p style="text-align: center; margin-top: 20px;"><a href="declaration.php">← Retour à l'enregistrement</a></p>
</div>

</body>
</html>