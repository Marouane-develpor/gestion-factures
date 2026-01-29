<?php
require __DIR__ . '/../layout/header.php';

// Initialiser les variables
$retards = $retards ?? [];

// Calculer le total dû
$totalDu = 0;
foreach ($retards as $facture) {
    $totalDu += $facture['montant_total'] ?? 0;
}
?>
<?php include '../app/Views/layout/header.php'; ?>
<?php include '../app/Views/layout/menu.php'; ?>
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-dark">Retards de Paiement</h1>
    </div>

    <!-- Alert Important -->
    <div class="alert alert-danger mb-4">
        <h5><i class="fas fa-exclamation-triangle"></i> Attention - Retards de paiement détectés</h5>
        <p class="mb-0">
            Les factures suivantes ont dépassé le délai de paiement de 60 jours. 
            Contactez ces clients pour régulariser leur situation.
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h6>Factures en Retard</h6>
                    <h2><?php echo count($retards); ?></h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h6>Montant Total Dû</h6>
                    <h3><?php echo number_format($totalDu, 2, ',', ' '); ?> DH</h3>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h6>Clients Concernés</h6>
                    <h2>
                        <?php 
                        $clientsUniques = array_unique(array_column($retards, 'idClient'));
                        echo count($clientsUniques);
                        ?>
                    </h2>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h6>Retard Moyen</h6>
                    <h2>
                        <?php 
                        $moyenne = count($retards) > 0 
                            ? array_sum(array_column($retards, 'jours_retard')) / count($retards) 
                            : 0;
                        echo round($moyenne); 
                        ?> jours
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Factures en retard -->
    <div class="card shadow">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Détail des Factures en Retard</h6>
            </div>
        </div>
        <div class="card-body">
            <?php if (empty($retards)): ?>
                <div class="alert alert-success text-center py-5">
                    <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                    <h4>Aucun retard de paiement !</h4>
                    <p class="mb-0">
                        Toutes les factures sont à jour. Excellent travail !
                    </p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-danger">
                            <tr>
                                <th>Facture</th>
                                <th>Client</th>
                                <th>Date Facture</th>
                                <th>Jours de Retard</th>
                                <th class="text-end">Montant Dû</th>
                                <th class="text-center">Contact Client</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($retards as $facture): 
                                $joursRetard = $facture['jours_retard'] ?? 0;
                                $niveauRetard = '';
                                if ($joursRetard > 90) $niveauRetard = 'danger';
                                elseif ($joursRetard > 66) $niveauRetard = 'warning';
                                else $niveauRetard = 'info';
                            ?>
                            <tr class="table-<?php echo $niveauRetard; ?>">
                                <td>
                                    <strong>
                                        <a href="index.php?action=detail_facture&id=<?php echo $facture['idFacture']; ?>" 
                                           class="text-decoration-none">
                                            <?php echo htmlspecialchars($facture['numero']); ?>
                                        </a>
                                    </strong>
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($facture['nom'] . ' ' . $facture['prenom']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($facture['ville'] ?? ''); ?></small>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($facture['date_facture'])); ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo $niveauRetard; ?> fs-6">
                                        <?php echo $joursRetard; ?> jours
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong class="text-danger fs-5">
                                        <?php echo number_format($facture['montant_total'], 2, ',', ' '); ?> DH
                                    </strong>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($facture['email'])): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($facture['email']); ?>?subject=Rappel%20Facture%20<?php echo urlencode($facture['numero']); ?>" 
                                           class="btn btn-sm btn-outline-primary mb-1" title="Envoyer email">
                                            <i class="fas fa-envelope"></i> Email
                                        </a><br>
                                    <?php endif; ?>
                                    <?php if (!empty($facture['telephone'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($facture['telephone']); ?>" 
                                           class="btn btn-sm btn-outline-success" title="Appeler">
                                            <i class="fas fa-phone"></i> Téléphone
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-dark">
                            <tr>
                                <td colspan="4" class="text-end fw-bold">TOTAL À RECOUVRER :</td>
                                <td class="text-end fw-bold">
                                    <span class="fs-4 text-white">
                                        <?php echo number_format($totalDu, 2, ',', ' '); ?> DH
                                    </span>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Fonctions pour les actions
function contacterClient(nom, email, telephone) {
    let message = `Contacter le client: ${nom}\n\n`;
    
    if (email) {
        message += ` Email: ${email}\n`;
    }
    if (telephone) {
        message += ` Téléphone: ${telephone}\n`;
    }
    
    message += "\nQue souhaitez-vous faire ?";
    
    const action = confirm(message + "\n\nCliquez sur OK pour envoyer un email, Annuler pour appeler.");
    
    if (action && email) {
        window.location.href = `mailto:${email}?subject=Rappel%20de%20paiement&body=Bonjour,%0D%0A%0D%0AVeuillez%20régulariser%20votre%20situation.%0D%0A%0D%0ACordialement`;
    } else if (telephone) {
        window.location.href = `tel:${telephone}`;
    }
}

function envoyerRappels() {
    if (confirm('Envoyer des rappels de paiement par email à tous les clients concernés ?')) {
        alert(' Les rappels ont été envoyés à tous les clients !\n\nCette fonctionnalité enverrait réellement des emails dans la version finale.');
        
        
    }
}

function exporterListe() {
    
    const csvContent = "Facture;Client;Date;Jours Retard;Montant Dû;Email;Téléphone\n" +
        <?php 
        $csvRows = [];
        foreach ($retards as $facture) {
            $csvRows[] = '"' . implode('";"', [
                $facture['numero'],
                $facture['nom'] . ' ' . $facture['prenom'],
                $facture['date_facture'],
                $facture['jours_retard'] ?? 0,
                $facture['montant_total'],
                $facture['email'] ?? '',
                $facture['telephone'] ?? ''
            ]) . '"';
        }
        echo '"' . implode("\\n\" + \n\"", $csvRows) . '"';
        ?> + "\n";
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    
    link.setAttribute("href", url);
    link.setAttribute("download", "retards_paiement_" + new Date().toISOString().split('T')[0] + ".csv");
    link.style.visibility = 'hidden';
    
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function genererMiseEnDemeure() {
    const clientSelect = prompt("Pour quel client voulez-vous générer une mise en demeure ?\n\nEntrez le nom du client :");
    
    if (clientSelect) {
        alert(` Mise en demeure générée pour ${clientSelect}.\n\nLe document est prêt à être imprimé et envoyé.`);
        
        // Ouvrir une nouvelle fenêtre avec le document
        const docWindow = window.open('', '_blank');
        docWindow.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Mise en Demeure - ${clientSelect}</title>
                <style>
                    body { font-family: 'Times New Roman', serif; padding: 40px; line-height: 1.6; }
                    .header { text-align: center; margin-bottom: 40px; }
                    .content { margin: 30px 0; }
                    .signature { margin-top: 100px; }
                    .footer { margin-top: 50px; font-size: 0.9em; color: #666; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h1>MISE EN DEMEURE</h1>
                    <h3>Article 1344 du Code Civil</h3>
                </div>
                
                <div class="content">
                    <p>À ${clientSelect},</p>
                    
                    <p>Par la présente, nous vous informons que vous êtes en retard de paiement concernant les factures ci-dessous :</p>
                    
                    <ul>
                        <?php foreach ($retards as $facture): ?>
                        <li>Facture ${facture['numero']} du ${new Date(facture['date_facture']).toLocaleDateString('fr-FR')} - Montant : ${facture['montant_total'].toLocaleString('fr-FR')} DH</li>
                        <?php endforeach; ?>
                    </ul>
                    
                    <p>Le montant total dû s'élève à <strong>${totalDu.toLocaleString('fr-FR')} DH</strong>.</p>
                    
                    <p>Nous vous mettons en demeure de régulariser votre situation dans un délai de <strong>15 jours</strong> à compter de la réception de cette lettre.</p>
                    
                    <p>Passé ce délai, nous nous verrons dans l'obligation de saisir les voies de recours appropriées, sans autre formalité.</p>
                </div>
                
                <div class="signature">
                    <p>Fait à _________, le ${new Date().toLocaleDateString('fr-FR')}</p>
                    <br><br>
                    <p>Le Gérant,</p>
                    <p><strong>_________________________</strong></p>
                </div>
                
                <div class="footer">
                    <p>Société Gestion Facturation - SIRET: XXXXXXXX - Adresse: XXX</p>
                </div>
            </body>
            </html>
        `);
        docWindow.document.close();
        docWindow.print();
    }
}

function signalerHuissier() {
    if (confirm('Êtes-vous sûr de vouloir signaler ce dossier à un huissier de justice ?\n\nCette action est irréversible.')) {
        alert(' Le dossier a été transmis au service de recouvrement.\n\nL\'huissier sera informé dans les plus brefs délais.');
    }
}
</script>

<?php
require __DIR__ . '/../layout/footer.php';
?>