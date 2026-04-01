<?php
// 1. SECURITE ET CONNEXION
ob_start(); 
error_reporting(E_ALL & ~E_DEPRECATED); 

require('fpdf.php');
include('table.php');

// Récupération de l'ID depuis l'URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

$query = "SELECT * FROM naissance WHERE n_reg = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Erreur : Acte introuvable dans la base de données.");
}

// Fonction pour gérer les accents
function fix($text) {
    return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
}

// 2. CREATION DU PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();
$pdf->SetMargins(20, 20, 20);

// CADRE EXTERIEUR
$pdf->SetLineWidth(0.5);
$pdf->Rect(10, 10, 190, 277); 

// ENTÊTE
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, fix("RÉPUBLIQUE DU SÉNÉGAL"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 4, fix("Un Peuple - Un But - Une Foi"), 0, 1, 'C');
$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, fix("COMMUNE DE SACRÉ-CŒUR"), 0, 1, 'L');
$pdf->Ln(15);

// TITRE
$pdf->SetFont('Arial', 'B', 18);
$pdf->Cell(0, 10, fix("EXTRAIT D'ACTE DE NAISSANCE"), 0, 1, 'C');
$pdf->SetLineWidth(0.8);
$pdf->Line(40, 60, 170, 60); 
$pdf->Ln(20);

// CORPS DU DOCUMENT
$pdf->SetFont('Arial', '', 12);
$h = 10;

$pdf->Cell(55, $h, fix("Numéro de Registre :"), 0, 0);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, $h, fix($data['n_reg']), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(55, $h, fix("Prénom(s) :"), 0, 0);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, $h, strtoupper(fix($data['prenom'])), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(55, $h, fix("Nom :"), 0, 0);
$pdf->SetFont('Arial', 'B', 13);
$pdf->Cell(0, $h, strtoupper(fix($data['nom'])), 0, 1);

$pdf->SetFont('Arial', '', 12);
$pdf->Cell(55, $h, fix("Date de naissance :"), 0, 0);
$pdf->Cell(0, $h, fix($data['date_naiss']), 0, 1);

$pdf->Cell(55, $h, fix("Lieu de naissance :"), 0, 0);
$pdf->Cell(0, $h, fix($data['lieu_naiss']), 0, 1);

$pdf->Cell(55, $h, fix("Prénom & Nom Père :"), 0, 0);
$pdf->Cell(0, $h, fix($data['nom_pere']), 0, 1);

$pdf->Cell(55, $h, fix("Prénom & Nom Mère :"), 0, 0);
$pdf->Cell(0, $h, fix($data['nom_mere']), 0, 1);

// SIGNATURE ET CACHET
$pdf->Ln(30);
$pdf->Cell(100); 
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 5, fix("Fait à Sacré-Cœur, le " . date('d/m/Y')), 0, 1, 'C');
$pdf->Ln(5);
$pdf->Cell(100);
$pdf->SetFont('Arial', 'BU', 11);
$pdf->Cell(0, 5, fix("L'Officier de l'État Civil"), 0, 1, 'C');

// ZONE DU CACHET (Utilisation d'un RECTANGLE au lieu d'un cercle pour éviter l'erreur)
$pdf->SetLineWidth(0.2);
$pdf->Rect(145, 235, 35, 35); // Un carré pour le tampon
$pdf->SetFont('Arial', 'I', 7);
$pdf->Text(150, 250, fix("EMPLACEMENT DU"));
$pdf->Text(155, 254, fix("CACHET"));

// SORTIE
ob_end_clean();
$pdf->Output('I', 'Extrait_Naissance.pdf');
?>