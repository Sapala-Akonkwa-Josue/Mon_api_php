<?php
require_once '../models/Categorie.php';

class CategorieController {
    private $model;

    public function __construct($db) {
        $this->model = new Categorie($db);
    }

    /**
     * Récupérer toutes les catégories
     */
    public function getAllCategories() {
        $categories = $this->model->getAllCategories();
        echo json_encode($categories);
    }

    /**
     * Récupérer une catégorie par son ID
     */
    public function getCategorieById($id) {
        $categorie = $this->model->getCategorieById($id);
        if ($categorie) {
            echo json_encode($categorie);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Catégorie non trouvée']);
        }
    }

    /**
     * Ajouter une nouvelle catégorie
     */
    public function addCategorie($data) {
        if (!isset($data['nom_categorie'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le champ nom_categorie est obligatoire']);
            return;
        }

        if ($this->model->addCategorie($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Catégorie ajoutée avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de la catégorie']);
        }
    }

    /**
     * Mettre à jour une catégorie existante
     */
    public function updateCategorie($id, $data) {
        if (!isset($data['nom_categorie'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Le champ nom_categorie est obligatoire']);
            return;
        }

        if ($this->model->updateCategorie($id, $data)) {
            echo json_encode(['message' => 'Catégorie mise à jour avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de la catégorie']);
        }
    }

    /**
     * Supprimer une catégorie
     */
    public function deleteCategorie($id) {
        if ($this->model->deleteCategorie($id)) {
            echo json_encode(['message' => 'Catégorie supprimée avec succès']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de la catégorie']);
        }
    }
}
?>