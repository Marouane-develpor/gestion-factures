<?php
namespace App\Controllers;

use App\Models\GestionFacture;
use App\Models\GestionClient;
use App\Models\GestionArticle;

class FactureController {
    private $fact;
    private $client;
    private $article;

    public function __construct() {
        $this->fact = new GestionFacture();
        $this->client = new GestionClient();
        $this->article = new GestionArticle();
    }

    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = trim($_POST['numero'] ?? '');
            $date_facture = trim($_POST['date_facture'] ?? '');
            $idClient = trim($_POST['idClient'] ?? '');
            $statut = trim($_POST['statut'] ?? 'non réglée');
            
            // Récupérer les lignes de facture
            $articles = $_POST['articles'] ?? [];
            $quantites = $_POST['quantites'] ?? [];
            $prix = $_POST['prix'] ?? [];
            
            // Calculer le montant total
            $montant_total = 0;
            $lignes = [];
            
            for ($i = 0; $i < count($articles); $i++) {
                if (!empty($articles[$i]) && !empty($quantites[$i])) {
                    $montant_ligne = $quantites[$i] * ($prix[$i] ?? 0);
                    $montant_total += $montant_ligne;
                    
                    $lignes[] = [
                        'idArticle' => $articles[$i],
                        'quantite' => $quantites[$i],
                        'prix' => $prix[$i] ?? 0,
                        'montant' => $montant_ligne
                    ];
                }
            }
            
            // Ajouter la facture avec ses lignes
            $idFacture = $this->fact->addF($numero, $date_facture, $idClient, $statut, $montant_total);
            
            // Ajouter les lignes de facture
            if ($idFacture) {
                foreach ($lignes as $ligne) {
                    $this->fact->addLf($idFacture, $ligne['idArticle'], $ligne['quantite'], 
                                     $ligne['prix'], $ligne['montant']);
                }
            }
            
