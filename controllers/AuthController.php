<?php
require_once '../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Connecter un utilisateur
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function login($usernameOrEmail, $password) {
        try {
            // Vérifier si l'utilisateur existe avec le nom d'utilisateur ou l'e-mail
            $user = $this->userModel->findByUsernameOrEmail($usernameOrEmail);
            
            if ($user && password_verify($password, $user['password'])) {
                http_response_code(200); // Code 200 pour le succès
                echo json_encode(['message' => 'Connexion réussie', 'data' => $user]);
            } else {
                http_response_code(401); // Code 401 pour erreur d'authentification
                echo json_encode(['error' => 'Nom d\'utilisateur ou mot de passe incorrect']);
                error_log("Tentative de connexion échouée pour: " . $usernameOrEmail);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la connexion : ' . $e->getMessage()]);
        }
    }
     ///////////////////////////////////////////////////////////////////////////////////////////////////////////
   /**
     * Ajouter un nouvel utilisateur
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function addUser($userData) {
        try {
            // Vérifier si l'utilisateur existe déjà
            $existingUser = $this->userModel->findByUsernameOrEmail($userData['username']);
            if ($existingUser) {
                http_response_code(400); // Code 400 pour une mauvaise requête
                echo json_encode(['error' => 'Le nom d\'utilisateur ou l\'e-mail est déjà utilisé']);
                return;
            }

            // Hasher le mot de passe avant de l'enregistrer
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

            // Ajouter l'utilisateur
            $userId = $this->userModel->addUser($userData);
            if ($userId) {
                http_response_code(201); // Code 201 pour la création réussie
                echo json_encode(['message' => 'Utilisateur enregistré avec succès', 'userId' => $userId]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Erreur lors de l\'enregistrement de l\'utilisateur']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de l\'enregistrement de l\'utilisateur : ' . $e->getMessage()]);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Récupérer tous les utilisateurs
     */
    //////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getUsers() {
        try {
            // Récupérer tous les utilisateurs depuis le modèle
            $users = $this->userModel->getAllUsers();

            // Vérifier si des utilisateurs ont été trouvés
            if (!empty($users)) {
                http_response_code(200); // Code 200 pour le succès
                echo json_encode(['data' => $users]);
            } else {
                http_response_code(404); // Code 404 si aucun utilisateur n'est trouvé
                echo json_encode(['message' => 'Aucun utilisateur trouvé.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Code 500 pour une erreur interne du serveur
            echo json_encode(['error' => 'Erreur lors de la récupération des utilisateurs : ' . $e->getMessage()]);
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
     /**
     * Récupérer un utilisateur par son ID.
     */
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getUsersById($id) {
        try {
            // Récupérer l'utilisateur par son ID depuis le modèle
            $user = $this->userModel->getUsersById($id);

            // Vérifier si l'utilisateur a été trouvé
            if ($user) {
                http_response_code(200); // Code 200 pour le succès
                echo json_encode(['data' => $user]);
            } else {
                http_response_code(404); // Code 404 si l'utilisateur n'est pas trouvé
                echo json_encode(['message' => 'Utilisateur non trouvé.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Code 500 pour une erreur interne du serveur
            echo json_encode(['error' => 'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage()]);
        }
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Mettre à jour un utilisateur
     */
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function updateUser($id, $userData) {
        try {
            // Vérifier si l'utilisateur existe avant de mettre à jour
            $existingUser = $this->userModel->getUsersById($id);
            if (!$existingUser) {
                http_response_code(404); // Code 404 si l'utilisateur n'est pas trouvé
                echo json_encode(['message' => 'Utilisateur non trouvé.']);
                return;
            }

            // Mettre à jour l'utilisateur
            $updated = $this->userModel->updateUser($id, $userData);

            if ($updated) {
                http_response_code(200); // Code 200 pour le succès
                echo json_encode(['message' => 'Utilisateur mis à jour avec succès.']);
            } else {
                http_response_code(500); // Code 500 en cas d'échec de la mise à jour
                echo json_encode(['error' => 'Échec de la mise à jour de l\'utilisateur.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Code 500 pour une erreur interne du serveur
            echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage()]);
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Supprimer un utilisateur
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function deleteUser($id) {
        try {
            // Vérifier si l'utilisateur existe avant de supprimer
            $existingUser = $this->userModel->getUsersById($id);
            if (!$existingUser) {
                http_response_code(404); // Code 404 si l'utilisateur n'est pas trouvé
                echo json_encode(['message' => 'Utilisateur non trouvé.']);
                return;
            }

            // Supprimer l'utilisateur
            $deleted = $this->userModel->deleteUser($id);

            if ($deleted) {
                http_response_code(200); // Code 200 pour le succès
                echo json_encode(['message' => 'Utilisateur supprimé avec succès.']);
            } else {
                http_response_code(500); // Code 500 en cas d'échec de la suppression
                echo json_encode(['error' => 'Échec de la suppression de l\'utilisateur.']);
            }
        } catch (Exception $e) {
            http_response_code(500); // Code 500 pour une erreur interne du serveur
            echo json_encode(['error' => 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage()]);
        }
    }
}
?>
