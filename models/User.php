<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Méthode pour connecter un utilisateur.
     *
     * @param string $usernameOrEmail Le nom d'utilisateur ou l'email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return array|false Les données de l'utilisateur ou false si l'authentification échoue.
     */
    public function login($usernameOrEmail, $password)
    {
        $query = "SELECT * FROM users WHERE email = :usernameOrEmail OR username = :usernameOrEmail";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
        
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Retourner les données de l'utilisateur sans le mot de passe
            unset($user['password']);
            return $user;
        }
        return false;
    }

    /**
     * Méthode pour récupérer tous les utilisateurs.
     *
     * @return array|false La liste des utilisateurs ou false si aucune donnée n'est trouvée.
     */
    public function getAllUsers()
    {
        $query = "SELECT id_user, username, prenom, email, bio, image, date_inscription, id_nationalite FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode pour ajouter un utilisateur.
     *
     * @param array $userData Les données de l'utilisateur à ajouter.
     * @return bool True si l'ajout est réussi, sinon false.
     */
    public function addUser($userData)
    {
        $query = "INSERT INTO users (username, prenom, email, password, bio, image, id_nationalite) 
                  VALUES (:username, :prenom, :email, :password, :bio, :image, :id_nationalite)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':prenom', $userData['prenom']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);
        $stmt->bindParam(':bio', $userData['bio']);
        $stmt->bindParam(':image', $userData['image']);
        $stmt->bindParam(':id_nationalite', $userData['id_nationalite']);

        return $stmt->execute();
    }

    /**
     * Méthode pour mettre à jour un utilisateur.
     *
     * @param int $id L'identifiant de l'utilisateur à mettre à jour.
     * @param array $userData Les nouvelles données de l'utilisateur.
     * @return bool True si la mise à jour est réussie, sinon false.
     */
    public function updateusers($id, $userData)
    {
        $query = "UPDATE users 
                  SET username = :username, prenom = :prenom, email = :email, password = :password, 
                      bio = :bio, image = :image, id_nationalite = :id_nationalite 
                  WHERE id_user = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':prenom', $userData['prenom']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $userData['password']);
        $stmt->bindParam(':bio', $userData['bio']);
        $stmt->bindParam(':image', $userData['image']);
        $stmt->bindParam(':id_nationalite', $userData['id_nationalite']);

        return $stmt->execute();
    }

    /**
     * Méthode pour supprimer un utilisateur.
     *
     * @param int $id L'identifiant de l'utilisateur à supprimer.
     * @return bool True si la suppression est réussie, sinon false.
     */
    public function deleteusers($id)
    {
        $query = "DELETE FROM users WHERE id_user = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Méthode pour récupérer un utilisateur par son ID.
     *
     * @param int $id L'identifiant de l'utilisateur.
     * @return array|false Les données de l'utilisateur ou false si non trouvé.
     */
    public function getUserById($id)
    {
        $query = "SELECT id_user, nom, prenom, email, bio, image, date_inscription, id_nationalite 
                  FROM user WHERE id_user = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>