<?php

namespace App\Models;

use PDO;
use PDOException;
use App\config\Database;

class GestionClient {
    private $con;

    public function __construct() {
        $this->con = Database::getConnection();
    }

    public function add($nom, $prenom, $email, $telephone, $ville) {
        try {
            $stmt = $this->con->prepare("
                INSERT INTO clients (nom, prenom, email, telephone, ville) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nom, $prenom, $email, $telephone, $ville]);
            return $this->con->lastInsertId();
        } catch(PDOException $e) {
            error_log("ERROR add: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $nom, $prenom, $email, $telephone, $ville) {
        try {
            $stmt = $this->con->prepare("
                UPDATE clients 
                SET nom = ?, prenom = ?, email = ?, telephone = ?, ville = ? 
                WHERE idClient = ?
            ");
            return $stmt->execute([$nom, $prenom, $email, $telephone, $ville, $id]);
        } catch(PDOException $e) {
            error_log("ERROR update: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            // Vérifier si le client a des factures
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM factures WHERE idClient = ?");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                return "Impossible de supprimer, le client a des factures associées";
            }
            
            $stmt = $this->con->prepare("DELETE FROM clients WHERE idClient = ?");
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("ERROR delete: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $stmt = $this->con->query("
                SELECT c.*, 
                       COUNT(f.idFacture) as nb_factures,
                       COALESCE(SUM(f.montant_total), 0) as chiffre_affaires
                FROM clients c
                LEFT JOIN factures f ON c.idClient = f.idClient
                GROUP BY c.idClient
                ORDER BY c.nom ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->con->prepare("SELECT * FROM clients WHERE idClient = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getById: " . $e->getMessage());
            return false;
        }
    }
}