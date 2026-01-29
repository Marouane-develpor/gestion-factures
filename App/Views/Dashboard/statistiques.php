<?php
require __DIR__ . '/../layout/header.php';

// Initialiser les variables si elles sont null
$factures = $factures ?? [];
$clients = $clients ?? [];
$caGlobal = $caGlobal ?? 0;

// Calculer les totaux
$totalFactures = is_array($factures) ? count($factures) : 0;
$totalClients = is_array($clients) ? count($clients) : 0;
$caTotal = 0;
$facturesReglees = 0;

foreach ($factures as $facture) {
    if (isset($facture['statut']) && $facture['statut'] === 'réglée') {
        $caTotal += $facture['montant_total'] ?? 0;
        $facturesReglees++;
    }
}
?>
<?php include '../app/Views/layout/menu.php'; ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-dark">Statistiques Générales</h1>
    </div>

    <!-- Stats Cards Row -->
    <div class="row">
        <!-- Total Clients Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $totalClients; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Factures Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Factures
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $totalFactures; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Factures Réglées Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Factures Réglées
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $facturesReglees; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chiffre d'Affaires Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chiffre d'Affaires
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo number_format($caTotal, 2, ',', ' '); ?> DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Liste des Factures -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Liste des Factures</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($factures)): ?>
                        <div class="alert alert-info">
                            Aucune facture trouvée.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>N° Facture</th>
                                        <th>Client</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($factures as $facture): ?>
                                    <tr>
                                        <td>
                                            <a href="index.php?action=detail_facture&id=<?php echo $facture['idFacture'] ?? ''; ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($facture['numero'] ?? ''); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars(($facture['nom'] ?? '') . ' ' . ($facture['prenom'] ?? '')); ?></td>
                                        <td><?php echo isset($facture['date_facture']) ? date('d/m/Y', strtotime($facture['date_facture'])) : ''; ?></td>
                                        <td><?php echo number_format($facture['montant_total'] ?? 0, 2, ',', ' '); ?> DH</td>
                                        <td>
                                            <span class="badge bg-<?php echo ($facture['statut'] ?? '') == 'réglée' ? 'success' : 'warning'; ?>">
                                                <?php echo htmlspecialchars($facture['statut'] ?? ''); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Clients avec CA -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Clients avec Chiffre d'Affaires</h6>
                </div>
                <div class="card-body">
                    <?php if (empty($clients)): ?>
                        <div class="alert alert-info">
                            Aucun client trouvé.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Client</th>
                                        <th class="text-center">Factures</th>
                                        <th class="text-end">Chiffre d'Affaires</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($clients as $client): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?php echo $client['nb_factures'] ?? 0; ?></span>
                                        </td>
                                        <td class="text-end">
                                            <strong><?php echo number_format($client['chiffre_affaires'] ?? 0, 2, ',', ' '); ?> DH</strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>TOTAL GLOBAL</strong></td>
                                        <td class="text-end">
                                            <strong class="text-primary">
                                                <?php echo number_format($caGlobal, 2, ',', ' '); ?> DH
                                            </strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Summary Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Résumé Statistique</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="card border-primary h-100">
                                <div class="card-body text-center">
                                    <div class="text-primary mb-2">
                                        <i class="fas fa-calculator fa-3x"></i>
                                    </div>
                                    <h5 class="card-title text-primary">Moyenne par Facture</h5>
                                    <h3 class="card-text">
                                        <?php 
                                        $moyenne = $totalFactures > 0 ? $caTotal / $totalFactures : 0;
                                        echo number_format($moyenne, 2, ',', ' ') . ' DH';
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-success h-100">
                                <div class="card-body text-center">
                                    <div class="text-success mb-2">
                                        <i class="fas fa-percent fa-3x"></i>
                                    </div>
                                    <h5 class="card-title text-success">Taux de Règlement</h5>
                                    <h3 class="card-text">
                                        <?php 
                                        $taux = $totalFactures > 0 ? ($facturesReglees / $totalFactures) * 100 : 0;
                                        echo number_format($taux, 1) . '%';
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="card border-info h-100">
                                <div class="card-body text-center">
                                    <div class="text-info mb-2">
                                        <i class="fas fa-user fa-3x"></i>
                                    </div>
                                    <h5 class="card-title text-info">Moyenne par Client</h5>
                                    <h3 class="card-text">
                                        <?php 
                                        $moyenneClient = $totalClients > 0 ? $caTotal / $totalClients : 0;
                                        echo number_format($moyenneClient, 2, ',', ' ') . ' DH';
                                        ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>



<?php
require __DIR__ . '/../layout/footer.php';
?>