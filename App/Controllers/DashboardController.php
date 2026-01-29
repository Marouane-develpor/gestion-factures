<?php
namespace App\Controllers;

use App\Models\GestionFacture;
use App\Models\GestionClient;

class DashboardController {
    private $facture;
    private $client;

    public function __construct() {
        $this->facture = new GestionFacture();
        $this->client = new GestionClient();
    }

    public function index() {
        // Récupérer les données
        $clients = $this->client->getAll();
        $factures = $this->facture->getAll();
        
        // Assurer que ce sont des tableaux
        $clients = is_array($clients) ? $clients : [];
        $factures = is_array($factures) ? $factures : [];
        
        // Calculer les statistiques
        $totalClients = count($clients);
        $totalFactures = count($factures);
        $chiffreAffaires = $this->calculateTotalCA($factures);
        $facturesRecentes = $this->getRecentInvoices($factures, 5);
        
        // Inclure la vue avec les variables
        require __DIR__ . '/../Views/dashboard/index.php';
    }

    public function statistiques() {
        // Récupérer les données
        $factures = $this->facture->getAll();
        $clients = $this->client->getAll();
        
        // Assurer que ce sont des tableaux (éviter null)
        $factures = is_array($factures) ? $factures : [];
        $clients = is_array($clients) ? $clients : [];
        
        // Calculer CA global
        $caGlobal = $this->calculateTotalCA($factures);
        
        // Inclure la vue - les variables seront disponibles
        require __DIR__ . '/../Views/dashboard/statistiques.php';
    }

    public function clientsRecompenser() {
        // Récupérer les clients
        $clients = $this->client->getAll();
        $clients = is_array($clients) ? $clients : [];
        
        // Filtrer ceux avec CA >= 500000
        $clientsRecompenser = array_filter($clients, function($client) {
            return ($client['chiffre_affaires'] ?? 0) >= 500000;
        });
        
        // Trier par CA décroissant
        usort($clientsRecompenser, function($a, $b) {
            return ($b['chiffre_affaires'] ?? 0) <=> ($a['chiffre_affaires'] ?? 0);
        });
        
        // Passer la variable à la vue
        $clients = $clientsRecompenser; // Renommer pour la vue
        
        require __DIR__ . '/../Views/dashboard/clients_recompenser.php';
    }

    public function retardsPaiement() {
        // Récupérer toutes les factures
        $factures = $this->facture->getAll();
        $factures = is_array($factures) ? $factures : [];
        
        // Filtrer les factures non réglées
        $retards = array_filter($factures, function($facture) {
            return isset($facture['statut']) && $facture['statut'] === 'non réglée';
        });
        
        // Calculer les jours de retard
        $aujourdhui = new \DateTime();
        foreach ($retards as &$facture) {
            if (isset($facture['date_facture'])) {
                $dateFacture = new \DateTime($facture['date_facture']);
                $facture['jours_retard'] = $aujourdhui->diff($dateFacture)->days;
            } else {
                $facture['jours_retard'] = 0;
            }
        }
        
        // Renommer pour la vue
        $retards = $retards;
        
        require __DIR__ . '/../Views/dashboard/retards_paiement.php';
    }

    // Méthodes utilitaires
    private function calculateTotalCA($factures) {
        if (!is_array($factures)) {
            return 0;
        }
        
        $total = 0;
        foreach ($factures as $facture) {
            if (isset($facture['statut']) && $facture['statut'] === 'réglée' && isset($facture['montant_total'])) {
                $total += $facture['montant_total'];
            }
        }
        return $total;
    }

    private function getRecentInvoices($factures, $limit = 5) {
        if (!is_array($factures)) {
            return [];
        }
        
        // Trier par date décroissante
        usort($factures, function($a, $b) {
            $dateA = isset($a['date_facture']) ? strtotime($a['date_facture']) : 0;
            $dateB = isset($b['date_facture']) ? strtotime($b['date_facture']) : 0;
            return $dateB - $dateA;
        });
        
        return array_slice($factures, 0, $limit);
    }
}