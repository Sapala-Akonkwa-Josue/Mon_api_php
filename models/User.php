<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Trouver un utilisateur par son nom d'utilisateur ou son e-mail
     */
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function findByUsernameOrEmail($usernameOrEmail) {
        try {
            // Préparer la requête SQL
            $query = "SELECT * FROM users WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
            $stmt = $this->db->prepare($query);

            // Binder les paramètres
            $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);

            // Exécuter la requête
            $stmt->execute();

            // Récupérer l'utilisateur
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Journaliser l'erreur
            error_log("Erreur lors de la recherche de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Connecter un utilisateur
     */
    ///////////////////////////////////////////////////////////////////////////////////////////////////////
    public function login($usernameOrEmail, $password) {
        try {
            // Trouver l'utilisateur par son nom d'utilisateur ou son e-mail
            $user = $this->findByUsernameOrEmail($usernameOrEmail);

            // Vérifier si l'utilisateur existe et si le mot de passe correspond
            if ($user && password_verify($password, $user['password'])) {
                // Retourner les données de l'utilisateur (sans le mot de passe)
                unset($user['password']);
                return $user;
            } else {
                // Retourner false si l'utilisateur n'existe pas ou si le mot de passe est incorrect
                return false;
            }
        } catch (PDOException $e) {
            // Journaliser l'erreur
            error_log("Erreur lors de la tentative de connexion : " . $e->getMessage());
            return false;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
      /**
     * Ajouter un nouvel utilisateur avec gestion de l'image.
     */
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function addUser($userData) {
        try {
            $query = "INSERT INTO users (username, prenom, email, password, bio, image, id_nationalite) 
                      VALUES (:username, :prenom, :email, :password, :bio, :image, :id_nationalite)";
            $stmt = $this->db->prepare($query);
    
            // Hash du mot de passe avant de lier la valeur
            $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);
    
            // Liaison des paramètres avec les variables
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':prenom', $userData['prenom']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':password', $hashedPassword);  // Passer la variable
            $stmt->bindParam(':bio', $userData['bio']);
            $stmt->bindParam(':image', $userData['image']);
            $stmt->bindParam(':id_nationalite', $userData['id_nationalite']);
    
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Récupérer tous les utilisateurs.
     */
    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getAllUsers() {
       try{
        $query = "SELECT id_user, username, prenom, email, bio, image, date_inscription, id_nationalite FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Retourner les utilisateurs
        return $users;
       }catch (PDOException $e) {
        // Journaliser l'erreur
        error_log("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
        return false;
    }
}
     ///////////////////////////////////////////////////////////////////////////////////////////////////////////
     /**
     * Récupérer un utilisateur par son ID.
     */
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getUsersById($id) {
        try {
            $query = "SELECT id_user, username, prenom, email, bio, image, date_inscription, id_nationalite 
                  FROM users WHERE id_user = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Journaliser l'erreur
        error_log("Erreur lors de la récupération l' utilisateur par ID : " . $e->getMessage());
        return false;
        }
    }
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Mettre à jour un utilisateur avec gestion de l'image.
     */
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function updateUser($id, $userData) {
        try {
            $user = $this->getUserById($id);
            if ($user && !empty($user['image']) && $user['image'] !== $userData['image']) {
                if (file_exists($user['image'])) {
                    unlink($user['image']);
                }
            }

            $query = "UPDATE users 
                      SET username = :username, prenom = :prenom, email = :email, bio = :bio, 
                          image = :image, id_nationalite = :id_nationalite 
                      WHERE id_user = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':username', $userData['username']);
            $stmt->bindParam(':prenom', $userData['prenom']);
            $stmt->bindParam(':email', $userData['email']);
            $stmt->bindParam(':bio', $userData['bio']);
            $stmt->bindParam(':image', $userData['image']);
            $stmt->bindParam(':id_nationalite', $userData['id_nationalite']);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }
   
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Supprimer un utilisateur avec suppression de son image.
     */
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function deleteUser($id) {
        try {
            $user = $this->getUserById($id);
            if ($user && !empty($user['image'])) {
                if (file_exists($user['image'])) {
                    unlink($user['image']);
                }
            }

            $query = "DELETE FROM users WHERE id_user = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la suppression de l'utilisateur : " . $e->getMessage());
            return false;
        }
    }   
}
?>
