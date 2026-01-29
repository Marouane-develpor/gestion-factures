<?php
require __DIR__ . '/../layout/header.php';

if (!isset($client) || empty($client)) {
    echo '<div class="alert alert-danger">Client non trouvé</div>';
    echo '<a href="index.php?action=liste_clients" class="btn btn-secondary">Retour</a>';
    require __DIR__ . '/../layout/footer.php';
    exit;
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil"></i> Modifier Client
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?action=modifier_client&id=<?php echo $client['idClient']; ?>">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nom" class="form-label">Nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" 
                                       value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="prenom" class="form-label">Prénom *</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" 
                                       value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($client['email'] ?? ''); ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                       value="<?php echo htmlspecialchars($client['telephone'] ?? ''); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" 
                                   value="<?php echo htmlspecialchars($client['ville'] ?? ''); ?>">
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=liste_clients" class="btn btn-secondary">
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
    </div>
</div>

<?php
require __DIR__ . '/../layout/footer.php';
?>