            header('Location: index.php?action=liste_factures&success=1');
            exit;
        }
        
        // Afficher le formulaire
        $clients = $this->client->getAll();
        $articles = $this->article->getAll();
        
        $viewPath = __DIR__ . '/../Views/factures/ajouter.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->showFallbackAddForm($clients, $articles);
        }
    }

    public function liste() {
        $factures = $this->fact->getAll();
        
        $viewPath = __DIR__ . '/../Views/factures/liste.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->showFallbackList($factures);
        }
    }

    public function detail($id) {
        $facture = $this->fact->getById($id);
        
        if (!$facture) {
            header('Location: index.php?action=liste_factures&error=1');
            exit;
        }
        
        $lignes = $this->fact->getLignes($id);
        
        $viewPath = __DIR__ . '/../Views/factures/detail.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->showFallbackDetail($facture, $lignes);
        }
    }

    public function modifier($id) {
        // Récupérer la facture à modifier
        $facture = $this->fact->getById($id);
        
        if (!$facture) {
            header('Location: index.php?action=liste_factures&error=2');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = trim($_POST['numero'] ?? '');
            $date_facture = trim($_POST['date_facture'] ?? '');
            $idClient = trim($_POST['idClient'] ?? '');
            $statut = trim($_POST['statut'] ?? 'non réglée');
            $montant_total = trim($_POST['montant_total'] ?? 0);
            
            // Mettre à jour la facture
            $result = $this->fact->update($id, $numero, $date_facture, $idClient, $statut, $montant_total);
            
            if ($result) {
                header('Location: index.php?action=detail_facture&id=' . $id . '&success=1');
            } else {
                header('Location: index.php?action=modifier_facture&id=' . $id . '&error=1');
            }
            exit;
        }
        
        // Afficher le formulaire de modification
        $clients = $this->client->getAll();
        $lignes = $this->fact->getLignes($id);
        
        $viewPath = __DIR__ . '/../Views/factures/modifier.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->showFallbackModifyForm($facture, $clients, $lignes);
        }
    }

    public function supprimer($id) {
        $result = $this->fact->deleteF($id);
        
        if ($result) {
            header('Location: index.php?action=liste_factures&success=3');
        } else {
            header('Location: index.php?action=liste_factures&error=3');
        }
        exit;
    }

    public function regler($id) {
        $this->fact->updateStatut($id, 'réglée');
        header('Location: index.php?action=liste_factures&success=2');
        exit;
    }

    // Méthode pour ajouter une ligne à une facture existante
    public function ajouterLigne($idFacture) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idArticle = trim($_POST['idArticle'] ?? '');
            $quantite = trim($_POST['quantite'] ?? 1);
            $prix = trim($_POST['prix'] ?? 0);
            $montant = $quantite * $prix;
            
            // Ajouter la ligne
            $this->fact->addLf($idFacture, $idArticle, $quantite, $prix, $montant);
            
            // Mettre à jour le montant total
            $facture = $this->fact->getById($idFacture);
            $lignes = $this->fact->getLignes($idFacture);
            
            $nouveauTotal = 0;
            foreach ($lignes as $ligne) {
                $nouveauTotal += $ligne['montant'];
            }
            
            $this->fact->updateMontant($nouveauTotal, $idFacture);
            
            header('Location: index.php?action=detail_facture&id=' . $idFacture . '&success=2');
            exit;
        }
        
        $facture = $this->fact->getById($idFacture);
        $articles = $this->article->getAll();
        
        // Afficher le formulaire pour ajouter une ligne
        echo '<h2>Ajouter une ligne à la facture ' . htmlspecialchars($facture['numero']) . '</h2>';
        echo '<form method="POST">';
        echo '<select name="idArticle">';
        foreach ($articles as $article) {
            echo '<option value="' . $article['idArticle'] . '">' 
                 . htmlspecialchars($article['nom'] . ' (' . $article['prix'] . ' DH)') 
                 . '</option>';
        }
        echo '</select>';
        echo '<input type="number" name="quantite" value="1" min="1">';
        echo '<input type="number" name="prix" step="0.01">';
        echo '<button type="submit">Ajouter</button>';
        echo '</form>';
    }

    // Méthode pour supprimer une ligne d'une facture
    public function supprimerLigne($idLigne) {
        // Récupérer l'ID de la facture avant suppression
        // (Tu devrais ajouter une méthode dans GestionFacture pour ça)
        $result = $this->fact->deleteLf($idLigne);
        
        if ($result) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&success=4');
        } else {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&error=4');
        }
        exit;
    }

    // Méthodes fallback
    private function showFallbackAddForm($clients, $articles) {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Nouvelle Facture</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <h2>Nouvelle Facture</h2>
                <a href="index.php?action=liste_factures" class="btn btn-secondary mb-3">Retour</a>
                
                <form method="POST" action="index.php?action=ajouter_facture">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Numéro Facture</label>
                            <input type="text" name="numero" class="form-control" 
                                   value="FACT-' . date('Ymd-His') . '" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Facture</label>
                            <input type="date" name="date_facture" class="form-control" 
                                   value="' . date('Y-m-d') . '" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Client</label>
                            <select name="idClient" class="form-select" required>
                                <option value="">Sélectionner un client</option>';
        
        foreach ($clients as $client) {
            echo '<option value="' . $client['idClient'] . '">'
                 . htmlspecialchars($client['nom'] . ' ' . $client['prenom'])
                 . '</option>';
        }
        
        echo '</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="non réglée">Non Réglée</option>
                                <option value="réglée">Réglée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Montant Total</label>
                        <input type="number" name="montant_total" class="form-control" 
                               step="0.01" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </form>
            </div>
        </body>
        </html>';
    }

    private function showFallbackModifyForm($facture, $clients, $lignes) {
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Modifier Facture</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
            <div class="container mt-4">
                <h2>Modifier Facture ' . htmlspecialchars($facture['numero']) . '</h2>
                <a href="index.php?action=detail_facture&id=' . $facture['idFacture'] . '" class="btn btn-secondary mb-3">Retour</a>
                
                <form method="POST" action="index.php?action=modifier_facture&id=' . $facture['idFacture'] . '">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Numéro Facture</label>
                            <input type="text" name="numero" class="form-control" 
                                   value="' . htmlspecialchars($facture['numero']) . '" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Date Facture</label>
                            <input type="date" name="date_facture" class="form-control" 
                                   value="' . $facture['date_facture'] . '" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Client</label>
                            <select name="idClient" class="form-select" required>';
        
        foreach ($clients as $client) {
            $selected = $client['idClient'] == $facture['idClient'] ? 'selected' : '';
            echo '<option value="' . $client['idClient'] . '" ' . $selected . '>'
                 . htmlspecialchars($client['nom'] . ' ' . $client['prenom'])
                 . '</option>';
        }
        
        echo '</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Statut</label>
                            <select name="statut" class="form-select">
                                <option value="non réglée" ' . ($facture['statut'] == 'non réglée' ? 'selected' : '') . '>Non Réglée</option>
                                <option value="réglée" ' . ($facture['statut'] == 'réglée' ? 'selected' : '') . '>Réglée</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Montant Total</label>
                        <input type="number" name="montant_total" class="form-control" 
                               value="' . $facture['montant_total'] . '" step="0.01" required>
                    </div>
                    
                    <button type="submit" class="btn btn-warning">Mettre à jour</button>
                </form>
                
                <h4 class="mt-4">Lignes de facture</h4>';
        
        if (!empty($lignes)) {
            echo '<ul>';
            foreach ($lignes as $ligne) {
                echo '<li>' . htmlspecialchars($ligne['article_nom'] ?? '') . ' - ' 
                     . $ligne['quantite'] . ' x ' . $ligne['prix'] . ' DH = ' 
                     . $ligne['montant'] . ' DH</li>';
            }
            echo '</ul>';
        }
        
        echo '</div>
        </body>
        </html>';
    }
}