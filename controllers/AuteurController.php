<?php
require_once '../models/Auteur.php';

class AuteurController {
    private $model;

    public function __construct($db) {
        $this->model = new Auteur($db);
    }

    public function getAllAuteurs() {
        $auteurs = $this->model->getAllAuteurs();
        echo json_encode($auteurs);
    }

    public function getAuteurById($id) {
        $auteur = $this->model->getAuteurById($id);
        if ($auteur) {
            echo json_encode($auteur);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Auteur non trouvé']);
        }
    }

    public function addAuteur($data) {
        if ($this->model->addAuteur($data)) {
            http_response_code(201);
            echo json_encode(['message' => 'Auteur ajouté']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'auteur']);
        }
    }

    public function updateAuteur($id, $data) {
        if ($this->model->updateAuteur($id, $data)) {
            echo json_encode(['message' => 'Auteur mis à jour']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'auteur']);
        }
    }

    public function deleteAuteur($id) {
        if ($this->model->deleteAuteur($id)) {
            echo json_encode(['message' => 'Auteur supprimé']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la suppression de l\'auteur']);
        }
    }
}
?>