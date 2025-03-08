<?php
require_once '../models/Nationalite.php';

class NationaliteController {
    private $nationaliteModel;

    public function __construct($db) {
        $this->nationaliteModel = new Nationalite($db);
    }

    /**
     * Récupérer toutes les nationalités.
     */
    public function getAllNationalites() {
       try {
        return $this->nationaliteModel->getAllNationalites();
       } catch (Exception $e) {
        throw new Exception('Erreur lors de la récupération des nationalites : ' . $e->getMessage());
       }
    }

    /**
     * Récupérer une nationalité par son ID.
     */
    public function getNationaliteById($id) {
        try {
            return $this->nationaliteModel->getNationaliteById($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération de la nationalite : ' . $e->getMessage());
        }
    }

    /**
     * Ajouter une nouvelle nationalité.
     */
    public function addNationalite($data) {
        try {
            return $this->nationaliteModel->addNationalite($data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de la nationalite : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour une nationalité existante.
     */
    public function updateNationalite($id, $data) {
        try {
            return $this->nationaliteModel->updateNationalite($id, $data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour de la nationalite : ' . $e->getMessage());
        }
    }

    /**
     * Supprimer une nationalité.
     */
    public function deleteNationalite($id) {
        try {
            return $this->nationaliteModel->deleteNationalite($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de la nationalite : ' . $e->getMessage());
        }
    }
}

?>
