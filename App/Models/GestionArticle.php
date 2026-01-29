<?php

namespace App\Models;

use PDO;
use PDOException;
use App\config\Database;

class GestionArticle {
    private $con;

    public function __construct() {
        $this->con = Database::getConnection();
    }

    public function getAll() {
        try {
            $stmt = $this->con->query("
                SELECT * FROM articles 
                ORDER BY nom ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getAll Articles: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        try {
            $stmt = $this->con->prepare("
                SELECT * FROM articles 
                WHERE idArticle = ?
            ");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getById Article: " . $e->getMessage());
            return false;
        }
    }

    public function add($reference, $nom, $description, $prix, $stock) {
        try {
            $stmt = $this->con->prepare("
                INSERT INTO articles (reference, nom, description, prix, stock) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$reference, $nom, $description, $prix, $stock]);
            return $this->con->lastInsertId();
        } catch(PDOException $e) {
            error_log("ERROR add Article: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $reference, $nom, $description, $prix, $stock) {
        try {
            $stmt = $this->con->prepare("
                UPDATE articles 
                SET reference = ?, nom = ?, description = ?, prix = ?, stock = ? 
                WHERE idArticle = ?
            ");
            return $stmt->execute([$reference, $nom, $description, $prix, $stock, $id]);
        } catch(PDOException $e) {
            error_log("ERROR update Article: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            // Vérifier si l'article est utilisé dans des factures
            $stmt = $this->con->prepare("
                SELECT COUNT(*) FROM ligne_facture 
                WHERE idArticle = ?
            ");
            $stmt->execute([$id]);
            $count = $stmt->fetchColumn();
            
            if ($count > 0) {
                return "Impossible de supprimer, l'article est utilisé dans des factures";
            }
            
            $stmt = $this->con->prepare("DELETE FROM articles WHERE idArticle = ?");
            return $stmt->execute([$id]);
        } catch(PDOException $e) {
            error_log("ERROR delete Article: " . $e->getMessage());
            return false;
        }
    }

    public function updateStock($idArticle, $quantite) {
        try {
            $stmt = $this->con->prepare("
                UPDATE articles 
                SET stock = stock - ? 
                WHERE idArticle = ? AND stock >= ?
            ");
            return $stmt->execute([$quantite, $idArticle, $quantite]);
        } catch(PDOException $e) {
            error_log("ERROR updateStock: " . $e->getMessage());
            return false;
        }
    }

    public function getByReference($reference) {
        try {
            $stmt = $this->con->prepare("
                SELECT * FROM articles 
                WHERE reference LIKE ?
            ");
            $stmt->execute(['%' . $reference . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ERROR getByReference: " . $e->getMessage());
            return [];
        }
    }
}