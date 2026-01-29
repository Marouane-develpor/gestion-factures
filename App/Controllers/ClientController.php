<?php
namespace App\Controllers;

use App\Models\GestionClient;

class ClientController {
    private $client;

    public function __construct() {
        $this->client = new GestionClient();
    }

    public function liste() {
        $clients = $this->client->getAll();
        require '../app/Views/clients/liste.php';
    }

    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $ville = trim($_POST['ville'] ?? '');

            if (!empty($nom) && !empty($prenom)) {
                $result = $this->client->add($nom, $prenom, $email, $telephone, $ville);
                if ($result) {
                    header('Location: index.php?action=liste_clients&success=1');
                } else {
                    header('Location: index.php?action=ajouter_client&error=1');
                }
                exit;
            }
        }
        
        require '../app/Views/clients/ajouter.php';
    }

    public function modifier($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $telephone = trim($_POST['telephone'] ?? '');
            $ville = trim($_POST['ville'] ?? '');

            if (!empty($nom) && !empty($prenom)) {
                $result = $this->client->update($id, $nom, $prenom, $email, $telephone, $ville);
                if ($result) {
                    header('Location: index.php?action=liste_clients&success=2');
                } else {
                    header('Location: index.php?action=modifier_client&id=' . $id . '&error=1');
                }
                exit;
            }
        }

        $client = $this->client->getById($id);
        if (!$client) {
            header('Location: index.php?action=liste_clients&error=3');
            exit;
        }

        require '../app/Views/clients/modifier.php';
    }

    public function supprimer($id) {
        $result = $this->client->delete($id);
        if ($result === true) {
            header('Location: index.php?action=liste_clients&success=3');
        } else {
            // $result contient le message d'erreur
            header('Location: index.php?action=liste_clients&error=' . urlencode($result));
        }
        exit;
    }
}