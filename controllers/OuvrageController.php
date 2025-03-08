<?php
require_once '../models/Ouvrage.php';

class OuvrageController {
    private $ouvrageModel;

    public function __construct($db) {
        $this->ouvrageModel = new Ouvrage($db);
    }

    // Récupérer tous les ouvrages
    public function getAllOuvrages() {
        try {
            return $this->ouvrageModel->getAllOuvrages();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des ouvrages : ' . $e->getMessage());
        }
    }

    // Récupérer un ouvrage par son ID
    public function getOuvrageById($id) {
        try {
            return $this->ouvrageModel->getOuvrageById($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération de l\'ouvrage : ' . $e->getMessage());
        }
    }

    // Ajouter un nouvel ouvrage
    public function addOuvrage($data) {
        try {
            return $this->ouvrageModel->addOuvrage($data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de l\'ouvrage : ' . $e->getMessage());
        }
    }

    // Mettre à jour un ouvrage existant
    public function updateOuvrage($id, $data) {
        try {
            return $this->ouvrageModel->updateOuvrage($id, $data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour de l\'ouvrage : ' . $e->getMessage());
        }
    }

    // Supprimer un ouvrage
    public function deleteOuvrage($id) {
        try {
            return $this->ouvrageModel->deleteOuvrage($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de l\'ouvrage : ' . $e->getMessage());
        }
    }
}
?>
