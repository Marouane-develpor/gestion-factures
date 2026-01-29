<?php include '../app/Views/layout/header.php'; ?>
<?php include '../app/Views/layout/menu.php'; ?>

<div class="col-md-10 main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Gestion des Clients</h2>
        <a href="index.php?action=ajouter_client" class="btn btn-success">
            <i class="bi bi-person-plus"></i> Nouveau Client
        </a>
    </div>
    
    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom & Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Ville</th>
                        <th>Factures</th>
                        <th>Chiffre d'Affaires</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['idClient']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?></strong>
                        </td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo htmlspecialchars($client['telephone']); ?></td>
                        <td><?php echo htmlspecialchars($client['ville']); ?></td>
                        <td>
                            <span class="badge bg-primary">
                                <?php echo $client['nb_factures']; ?> factures
                            </span>
                        </td>
                        <td>
                            <strong><?php echo number_format($client['chiffre_affaires'], 2, ',', ' '); ?> DH</strong>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?action=factures_client&id=<?php echo $client['idClient']; ?>" 
                                   class="btn btn-info" title="Voir factures">
                                    <i class="bi bi-receipt"></i>
                                </a>
                                <a href="index.php?action=modifier_client&id=<?php echo $client['idClient']; ?>" 
                                   class="btn btn-warning" title="Modifier">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=supprimer_client&id=<?php echo $client['idClient']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?')" 
                                   title="Supprimer">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../app/Views/layout/footer.php'; ?>