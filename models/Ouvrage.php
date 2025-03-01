<?php
class Ouvrage {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupérer tous les ouvrages
     */
    public function getAllOuvrages() {
        $query = "SELECT * FROM ouvrage";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un ouvrage par son ID
     */
    public function getOuvrageById($id) {
        $query = "SELECT * FROM ouvrage WHERE id_ouvrage = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter un nouvel ouvrage
     */
    public function addOuvrage($data) {
        $query = "INSERT INTO ouvrage (titre_ouvrage, id_auteur, id_categorie, annee_publication, image) VALUES (:titre, :auteur, :categorie, :annee, :img)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titre', $data['titre_ouvrage']);
        $stmt->bindParam(':auteur', $data['id_auteur']);
        $stmt->bindParam(':categorie', $data['id_categorie']);
        $stmt->bindParam(':annee', $data['annee_publication']);
        $stmt->bindParam(':img', $data['image']);
        return $stmt->execute();
    }

    /**
     * Mettre à jour un ouvrage existant
     */
    public function updateOuvrage($id, $data) {
        $query = "UPDATE ouvrage SET titre_ouvrage = :titre, id_auteur = :auteur, id_categorie = :categorie, annee_publication = :annee, image = :img WHERE id_ouvrage = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':titre', $data['titre_ouvrage']);
        $stmt->bindParam(':auteur', $data['id_auteur']);
        $stmt->bindParam(':categorie', $data['id_categorie']);
        $stmt->bindParam(':annee', $data['annee_publication']);
        $stmt->bindParam(':img', $data['image']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Supprimer un ouvrage
     */
    public function deleteOuvrage($id) {
        $query = "DELETE FROM ouvrage WHERE id_ouvrage = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>