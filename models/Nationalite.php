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
     * Ajouter une nouvelle nationalité avec une image
     */
    public function addNationalite($data) {
        try {
            $query = "INSERT INTO nationalite (nom_nationalite, image) VALUES (:nom, :img)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $data['nom_nationalite']);
            $stmt->bindParam(':img', $data['image']);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de la nationalité: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour une nationalité existante avec une image
     */
    public function updateNationalite($id, $data) {
        try {
             // Récupérer l'image avant avant la mise a jour
             $nationalite = $this->getNationaliteById($id);
             if ($nationalite && !empty($nationalite['image'])) {
                 if (file_exists($nationalite['image'])) {
                     unlink($nationalite['image']);
                 }
             }
            $query = "UPDATE nationalite SET nom_nationalite = :nom, image = :img WHERE id_nationalite = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $data['nom_nationalite']);
            $stmt->bindParam(':img', $data['image']);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de la nationalité: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprimer une nationalité avec son image
     */
    public function deleteNationalite($id) {
        try {
            // Récupérer l'image avant suppression
            $nationalite = $this->getNationaliteById($id);
            if ($nationalite && !empty($nationalite['image'])) {
                if (file_exists($nationalite['image'])) {
                    unlink($nationalite['image']);
                }
            }

            $query = "DELETE FROM nationalite WHERE id_nationalite = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de la nationalité: " . $e->getMessage());
            return false;
        }
    }
}
?>
