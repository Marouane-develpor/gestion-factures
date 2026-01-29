<?php include '../app/Views/layout/header.php'; ?>
<?php include '../app/Views/layout/menu.php'; ?>

<div class="col-md-10 main-content">
    <h2 class="mb-4">Nouvelle Facture</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?action=ajouter_facture" id="factureForm">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="numero" class="form-label">Numéro Facture</label>
                        <input type="text" class="form-control" id="numero" name="numero" 
                               value="FACT-<?php echo date('Ymd-His'); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="date_facture" class="form-label">Date Facture</label>
                        <input type="date" class="form-control" id="date_facture" name="date_facture" 
                               value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="idClient" class="form-label">Client</label>
                        <select class="form-select" id="idClient" name="idClient" required>
                            <option value="">Sélectionner un client</option>
                            <?php 
                            $clientModel = new App\Models\GestionClient();
                            $clients = $clientModel->getAll();
                            foreach ($clients as $client): 
                            ?>
                            <option value="<?php echo $client['idClient']; ?>">
                                <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom'] . ' - ' . $client['ville']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="statut" class="form-label">Statut</label>
                        <select class="form-select" id="statut" name="statut">
                            <option value="non réglée">Non Réglée</option>
                            <option value="réglée">Réglée</option>
                        </select>
                    </div>
                </div>
                
                <h5 class="mt-4 mb-3">Articles de la Facture</h5>
                
                <div id="lignes-container">
                    <div class="row ligne-facture mb-3">
                        <div class="col-md-4">
                            <select class="form-select article-select" name="articles[]" required>
                                <option value="">Sélectionner article</option>
                                <?php 
                                $articleModel = new App\Models\GestionArticle();
                                $articles = $articleModel->getAll();
                                foreach ($articles as $article): 
                                ?>
                                <option value="<?php echo $article['idArticle']; ?>" 
                                        data-prix="<?php echo $article['prix']; ?>">
                                    <?php echo htmlspecialchars($article['reference'] . ' - ' . $article['nom'] . ' (' . $article['prix'] . ' DH)'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control quantite" name="quantites[]" 
                                   placeholder="Quantité" min="1" value="1" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control prix" name="prix[]" 
                                   placeholder="Prix unitaire" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control montant" readonly 
                                   placeholder="Montant">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm supprimer-ligne">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="button" id="ajouter-ligne" class="btn btn-secondary mb-3">
                    <i class="bi bi-plus-circle"></i> Ajouter une ligne
                </button>
                
                <div class="row mt-4">
                    <div class="col-md-8"></div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5>Total Facture</h5>
                                <h3 id="total-facture">0.00 DH</h3>
                                <input type="hidden" name="montant_total" id="montant-total" value="0">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Enregistrer la Facture
                    </button>
                    <a href="index.php?action=liste_factures" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calculer le montant d'une ligne
    function calculerMontantLigne(ligne) {
        const quantite = parseFloat(ligne.querySelector('.quantite').value) || 0;
        const prix = parseFloat(ligne.querySelector('.prix').value) || 0;
        const montant = quantite * prix;
        ligne.querySelector('.montant').value = montant.toFixed(2);
        calculerTotal();
    }
    
    // Calculer le total de la facture
    function calculerTotal() {
        let total = 0;
        document.querySelectorAll('.ligne-facture').forEach(ligne => {
            const montant = parseFloat(ligne.querySelector('.montant').value) || 0;
            total += montant;
        });
        document.getElementById('total-facture').textContent = total.toFixed(2) + ' DH';
        document.getElementById('montant-total').value = total.toFixed(2);
    }
    
    // Ajouter une nouvelle ligne
    document.getElementById('ajouter-ligne').addEventListener('click', function() {
        const container = document.getElementById('lignes-container');
        const nouvelleLigne = document.querySelector('.ligne-facture').cloneNode(true);
        nouvelleLigne.querySelector('.article-select').value = '';
        nouvelleLigne.querySelector('.quantite').value = 1;
        nouvelleLigne.querySelector('.prix').value = '';
        nouvelleLigne.querySelector('.montant').value = '';
        container.appendChild(nouvelleLigne);
        
        // Ajouter les écouteurs d'événements à la nouvelle ligne
        ajouterEcouteursLigne(nouvelleLigne);
    });
    
    // Ajouter les écouteurs d'événements à une ligne
    function ajouterEcouteursLigne(ligne) {
        ligne.querySelector('.article-select').addEventListener('change', function() {
            const prix = this.options[this.selectedIndex].dataset.prix;
            ligne.querySelector('.prix').value = prix || '';
            calculerMontantLigne(ligne);
        });
        
        ligne.querySelector('.quantite').addEventListener('input', function() {
            calculerMontantLigne(ligne);
        });
        
        ligne.querySelector('.prix').addEventListener('input', function() {
            calculerMontantLigne(ligne);
        });
        
        ligne.querySelector('.supprimer-ligne').addEventListener('click', function() {
            if (document.querySelectorAll('.ligne-facture').length > 1) {
                ligne.remove();
                calculerTotal();
            }
        });
    }
    
    // Initialiser les écouteurs pour la première ligne
    document.querySelectorAll('.ligne-facture').forEach(ligne => {
        ajouterEcouteursLigne(ligne);
    });
    
    // Initialiser le calcul
    calculerTotal();
});
</script>

<?php include '../app/Views/layout/footer.php'; ?>