<?php

class Auteur {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Récupère tous les auteurs de la base de données.
     *
     * @return array
     */
    public function getAllAuteurs() {
        $query = "SELECT * FROM auteur";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un auteur par son ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getAuteurById($id) {
        $query = "SELECT * FROM auteur WHERE id_auteur = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ajoute un nouvel auteur dans la base de données.
     *
     * @param array $data
     * @return bool
     */
    public function addAuteur($data) {
        try {
            $query = "INSERT INTO auteur (nom_auteur, prenom_auteur, id_nationalite, image) VALUES (:nom, :prenom, :nationalite, :img)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $data['nom_auteur']);
            $stmt->bindParam(':prenom', $data['prenom_auteur']);
            $stmt->bindParam(':nationalite', $data['id_nationalite']);
            $stmt->bindParam(':img', $data['image']);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log l'erreur et retourne false
            error_log("Erreur lors de l'ajout de l'auteur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour un auteur existant dans la base de données.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAuteur($id, $data) {
        try {
            // Récupérer l'image avant la mise a jour
            $auteur = $this->getAuteurById($id);
            if ($auteur && !empty($auteur['image'])) {
                if (file_exists($auteur['image'])) {
                    unlink($auteur['image']);
                }
            }
            $query = "UPDATE auteur SET nom_auteur = :nom, prenom_auteur = :prenom, id_nationalite = :nationalite, image = :img WHERE id_auteur = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $data['nom_auteur']);
            $stmt->bindParam(':prenom', $data['prenom_auteur']);
            $stmt->bindParam(':nationalite', $data['id_nationalite']);
            $stmt->bindParam(':img', $data['image']);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log l'erreur et retourne false
            error_log("Erreur lors de la mise à jour de l'auteur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un auteur de la base de données.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAuteur($id) {
        try {
            // Récupérer l'image avant suppression
            $auteur = $this->getAuteurById($id);
            if ($auteur && !empty($auteur['image'])) {
                if (file_exists($auteur['image'])) {
                    unlink($auteur['image']);
                }
            }
            $query = "DELETE FROM auteur WHERE id_auteur = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Log l'erreur et retourne false
            error_log("Erreur lors de la suppression de l'auteur: " . $e->getMessage());
            return false;
        }
    }
}
?>