<?php
require_once '../models/Nationalite.php';

class NationaliteController {
    private $model;

    public function __construct($db) {
        $this->model = new Nationalite($db);
    }

    /**
     * Récupérer toutes les nationalités
     */
    public function getAllNationalites() {
        $nationalites = $this->model->getAllNationalites();
        echo json_encode($nationalites);
    }

    /**
     * Récupérer une nationalité par son ID
     */
    public function getNationaliteById($id) {
        $nationalite = $this->model->getNationaliteById($id);
        if ($nationalite) {
            echo json_encode($nationalite);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Nationalité non trouvée']);
        }
    }

    /**
     * Ajouter une nouvelle nationalité
     */
    public function addNationalite($data) {
        if (!isset($data['nom_nationalite'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le champ nom_nationalite est obligatoire']);
            return;
        }

        if ($this->model->addNationalite($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Nationalité ajoutée avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de la nationalité']);
        }
    }

    /**
     * Mettre à jour une nationalité existante
     */
    public function updateNationalite($id, $data) {
        if (!isset($data['nom_nationalite'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le champ nom_nationalite est obligatoire']);
            return;
        }

        if ($this->model->updateNationalite($id, $data)) {
            echo json_encode(['message' => 'Nationalité mise à jour avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de la nationalité']);
        }
    }

    /**
     * Supprimer une nationalité
     */
    public function deleteNationalite($id) {
        if ($this->model->deleteNationalite($id)) {
            echo json_encode(['message' => 'Nationalité supprimée avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de la nationalité']);
        }
    }
}
?>