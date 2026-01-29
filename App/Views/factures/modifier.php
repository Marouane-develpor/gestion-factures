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
    <h2 class="mb-4"><i class="bi bi-pencil"></i> Modifier la Facture</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?action=modifier_facture&id=<?php echo $facture['idFacture']; ?>">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Numéro Facture *</label>
                                <input type="text" class="form-control" name="numero" 
                                       value="<?php echo htmlspecialchars($facture['numero']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date Facture *</label>
                                <input type="date" class="form-control" name="date_facture" 
                                       value="<?php echo $facture['date_facture']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Client *</label>
                                <select class="form-select" name="idClient" required>
                                    <option value="">Sélectionner un client</option>
                                    <?php foreach ($clients as $client): ?>
                                    <option value="<?php echo $client['idClient']; ?>"
                                            <?php echo $client['idClient'] == $facture['idClient'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-select" name="statut">
                                    <option value="non réglée" <?php echo $facture['statut'] == 'non réglée' ? 'selected' : ''; ?>>
                                        Non Réglée
                                    </option>
                                    <option value="réglée" <?php echo $facture['statut'] == 'réglée' ? 'selected' : ''; ?>>
                                        Réglée
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Montant Total</h5>
                                <input type="number" class="form-control text-center fs-4" 
                                       name="montant_total" value="<?php echo $facture['montant_total']; ?>" 
                                       step="0.01" required>
                                <small class="text-muted">En DH</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="index.php?action=detail_facture&id=<?php echo $facture['idFacture']; ?>" 
                       class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-check-circle"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/../layout/footer.php';
?>