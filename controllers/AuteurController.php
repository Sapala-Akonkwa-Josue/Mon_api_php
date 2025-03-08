<?php
require_once '../models/Auteur.php';

class AuteurController {
    private $auteurModel;

    public function __construct($db) {
        $this->auteurModel = new Auteur($db);
    }

    /**
     * Récupère tous les auteurs.
     *
     * @return array
     */
    public function getAllAuteurs() {
       try {
        return $this->auteurModel->getAllAuteurs();
       } catch (Exception $e) {
        throw new Exception('Erreur lors de la récupération des auteurs : ' . $e->getMessage());
       }
    }

    /**
     * Récupère un auteur par son ID.
     *
     * @param int $id
     * @return array|null
     */
    public function getAuteurById($id) {
       try {
        return $this->auteurModel->getAuteurById($id);
       } catch (Exception $e) {
        throw new Exception('Erreur lors de la récupération des auteurs : ' . $e->getMessage());
       }
    }

    /**
     * Ajoute un auteur.
     *
     * @param array $data
     * @return bool
     */
    public function addAuteur($data) {
        try {
            return $this->auteurModel->addAuteur($data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'ajout de l\'auteur : ' . $e->getMessage());
        }
    }

    /**
     * Met à jour un auteur.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateAuteur($id, $data) {
        try {
            return $this->auteurModel->updateAuteur($id, $data);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour de l\'auteur: ' . $e->getMessage());
        }
    }

    /**
     * Supprime un auteur.
     *
     * @param int $id
     * @return bool
     */
    public function deleteAuteur($id) {
       try {
        return $this->auteurModel->deleteAuteur($id);
       } catch (\Throwable $th) {
        throw new Exception('Erreur lors de la suppression de l\'auteur : ' . $e->getMessage());
       }
    }
}
?>
