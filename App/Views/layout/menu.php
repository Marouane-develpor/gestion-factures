<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
    <div >
        <a class="navbar-brand" href="index.php?action=dashboard">
            <img src="images\facture.png" height="30"
                 alt="facture Logo"
                 loading="lazy" />
        </a>
    </div>
        <div class="collapse navbar-collapse">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($_GET['action'] ?? '') == 'dashboard' ? 'active' : ''; ?>" 
                   href="index.php?action=dashboard">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_GET['action'] ?? '', 'client') !== false ? 'active' : ''; ?>" 
                   href="index.php?action=liste_clients">
                    <i class="bi bi-people"></i> Clients
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo strpos($_GET['action'] ?? '', 'facture') !== false ? 'active' : ''; ?>" 
                   href="index.php?action=liste_factures">
                    <i class="bi bi-file-text"></i> Factures
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="statistiquesDropdown" 
                   role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-graph-up"></i> Statistiques
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="index.php?action=statistiques">
                            <i class="bi bi-list-check"></i> Statistiques Générales
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="index.php?action=clients_recompenser">
                            <i class="bi bi-trophy"></i> Clients à Récompenser
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="index.php?action=retards_paiement">
                            <i class="bi bi-exclamation-triangle"></i> Retards de Paiement
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
</div> 
    </div>
</nav>