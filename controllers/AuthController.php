<?php
require_once '../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
    }
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Connecter un utilisateur
     */
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function login($usernameOrEmail, $password) {
        // Requête pour récupérer l'utilisateur par email ou username
        $stmt = $this->db->prepare("SELECT id_user, username, email, password FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug : vérifier si l'utilisateur est bien récupéré
        if (!$user) {
            echo json_encode(['error' => 'Utilisateur non trouvé']);
            return;
        }

        // Debug : Vérifier ce qui est stocké dans la base
        $storedHash = $user['password'];
        echo json_encode(["debug_password" => $password, "debug_hash" => $storedHash, "debug_verify" => password_verify($password, $storedHash)]);

        // Vérification du mot de passe
        if (!password_verify($password, $storedHash)) {
            echo json_encode(['error' => 'Mot de passe incorrect']);
            return;
        }

        // Réponse de connexion réussie
        echo json_encode([
            'message' => 'Connexion réussie',
            'user' => [
                'id_user' => $user['id_user'],
                'username' => $user['username'],
                'email' => $user['email']
            ]
        ]);
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
            return $this->userModel->getUsersById($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la recuperation de l\'utilisateur: '. $e->getMessage());   
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
            return $this->userModel->deleteUser($id);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la suppression d\'un utilisateur:'  . $e->getMessage());   
        }
    }
}
?>
