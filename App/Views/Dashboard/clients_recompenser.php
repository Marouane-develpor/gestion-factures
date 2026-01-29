<?php
require __DIR__ . '/../layout/header.php';

// Initialiser les variables
$clients = $clients ?? [];
?>
<?php include '../app/Views/layout/header.php'; ?>
<?php include '../app/Views/layout/menu.php'; ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-dark">Clients à Récompenser</h1>
    </div>

    <!-- Information Card -->
    <div class="alert alert-info mb-4">
        <h5>Critères de Récompense</h5>
        <p class="mb-0">
            Cette liste affiche les clients ayant un chiffre d'affaires cumulé supérieur ou égal à <strong>500.000 DH</strong>.
            Ces clients sont éligibles pour recevoir des bons d'achat de valeur intéressante.
        </p>
    </div>

    <!-- Clients List -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Liste des Clients Éligibles</h6>
                <span class="badge bg-primary fs-6">
                    <?php echo count($clients); ?>
                </span>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($clients)): ?>
                <div class="alert alert-warning text-center py-5">
                    <i class="fas fa-trophy fa-3x mb-3 text-warning"></i>
                    <h4>Aucun client éligible pour le moment</h4>
                    <p class="mb-0">
                        Aucun client n'a encore atteint le seuil de 500.000 DH de chiffre d'affaires.
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th></th>   
                                <th>Client</th>
                                <th>Contact</th>
                                <th class="text-center">Nombre de Factures</th>
                                <th class="text-end">Chiffre d'Affaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $index => $client): 
                                $chiffreAffaires = $client['chiffre_affaires'] ?? 0;
                                $pourcentage = ($chiffreAffaires / 500000) * 100;
                                if ($pourcentage > 100) $pourcentage = 100;
                            ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?></strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?php echo htmlspecialchars($client['ville'] ?? ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if (!empty($client['email'])): ?>
                                        <div>
                                            <i class="fas fa-envelope text-primary"></i> 
                                            <?php echo htmlspecialchars($client['email']); ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($client['telephone'])): ?>
                                        <div>
                                            <i class="fas fa-phone text-success"></i> 
                                            <?php echo htmlspecialchars($client['telephone']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary fs-6">
                                        <?php echo $client['nb_factures'] ?? 0; ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="mb-3">
                                        <strong class="text-success fs-5">
                                            <?php echo number_format($chiffreAffaires, 2, ',', ' '); ?> DH
                                        </strong>
                                    </div>
                                </td>
                                
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">Total CA des clients éligibles :</td>
                                <td class="text-end fw-bold">
                                    <?php 
                                    $totalCA = array_sum(array_column($clients, 'chiffre_affaires'));
                                    echo number_format($totalCA, 2, ',', ' ') . ' DH';
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Suggestions de récompenses -->
    <div class="row mt-4">
        <div class="col-lg-6 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Suggestions de Récompenses</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <i class="fas fa-ticket-alt text-primary"></i>
                            <strong>Bon d'achat</strong> : 5% du CA annuel
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-percent text-success"></i>
                            <strong>Remise exceptionnelle</strong> : 10% sur la prochaine commande
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-gift text-warning"></i>
                            <strong>Cadeau d'affaires</strong> : Produits premium ou paniers cadeaux
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-shipping-fast text-info"></i>
                            <strong>Livraison gratuite</strong> : Pour toutes les commandes de l'année
                        </li>
                        <li class="list-group-item">
                            <i class="fas fa-star text-danger"></i>
                            <strong>Statut VIP</strong> : Support prioritaire et avantages exclusifs
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="fas fa-calculator"></i> Calcul des Bons d'Achat</h6>
                </div>
                <div class="card-body">
                    <form id="calculBonForm">
                        <div class="mb-3">
                            <label class="form-label">Sélectionner un client</label>
                            <select class="form-select" id="clientSelect">
                                <option value="">Choisir un client...</option>
                                <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['idClient']; ?>" data-ca="<?php echo $client['chiffre_affaires'] ?? 0; ?>">
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                    (<?php echo number_format($client['chiffre_affaires'] ?? 0, 0, ',', ' '); ?> DH)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pourcentage du bon</label>
                            <div class="input-group">
                                <span class="input-group-text">%</span>
                                <input type="number" class="form-control" id="pourcentageInput" value="5" min="1" max="10">
                            </div>
                            <small class="text-muted">Glissez pour ajuster le pourcentage (1-10%)</small>
                        </div>
                        
                        <div class="card bg-light p-3 mb-3">
                            <h6 class="text-center">Valeur du Bon d'Achat</h6>
                            <h2 class="text-center text-success" id="valeurBon">0.00 DH</h2>
                        </div>
                        
                        <div class="d-grid">
                            <button type="button" class="btn btn-primary" onclick="genererBon()">
                                <i class="fas fa-print"></i> Générer le Bon
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour attribuer un bon
function attribuerBon(clientId, clientNom, chiffreAffaires) {
    const valeurSuggest = Math.round(chiffreAffaires * 0.05); // 5% du CA
    
    const valeur = prompt(`Attribuer un bon d'achat à ${clientNom}\n\nChiffre d'affaires: ${chiffreAffaires.toLocaleString()} DH\n\nValeur suggérée: ${valeurSuggest.toLocaleString()} DH (5%)\n\nEntrez la valeur du bon (en DH):`, valeurSuggest);
    
    if (valeur && !isNaN(valeur) && valeur > 0) {
        if (confirm(`Confirmer l'attribution d'un bon de ${parseFloat(valeur).toLocaleString()} DH à ${clientNom}?`)) {
            // Simulation - à remplacer par un appel AJAX réel
            fetch('index.php?action=attribuer_bon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `idClient=${clientId}&valeur=${valeur}&client=${encodeURIComponent(clientNom)}`
            })
            .then(response => response.text())
            .then(data => {
                alert(` Bon de ${parseFloat(valeur).toLocaleString()} DH attribué à ${clientNom} avec succès!`);
                // Recharger la page pour voir les changements
                window.location.reload();
            })
            .catch(error => {
                alert(' Erreur lors de l\'attribution du bon: ' + error);
            });
        }
    }
}

