<?php
namespace App\Controllers;

use App\Models\GestionFacture;

class PdfController {
    private $facture;

    public function __construct() {
        $this->facture = new GestionFacture();
    }

    public function genererFacture($id) {
        // Récupérer les données
        $facture = $this->facture->getById($id);
        $lignes = $this->facture->getLignes($id);
        
        if (!$facture) {
            die("Facture non trouvée !");
        }
        
        // Créer le PDF
        $this->creerPDFSimple($facture, $lignes);
    }

    private function creerPDFSimple($facture, $lignes) {
        // Inclure FPDF
        require_once __DIR__ . '/../../vendor/fpdf/fpdf.php';
        
        $pdf = new \FPDF();
        $pdf->AddPage();
        
        // Titre
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'FACTURE', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Numéro et date
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 8, 'Facture N°:', 0, 0);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 8, $facture['numero'], 0, 1);
        
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(40, 8, 'Date:', 0, 0);
        $pdf->Cell(0, 8, date('d/m/Y', strtotime($facture['date_facture'])), 0, 1);
        $pdf->Ln(10);
        
        // Client
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Client:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 8, $facture['nom'] . ' ' . $facture['prenom'], 0, 1);
        $pdf->Cell(0, 8, $facture['ville'], 0, 1);
        
        if (!empty($facture['email'])) {
            $pdf->Cell(0, 8, 'Email: ' . $facture['email'], 0, 1);
        }
        $pdf->Ln(10);
        
        // Tableau
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(20, 10, 'N°', 1, 0, 'C');
        $pdf->Cell(80, 10, 'Désignation', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Prix', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Quantité', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Montant', 1, 1, 'C');
        
        $pdf->SetFont('Arial', '', 11);
        $total = 0;
        
        foreach ($lignes as $index => $ligne) {
            $pdf->Cell(20, 10, $index + 1, 1, 0, 'C');
            $pdf->Cell(80, 10, $ligne['designation'] ?? $ligne['article_nom'], 1, 0, 'L');
            $pdf->Cell(30, 10, number_format($ligne['prix'] ?? $ligne['prix_unitaire'], 2, ',', ' ') . ' DH', 1, 0, 'R');
            $pdf->Cell(30, 10, $ligne['quantite'], 1, 0, 'C');
            
            $montant = ($ligne['prix'] ?? $ligne['prix_unitaire']) * $ligne['quantite'];
            $total += $montant;
            
            $pdf->Cell(30, 10, number_format($montant, 2, ',', ' ') . ' DH', 1, 1, 'R');
        }
        
        // Total
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(160, 10, 'TOTAL', 1, 0, 'C');
        $pdf->Cell(30, 10, number_format($total, 2, ',', ' ') . ' DH', 1, 1, 'R');
        
        $pdf->Ln(15);
        
        // Statut
        $pdf->SetFont('Arial', 'I', 11);
        $pdf->Cell(0, 8, 'Statut: ' . $facture['statut'], 0, 1);
        $pdf->Cell(0, 8, 'Merci de votre confiance !', 0, 1);
        
        // Sortie
        $filename = 'Facture_' . $facture['numero'] . '.pdf';
        $pdf->Output('I', $filename);
        exit;
    }
}