<?php

namespace App\Models;

use PDO;
use PDOException;
use App\config\Database;

class GestionFacture {
    private $con;

    public function __construct() {
        $this->con = Database::getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->con->query("
                SELECT f.idFacture, f.numero, f.date_facture, f.montant_total, 
                       f.statut, c.idClient, c.nom, c.prenom, c.email, c.telephone, c.ville
                FROM factures f 
                JOIN clients c ON f.idClient = c.idClient
                ORDER BY f.date_facture DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getById($idFacture) {
        try {
            $stmt = $this->con->prepare("
                SELECT f.*, c.nom, c.prenom, c.email, c.telephone, c.ville
                FROM factures f 
                JOIN clients c ON f.idClient = c.idClient
                WHERE f.idFacture = ?
            ");
            $stmt->execute([$idFacture]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getById: " . $e->getMessage());
            return false;
        }
    }

    public function getLignes($idFacture) {
        try {
            $stmt = $this->con->prepare("
                SELECT lf.*, a.nom as article_nom, a.prix as prix_unitaire, a.reference
                FROM ligne_facture lf 
                JOIN articles a ON lf.idArticle = a.idArticle
                WHERE lf.idFacture = ?
                ORDER BY lf.idLigneFacture
            ");
            $stmt->execute([$idFacture]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getLignes: " . $e->getMessage());
            return [];
        }
    }

    public function addF($numero, $date_facture, $idClient, $statut, $montant_total) {
        try {
            // Générer un numéro de facture si non fourni
            if (empty($numero)) {
                $numero = "FACT-" . date('Ymd') . "-" . rand(1000, 9999);
            }
            
            $stmt = $this->con->prepare("
                INSERT INTO factures (numero, date_facture, idClient, statut, montant_total) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$numero, $date_facture, $idClient, $statut, $montant_total]);
            return $this->con->lastInsertId();
        } catch(PDOException $e) {
            error_log("ERROR addF: " . $e->getMessage());
            return false;
        }
    }

    public function addLf($idFacture, $idArticle, $quantite, $prix, $montant) {
        try {
            $stmt = $this->con->prepare("
                INSERT INTO ligne_facture (idFacture, idArticle, quantite, prix, montant) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$idFacture, $idArticle, $quantite, $prix, $montant]);
            return true;
        } catch(PDOException $e) {
            error_log("ERROR addLf: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatut($idFacture, $statut) {
        try {
            $stmt = $this->con->prepare("UPDATE factures SET statut = ? WHERE idFacture = ?");
            return $stmt->execute([$statut, $idFacture]);
        } catch(PDOException $e) {
            error_log("ERROR updateStatut: " . $e->getMessage());
            return false;
        }
    }

    public function getByClient($idClient) {
        try {
            $stmt = $this->con->prepare("
                SELECT f.*, c.nom, c.prenom 
                FROM factures f 
                JOIN clients c ON f.idClient = c.idClient
                WHERE f.idClient = ?
                ORDER BY f.date_facture DESC
            ");
            $stmt->execute([$idClient]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getByClient: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalChiffreAffaires() {
        try {
            $stmt = $this->con->query("SELECT SUM(montant_total) as total FROM factures WHERE statut = 'réglée'");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch(PDOException $e) {
            error_log("ERROR getTotalChiffreAffaires: " . $e->getMessage());
            return 0;
        }
    }

    public function getClientsARecompenser() {
        try {
            $stmt = $this->con->query("
                SELECT c.idClient, c.nom, c.prenom, c.email, 
                       SUM(f.montant_total) as chiffre_affaires
                FROM clients c
                JOIN factures f ON c.idClient = f.idClient
                WHERE f.statut = 'réglée'
                GROUP BY c.idClient
                HAVING chiffre_affaires >= 500000
                ORDER BY chiffre_affaires DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getClientsARecompenser: " . $e->getMessage());
            return [];
        }
    }

    public function getRetardsPaiement() {
        try {
            $stmt = $this->con->query("
                SELECT f.*, c.nom, c.prenom, c.email, c.telephone,
                       DATEDIFF(CURDATE(), f.date_facture) as jours_retard
                FROM factures f
                JOIN clients c ON f.idClient = c.idClient
                WHERE f.statut = 'non réglée'
                AND f.date_facture < DATE_SUB(CURDATE(), INTERVAL 60 DAY)
                ORDER BY jours_retard DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getRetardsPaiement: " . $e->getMessage());
            return [];
        }
    }
    

public function update($idFacture, $numero, $date_facture, $idClient, $statut, $montant_total) {
    try {
        $stmt = $this->con->prepare("
            UPDATE factures 
            SET numero = ?, date_facture = ?, idClient = ?, statut = ?, montant_total = ?
            WHERE idFacture = ?
        ");
        return $stmt->execute([$numero, $date_facture, $idClient, $statut, $montant_total, $idFacture]);
    } catch(PDOException $e) {
        error_log("ERROR update: " . $e->getMessage());
        return false;
    }
}

public function deleteF($idFacture) {
    try {
        $stmt = $this->con->prepare("DELETE FROM factures WHERE idFacture = ?");
        return $stmt->execute([$idFacture]);
    } catch(PDOException $e) {
        error_log("ERROR deleteF: " . $e->getMessage());
        return false;
    }
}

public function deleteLf($idLigne) {
    try {
        $stmt = $this->con->prepare("DELETE FROM ligne_facture WHERE idLigneFacture = ?");
        return $stmt->execute([$idLigne]);
    } catch(PDOException $e) {
        error_log("ERROR deleteLf: " . $e->getMessage());
        return false;
    }
}

public function updateMontant($montant_total, $idFacture) {
    try {
        $stmt = $this->con->prepare("UPDATE factures SET montant_total = ? WHERE idFacture = ?");
        return $stmt->execute([$montant_total, $idFacture]);
    } catch(PDOException $e) {
        error_log("ERROR updateMontant: " . $e->getMessage());
        return false;
    }
}
}