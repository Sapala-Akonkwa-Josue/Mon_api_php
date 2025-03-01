<?php
require_once '../models/Ouvrage.php';

class OuvrageController {
    private $model;

    public function __construct($db) {
        $this->model = new Ouvrage($db);
    }

    /**
     * Récupérer tous les ouvrages
     */
    public function getAllOuvrages() {
        $ouvrages = $this->model->getAllOuvrages();
        echo json_encode($ouvrages);
    }

    /**
     * Récupérer un ouvrage par son ID
     */
    public function getOuvrageById($id) {
        $ouvrage = $this->model->getOuvrageById($id);
        if ($ouvrage) {
            echo json_encode($ouvrage);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ouvrage non trouvé']);
        }
    }

    /**
     * Ajouter un nouvel ouvrage
     */
    public function addOuvrage($data) {
        // Validation des champs obligatoires
        if (!isset($data['titre_ouvrage']) || !isset($data['id_auteur']) || !isset($data['id_categorie']) || !isset($data['annee_publication'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs titre_ouvrage, id_auteur, id_categorie et annee_publication sont obligatoires']);
            return;
        }

        if ($this->model->addOuvrage($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Ouvrage ajouté avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'ouvrage']);
        }
    }

    /**
     * Mettre à jour un ouvrage existant
     */
    public function updateOuvrage($id, $data) {
        // Validation des champs obligatoires
        if (!isset($data['titre_ouvrage']) || !isset($data['id_auteur']) || !isset($data['id_categorie']) || !isset($data['annee_publication'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs titre_ouvrage, id_auteur, id_categorie et annee_publication sont obligatoires']);
            return;
        }

        if ($this->model->updateOuvrage($id, $data)) {
            echo json_encode(['message' => 'Ouvrage mis à jour avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'ouvrage']);
        }
    }

    /**
     * Supprimer un ouvrage
     */
    public function deleteOuvrage($id) {
        if ($this->model->deleteOuvrage($id)) {
            echo json_encode(['message' => 'Ouvrage supprimé avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de l\'ouvrage']);
        }
    }
}
?>