// Calcul du bon d'achat
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('clientSelect');
    const pourcentageRange = document.getElementById('pourcentageRange');
    const pourcentageInput = document.getElementById('pourcentageInput');
    const valeurBon = document.getElementById('valeurBon');
    
    function calculerBon() {
        const selectedOption = clientSelect.options[clientSelect.selectedIndex];
        const chiffreAffaires = parseFloat(selectedOption.dataset.ca) || 0;
        const pourcentage = parseFloat(pourcentageInput.value) || 5;
        
        const valeur = (chiffreAffaires * pourcentage) / 100;
        valeurBon.textContent = valeur.toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }) + ' DH';
    }
    
    clientSelect.addEventListener('change', calculerBon);
    pourcentageInput.addEventListener('input', function() {
        pourcentageRange.value = this.value;
        calculerBon();
    });
    
    // Initialiser le calcul
    calculerBon();
});

function genererBon() {
    const clientSelect = document.getElementById('clientSelect');
    const selectedOption = clientSelect.options[clientSelect.selectedIndex];
    
    if (!selectedOption.value) {
        alert('Veuillez sélectionner un client');
        return;
    }
    
    const clientNom = selectedOption.text.split('(')[0].trim();
    const valeur = document.getElementById('valeurBon').textContent;
    
    if (confirm(`Générer un bon d'achat de ${valeur} pour ${clientNom}?`)) {
        // Ouvrir une nouvelle fenêtre avec le bon
        const bonWindow = window.open('', '_blank');
        bonWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Bon d'Achat - ${clientNom}</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    .bon { border: 3px dashed #333; padding: 30px; max-width: 600px; margin: 0 auto; }
                    .header { text-align: center; margin-bottom: 30px; }
                    .valeur { font-size: 48px; color: #28a745; text-align: center; margin: 30px 0; }
                    .details { margin: 20px 0; }
                    .footer { margin-top: 40px; text-align: center; color: #666; }
                </style>
            </head>
            <body>
                <div class="bon">
                    <div class="header">
                        <h1>🎁 BON D'ACHAT</h1>
                        <h3>En remerciement de votre fidélité</h3>
                    </div>
                    
                    <div class="valeur">
                        ${valeur}
                    </div>
                    
                    <div class="details">
                        <p><strong>Bénéficiaire:</strong> ${clientNom}</p>
                        <p><strong>Date d'émission:</strong> ${new Date().toLocaleDateString('fr-FR')}</p>
                        <p><strong>Date de validité:</strong> ${new Date(new Date().setMonth(new Date().getMonth() + 3)).toLocaleDateString('fr-FR')}</p>
                        <p><strong>Code du bon:</strong> BON-${Date.now().toString().substr(-6)}</p>
                    </div>
                    
                    <div class="footer">
                        <p>Ce bon est valable pour tout achat dans notre boutique.</p>
                        <p>Non cumulable avec d'autres promotions. Valable 3 mois.</p>
                        <hr>
                        <p><small>Gestion Facturation © ${new Date().getFullYear()}</small></p>
                    </div>
                </div>
            </body>
            </html>
        `);
        bonWindow.document.close();
        bonWindow.print();
    }
}
</script>

<?php
require __DIR__ . '/../layout/footer.php';
?>