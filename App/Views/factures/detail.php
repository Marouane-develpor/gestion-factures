<?php
require __DIR__ . '/../layout/header.php';

if (!isset($facture) || empty($facture)) {
    echo '<div class="alert alert-danger">Facture non trouvée</div>';
    echo '<a href="index.php?action=liste_factures" class="btn btn-secondary">Retour</a>';
    require __DIR__ . '/../layout/footer.php';
    exit;
}
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-receipt"></i> Détail de la Facture</h2>
        <div>
            <a href="index.php?action=liste_factures" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <?php if ($facture['statut'] == 'non réglée'): ?>
                <a href="index.php?action=regler_facture&id=<?php echo $facture['idFacture']; ?>" 
                   class="btn btn-success"
                   onclick="return confirm('Marquer cette facture comme réglée?')">
                    <i class="bi bi-check-circle"></i> Marquer comme Réglée
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Facture <?php echo htmlspecialchars($facture['numero']); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informations Client</h6>
                            <p>
                                <strong>Client:</strong><br>
                                <?php echo htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']); ?><br>
                                <?php if (!empty($facture['email'])): ?>
                                    <i class="bi bi-envelope"></i> <?php echo htmlspecialchars($facture['email']); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($facture['telephone'])): ?>
                                    <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($facture['telephone']); ?><br>
                                <?php endif; ?>
                                <?php if (!empty($facture['ville'])): ?>
                                    <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($facture['ville']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informations Facture</h6>
                            <p>
                                <strong>Date:</strong> <?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?><br>
                                <strong>Statut:</strong> 
                                <span class="badge bg-<?php echo $facture['statut'] == 'réglée' ? 'success' : 'warning'; ?>">
                                    <?php echo $facture['statut']; ?>
                                </span><br>
                                <strong>Montant Total:</strong> 
                                <span class="fs-5 text-primary">
                                    <?php echo number_format($facture['montant_total'], 2, ',', ' '); ?> DH
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Articles</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($lignes)): ?>
                        <p class="text-muted">Aucun article dans cette facture</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Article</th>
                                        <th>Référence</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Montant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    foreach ($lignes as $ligne): 
                                        $total += $ligne['montant'];
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ligne['article_nom'] ?? ''); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($ligne['reference'] ?? ''); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $ligne['quantite']; ?></td>
                                        <td><?php echo number_format($ligne['prix_unitaire'] ?? $ligne['prix'], 2, ',', ' '); ?> DH</td>
                                        <td>
                                            <strong><?php echo number_format($ligne['montant'], 2, ',', ' '); ?> DH</strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr class="table-primary">
                                        <td colspan="4" class="text-end"><strong>TOTAL</strong></td>
                                        <td><strong><?php echo number_format($total, 2, ',', ' '); ?> DH</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="index.php?action=pdf_facture&id=<?php echo $facture['idFacture']; ?>" class="btn btn-danger" target="_blank">
                            <i class="fas fa-file-pdf"></i> Télécharger PDF
                        </a>
                        <a href="index.php?action=modifier_facture&id=<?php echo $facture['idFacture']; ?>" 
                           class="btn btn-outline-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <?php if ($facture['statut'] == 'non réglée'): ?>
                            <a href="index.php?action=regler_facture&id=<?php echo $facture['idFacture']; ?>" 
                               class="btn btn-success"
                               onclick="return confirm('Marquer comme réglée?')">
                                <i class="bi bi-check-circle"></i> Marquer comme Réglée
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Résumé</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>N° Facture:</strong><br>
                        <?php echo htmlspecialchars($facture['numero']); ?>
                    </p>
                    <p>
                        <strong>Date d'émission:</strong><br>
                        <?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?>
                    </p>
                    <p>
                        <strong>Dernière mise à jour:</strong><br>
                        <?php echo date('d/m/Y H:i', strtotime($facture['created_at'] ?? 'now')); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/../layout/footer.php';
?>