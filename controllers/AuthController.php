<?php
include_once '../models/User.php';

class AuthController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    /**
     * Méthode pour connecter un utilisateur.
     *
     * @param string $usernameOrEmail Le nom d'utilisateur ou l'email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return string JSON contenant le statut et les données de l'utilisateur ou un message d'erreur.
     */
    public function login($usernameOrEmail, $password)
    {
        // Validation des entrées
        if (empty($usernameOrEmail)) {
            return json_encode(['status' => 'error', 'message' => 'Le nom d\'utilisateur ou l\'email est requis.']);
        }
        if (empty($password)) {
            return json_encode(['status' => 'error', 'message' => 'Le mot de passe est requis.']);
        }

        // Nettoyage des entrées
        $usernameOrEmail = htmlspecialchars(strip_tags($usernameOrEmail));
        $password = htmlspecialchars(strip_tags($password));

        // Appel du modèle
        $user = $this->userModel->login($usernameOrEmail, $password);
        if ($user) {
            return json_encode(['status' => 'success', 'user' => $user]);
        }
        return json_encode(['status' => 'error', 'message' => 'Nom d\'utilisateur ou mot de passe incorrect.']);
    }

    /**
     * Méthode pour récupérer tous les utilisateurs.
     *
     * @return string JSON contenant le statut et la liste des utilisateurs ou un message d'erreur.
     */
    public function getusers()
    {
        $users = $this->userModel->getAllUsers();
        if ($users) {
            return json_encode(['status' => 'success', 'data' => $users]);
        }
        return json_encode(['status' => 'error', 'message' => 'Aucun utilisateur trouvé.']);
    }

    /**
     * Méthode pour ajouter un utilisateur.
     *
     * @param array $userData Les données de l'utilisateur à ajouter.
     * @return string JSON contenant le statut et un message de succès ou d'erreur.
     */
    public function addUser($userData)
    {
        try {
            // Hashage du mot de passe
            if (isset($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            }

            // Appel du modèle
            $result = $this->userModel->addUser($userData);
            if ($result) {
                return json_encode(['status' => 'success', 'message' => 'Utilisateur ajouté avec succès.']);
            }
            return json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'ajout de l\'utilisateur.']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Méthode pour mettre à jour un utilisateur.
     *
     * @param int $id L'identifiant de l'utilisateur à mettre à jour.
     * @param array $userData Les nouvelles données de l'utilisateur.
     * @return string JSON contenant le statut et un message de succès ou d'erreur.
     */
    public function updateusers($id, $userData)
    {
        try {
            // Hashage du mot de passe si fourni
            if (isset($userData['password'])) {
                $userData['password'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            }

            // Appel du modèle
            $result = $this->userModel->updateusers($id, $userData);
            if ($result) {
                return json_encode(['status' => 'success', 'message' => 'Utilisateur mis à jour avec succès.']);
            }
            return json_encode(['status' => 'error', 'message' => 'Erreur lors de la mise à jour de l\'utilisateur.']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }

    /**
     * Méthode pour supprimer un utilisateur.
     *
     * @param int $id L'identifiant de l'utilisateur à supprimer.
     * @return string JSON contenant le statut et un message de succès ou d'erreur.
     */
    public function deleteusers($id)
    {
        try {
            // Vérification de l'existence de l'utilisateur
            $user = $this->userModel->getUserById($id);
            if (!$user) {
                return json_encode(['status' => 'error', 'message' => 'Utilisateur non trouvé.']);
            }

            // Suppression de l'utilisateur
            $result = $this->userModel->deleteusers($id);
            if ($result) {
                return json_encode(['status' => 'success', 'message' => 'Utilisateur supprimé avec succès.']);
            }
            return json_encode(['status' => 'error', 'message' => 'Erreur lors de la suppression de l\'utilisateur.']);
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => 'Erreur : ' . $e->getMessage()]);
        }
    }
}
?>