<?php
// public/index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// Définir la constante de base
define('APP_ROOT', dirname(__DIR__));

// Autoloader amélioré
spl_autoload_register(function ($className) {
    // Convertir le namespace en chemin
    $file = APP_ROOT . '/' . str_replace('\\', '/', $className) . '.php';
    
    // Debug: afficher le chemin cherché
    if (isset($_GET['debug'])) {
        echo "Trying to load: $className<br>";
        echo "Looking for file: $file<br>";
    }
    
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    
    return false;
});

// Inclure la configuration
require_once APP_ROOT . '/app/config/Database.php';

// Router simple avec débogage
$action = $_GET['action'] ?? 'dashboard';

// Afficher l'action pour déboguer
if (isset($_GET['debug'])) {
    echo "Action demandée: $action<br>";
}

switch ($action) {
    case 'dashboard':
        $controller = new App\Controllers\DashboardController();
        $controller->index();
        break;
        
    case 'liste_clients':
        $controller = new App\Controllers\ClientController();
        $controller->liste();
        break;
        
    case 'ajouter_client':
        $controller = new App\Controllers\ClientController();
        $controller->ajouter();
        break;
        
    case 'modifier_client':
        $controller = new App\Controllers\ClientController();
        $controller->modifier($_GET['id'] ?? 0);
        break;
        
    case 'supprimer_client':
        $controller = new App\Controllers\ClientController();
        $controller->supprimer($_GET['id'] ?? 0);
        break;
        
    case 'liste_factures':
        $controller = new App\Controllers\FactureController();
        $controller->liste();
        break;
        
    case 'ajouter_facture':
        $controller = new App\Controllers\FactureController();
        $controller->ajouter();
        break;
        
    case 'detail_facture':
        $controller = new App\Controllers\FactureController();
        $controller->detail($_GET['id'] ?? 0);
        break;

  
    case 'modifier_facture':
        $controller = new App\Controllers\FactureController();
        $controller->modifier($_GET['id'] ?? 0);
        break;
        
    case 'supprimer_facture':
        $controller = new App\Controllers\FactureController();
        $controller->supprimer($_GET['id'] ?? 0);
        break;
        
    case 'ajouter_ligne':
        $controller = new App\Controllers\FactureController();
        $controller->ajouterLigne($_GET['id'] ?? 0);
        break;
        
    case 'supprimer_ligne':
        $controller = new App\Controllers\FactureController();
        $controller->supprimerLigne($_GET['id'] ?? 0);
        break; 
        
    case 'regler_facture':
        $controller = new App\Controllers\FactureController();
        $controller->regler($_GET['id'] ?? 0);
        break;
        
    case 'statistiques':
        $controller = new App\Controllers\DashboardController();
        $controller->statistiques();
        break;
        
    case 'clients_recompenser':
        $controller = new App\Controllers\DashboardController();
        $controller->clientsRecompenser();
        break;
        
    case 'retards_paiement':
        $controller = new App\Controllers\DashboardController();
        $controller->retardsPaiement();
        break;
    case 'pdf_facture':
        $controller = new App\Controllers\PdfController();
        $controller->genererFacture($_GET['id'] ?? 0);
        break;
        
    default:
        echo "<h1>Page non trouvée</h1>";
        echo "<p>Action '$action' n'existe pas</p>";
        echo '<a href="index.php?action=dashboard">Retour au dashboard</a>';
        break;
}