<?php
require_once '../models/Categorie.php';

class CategorieController {
    private $categorieModel;

    public function __construct($db) {
        $this->categorieModel = new Categorie($db);
    }

    // Récupérer toutes les catégories
    public function getAllCategories() {
        try {
            return $this->categorieModel->getAllCategories();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération des catégories : ' . $e->getMessage());
        }
    }

    // Récupérer une catégorie par son ID
    public function getCategorieById($id) {
        try {
            return $this->categorieModel->getCategorieById($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la récupération de la catégorie : ' . $e->getMessage());
        }
    }

    // Ajouter une nouvelle catégorie
    public function addCategorie($data) {
        try {
            return $this->categorieModel->addCategorie($data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de la catégorie : ' . $e->getMessage());
        }
    }

    // Mettre à jour une catégorie existante
    public function updateCategorie($id, $data) {
        try {
            return $this->categorieModel->updateCategorie($id, $data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour de la catégorie : ' . $e->getMessage());
        }
    }

    // Supprimer une catégorie
    public function deleteCategorie($id) {
        try {
            return $this->categorieModel->deleteCategorie($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression de la catégorie : ' . $e->getMessage());
        }
    }
}
?>
