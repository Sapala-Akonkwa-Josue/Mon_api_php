<?php
class Nationalite {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupérer toutes les nationalités
     */
    public function getAllNationalites() {
        $query = "SELECT * FROM nationalite";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une nationalité par son ID
     */
    public function getNationaliteById($id) {
        $query = "SELECT * FROM nationalite WHERE id_nationalite = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter une nouvelle nationalité
     */
    public function addNationalite($data) {
        $query = "INSERT INTO nationalite (nom_nationalite, image) VALUES (:nom, :img)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_nationalite']);
        $stmt->bindParam(':img', $data['image']);
        return $stmt->execute();
    }

    /**
     * Mettre à jour une nationalité existante
     */
    public function updateNationalite($id, $data) {
        $query = "UPDATE nationalite SET nom_nationalite = :nom, image = :img WHERE id_nationalite = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_nationalite']);
        $stmt->bindParam(':img', $data['image']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Supprimer une nationalité
     */
    public function deleteNationalite($id) {
        $query = "DELETE FROM nationalite WHERE id_nationalite = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>