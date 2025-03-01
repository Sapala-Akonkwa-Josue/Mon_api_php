<?php
class Categorie {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupérer toutes les catégories
     */
    public function getAllCategories() {
        $query = "SELECT * FROM categorie";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une catégorie par son ID
     */
    public function getCategorieById($id) {
        $query = "SELECT * FROM categorie WHERE id_categorie = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter une nouvelle catégorie
     */
    public function addCategorie($data) {
        $query = "INSERT INTO categorie (nom_categorie) VALUES (:nom)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_categorie']);
        return $stmt->execute();
    }

    /**
     * Mettre à jour une catégorie existante
     */
    public function updateCategorie($id, $data) {
        $query = "UPDATE categorie SET nom_categorie = :nom WHERE id_categorie = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_categorie']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Supprimer une catégorie
     */
    public function deleteCategorie($id) {
        $query = "DELETE FROM categorie WHERE id_categorie = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>