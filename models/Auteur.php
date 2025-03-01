<?php
class Auteur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllAuteurs() {
        $query = "SELECT * FROM auteur";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAuteurById($id) {
        $query = "SELECT * FROM auteur WHERE id_auteur = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addAuteur($data) {
        $query = "INSERT INTO auteur (nom_auteur, prenom_auteur, id_nationalite, image) VALUES (:nom, :prenom, :nationalite, :img)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_auteur']);
        $stmt->bindParam(':prenom', $data['prenom_auteur']);
        $stmt->bindParam(':nationalite', $data['id_nationalite']);
        $stmt->bindParam(':img', $data['image']);
        return $stmt->execute();
    }

    public function updateAuteur($id, $data) {
        $query = "UPDATE auteur SET nom_auteur = :nom, prenom_auteur = :prenom, id_nationalite = :nationalite, image = :img WHERE id_auteur = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nom', $data['nom_auteur']);
        $stmt->bindParam(':prenom', $data['prenom_auteur']);
        $stmt->bindParam(':nationalite', $data['id_nationalite']);
        $stmt->bindParam(':img', $data['image']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function deleteAuteur($id) {
        $query = "DELETE FROM auteur WHERE id_auteur = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>