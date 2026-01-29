<?php

$headerPath = __DIR__ . '/../layout/header.php';
if (file_exists($headerPath)) {
    require $headerPath;
}
?>
<?php include '../app/Views/layout/menu.php'; ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Liste des Factures</h2>
        <div>
            <a href="index.php?action=ajouter_facture" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Nouvelle Facture
            </a>
        </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php
            $messages = [
                1 => 'Facture ajoutée avec succès',
                2 => 'Facture marquée comme réglée'
            ];
            echo $messages[$_GET['success']] ?? 'Opération réussie';
            ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <?php if (empty($factures)): ?>
                <div class="alert alert-info">
                    Aucune facture trouvée. <a href="index.php?action=ajouter_facture">Créer la première facture</a>
                </div>
            <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Montant Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $facture): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($facture['numero']); ?></strong>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?></td>
                            <td>
                                <?php echo htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']); ?>
                                <br>
                                <small class="text-muted"><?php echo htmlspecialchars($facture['ville'] ?? ''); ?></small>
                            </td>
                            <td>
                                <strong><?php echo number_format($facture['montant_total'], 2, ',', ' '); ?> DH</strong>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $facture['statut'] == 'réglée' ? 'success' : 'warning'; ?>">
                                    <?php echo htmlspecialchars($facture['statut']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="index.php?action=detail_facture&id=<?php echo $facture['idFacture']; ?>" 
                                       class="btn btn-info" title="Voir détail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($facture['statut'] == 'non réglée'): ?>
                                    <a href="index.php?action=regler_facture&id=<?php echo $facture['idFacture']; ?>" 
                                       class="btn btn-success" 
                                       onclick="return confirm('Marquer cette facture comme réglée?')"
                                       title="Marquer comme réglée">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="index.php?action=pdf_facture&id=<?php echo $facture['idFacture']; ?>" 
                                    class="btn btn-danger" target="_blank">
                                        <i class="bi bi-file-pdf" style=" color: white;"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$footerPath = __DIR__ . '/../layout/footer.php';
if (file_exists($footerPath)) {
    require $footerPath;
}
?>