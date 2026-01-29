    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
<?php if (isset($_GET['success'])): ?>
    <?php 
    $messages = [
        1 => 'Client ajouté avec succès',
        2 => 'Client modifié avec succès',
        3 => 'Client supprimé avec succès',
        4 => 'Facture ajoutée avec succès',
        5 => 'Facture réglée avec succès'
    ];
    ?>
    alert('<?php echo $messages[$_GET['success']] ?? 'Opération réussie'; ?>');
<?php endif; ?>

<?php if (isset($_GET['error'])): ?>
    alert('Erreur: <?php echo urldecode($_GET['error']); ?>');
<?php endif; ?>
</script>
</body>
</html>