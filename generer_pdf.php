<?php
// 1. SÉCURITÉ ET CONNEXION
ob_start();
error_reporting(E_ALL & ~E_DEPRECATED);

require('fpdf.php');
include('table.php');

// Vérification de la connexion
if (!$conn) {
    die("❌ Erreur : Connexion à la base de données impossible.");
}

// Récupération sécurisée de l'ID depuis l'URL
$id = isset($_GET['id']) ? trim($_GET['id']) : '';

if (empty($id)) {
    die("❌ Erreur : Aucun numéro de registre fourni.");
}

// Requête préparée — protection contre les injections SQL
$stmt = mysqli_prepare($conn, "SELECT * FROM naissance WHERE n_reg = ?");
if (!$stmt) {
    die("❌ Erreur de requête : " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data   = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    die("❌ Erreur : Acte n° " . htmlspecialchars($id) . " introuvable dans la base de données.");
}

// Formatage de la date : YYYY-MM-DD → DD/MM/YYYY
$date_formatee  = date('d/m/Y', strtotime($data['date_naiss']));
$heure_formatee = substr($data['heure_naiss'], 0, 5); // HH:MM seulement

// Numéro de registre en séquence lisible
$num_seq = $data['n_reg'];

// Fonction pour gérer les accents (FPDF utilise ISO-8859-1)
function fix($text) {
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}


// 2. CRÉATION DU PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// --- CADRE EXTÉRIEUR ---
$pdf->SetLineWidth(0.8);
$pdf->Rect(10, 10, 190, 277);
$pdf->SetLineWidth(0.3);
$pdf->Rect(12, 12, 186, 273); // double cadre intérieur

// --- EN-TÊTE ---
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetY(18);
$pdf->Cell(0, 6, fix("RÉPUBLIQUE DU SÉNÉGAL"), 0, 1, 'C');
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 4, fix("Un Peuple - Un But - Une Foi"), 0, 1, 'C');

$pdf->Ln(3);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(0, 5, fix("COMMUNE DE SACRÉ-CŒUR"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, fix("Service de l'État Civil"), 0, 1, 'C');

// Ligne de séparation
$pdf->Ln(4);
$pdf->SetLineWidth(0.5);
$pdf->Line(20, $pdf->GetY(), 190, $pdf->GetY());

// --- TITRE ---
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, fix("EXTRAIT D'ACTE DE NAISSANCE"), 0, 1, 'C');
$pdf->SetLineWidth(0.8);
$pdf->Line(40, $pdf->GetY(), 170, $pdf->GetY());
$pdf->Ln(10);

// --- NUMÉRO DE REGISTRE ---
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, fix("Numéro de Registre : " . $num_seq), 0, 1, 'R');
$pdf->Ln(4);

// --- CORPS DU DOCUMENT ---
$h = 9;
$col1 = 65; // largeur colonne étiquette

// Identité enfant
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(236, 240, 241);
$pdf->Cell(0, 7, fix("  IDENTITÉ DE L'ENFANT"), 0, 1, 'L', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell($col1, $h, fix("Prénom(s) :"), 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, $h, strtoupper(fix($data['prenom'])), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell($col1, $h, fix("Nom de famille :"), 0, 0);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, $h, strtoupper(fix($data['nom'])), 0, 1);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell($col1, $h, fix("Date de naissance :"), 0, 0);
$pdf->Cell(0, $h, fix($date_formatee), 0, 1);

$pdf->Cell($col1, $h, fix("Heure de naissance :"), 0, 0);
$pdf->Cell(0, $h, fix($heure_formatee), 0, 1);

$pdf->Cell($col1, $h, fix("Lieu de naissance :"), 0, 0);
$pdf->Cell(0, $h, fix($data['lieu_naiss']), 0, 1);

// Filiation
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(236, 240, 241);
$pdf->Cell(0, 7, fix("  FILIATION"), 0, 1, 'L', true);
$pdf->Ln(2);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell($col1, $h, fix("Nom complet du père :"), 0, 0);
$pdf->Cell(0, $h, fix($data['nom_pere']), 0, 1);

$pdf->Cell($col1, $h, fix("Nom complet de la mère :"), 0, 0);
$pdf->Cell(0, $h, fix($data['nom_mere']), 0, 1);

// Mention légale
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->SetTextColor(100, 100, 100);
$pdf->MultiCell(0, 5, fix(
    "Le présent extrait est délivré sur demande de l'intéressé(e) pour servir et valoir ce que de droit.\n" .
    "Tout faux ou usage de faux est passible de poursuites judiciaires."
), 0, 'C');
$pdf->SetTextColor(0, 0, 0);

// --- SIGNATURE ---
$pdf->Ln(12);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(100, 5, '', 0, 0); // espace gauche
$pdf->Cell(0, 5, fix("Fait à Sacré-Cœur, le " . date('d/m/Y')), 0, 1, 'C');
$pdf->Ln(3);
$pdf->Cell(100, 5, '', 0, 0);
$pdf->SetFont('Arial', 'BU', 10);
$pdf->Cell(0, 5, fix("L'Officier de l'État Civil"), 0, 1, 'C');

// --- CACHET CIRCULAIRE ---
$pdf->SetLineWidth(0.4);
$pdf->SetFont('Arial', 'I', 7);
$pdf->SetTextColor(80, 80, 80);
$pdf->Text(fix(147), 247, fix("MAIRIE DE"));
$pdf->Text(fix(146), 251, fix("SACRÉ-CŒUR"));
$pdf->SetTextColor(0, 0, 0);

// --- SORTIE DU PDF ---
ob_end_clean();
$pdf->Output('I', 'Extrait_Naissance_' . $data['n_reg'] . '.pdf');
?>