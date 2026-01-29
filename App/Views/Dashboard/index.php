<?php include '../app/Views/layout/header.php'; ?>
<?php include '../app/Views/layout/menu.php'; ?>

<div class="col-md-10 main-content">
    <h2 class="mb-4">Tableau de Bord</h2>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-people"></i> Clients
                    </h5>
                    <h2 class="card-text"><?php echo $totalClients; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-file-text"></i> Factures
                    </h5>
                    <h2 class="card-text"><?php echo $totalFactures; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-currency-exchange"></i> Chiffre d'Affaires
                    </h5>
                    <h2 class="card-text"><?php echo number_format($chiffreAffaires, 2, ',', ' '); ?> DH</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-calendar-check"></i> Aujourd'hui
                    </h5>
                    <h6 class="card-text"><?php echo date('d/m/Y'); ?></h6>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row ">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Factures Récentes</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>N° Facture</th>
                                <th>Client</th>
                                <th>Date</th>
                                <th>Montant</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturesRecentes as $facture): ?>
                            <tr>
                                <td>
                                    <a href="index.php?action=detail_facture&id=<?php echo $facture['idFacture']; ?>">
                                        <?php echo htmlspecialchars($facture['numero']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></td>
                                <td><?php echo number_format($facture['montant_total'], 2, ',', ' '); ?> DH</td>
                                <td>
                                    <span class="badge bg-<?php echo $facture['statut'] == 'réglée' ? 'success' : 'warning'; ?>">
                                        <?php echo $facture['statut']; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../app/Views/layout/footer.php'; ?>