<?php

header("Access-Control-Allow-Origin: *");
header("Content-type: application/json; charset= UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
echo json_encode(["message" => "Bienvenue à l'API de gestion de la bibliotheque d'ouvrage"]);


if (file_exists('../config/database.php')) {
    include_once '../config/database.php';
} else {
    die('Le fichier Database.php est introuvable.');
}
require_once '../controllers/AuthController.php';
require_once '../controllers/AuteurController.php';
require_once '../controllers/CategorieController.php';
require_once '../controllers/NationaliteController.php';
require_once '../controllers/OuvrageController.php';

// Créer une instance de la base de données
$database = new Database();
$db = $database->connect();

// Passer la connexion à la base de données aux contrôleurs
$authController = new AuthController($db);
$auteurController = new AuteurController($db);
$categorieController = new CategorieController($db);
$nationaliteController = new NationaliteController($db);
$ouvrageController = new OuvrageController($db);

// Vérifiez la méthode et l'URL
$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les utilisateurs
////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour la connexion (POST /login)
if ($requestMethod === 'POST' && strpos($requestUri, '/login') !== false) {
    // Récupérer les données envoyées en POST

    // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);
    
    if (!isset($data['usernameOrEmail']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs usernameOrEmail et password sont obligatoires.']);
        exit;
    }
    
    
    echo $authController->login($data['usernameOrEmail'], $data['password']);
}

// Route pour ajouter un nouvel utilisateur (POST /users)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/users') !== false) {
    // Vérifiez si le répertoire existe, sinon créez-le
    $imageDir = 'images/users/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageName = basename($_FILES['image']['name']); // Sécuriser le nom du fichier
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
    // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Valider les champs obligatoires
    if (empty($data['username']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs username, email et password sont obligatoires.']);
        exit;
    }

    // Ajouter l'utilisateur
    try {
        $response = $authController->addUser($data);
        http_response_code(201);
        echo json_encode(['message' => 'Utilisateur ajouté avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'utilisateur : ' . $e->getMessage()]);
    }
}

// Route pour récupérer tous les utilisateurs (GET /users)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/users') !== false) {
    echo $authController->getUsers();
}
// Route pour récupérer un utilisateur par son ID (GET /user)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/user') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'utilisateur est obligatoire']);
        exit;
    }

    try {
        $response = $authController->getUsersById($id);
        if ($response) {
            echo json_encode(['message' => 'Utilisateur récupéré avec succès', 'data' => $response]);
        } 
        else {
            http_response_code(404);
            echo json_encode(['error' => 'Utilisateur non trouvé']);
         }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage()]);
    }
    exit;
}

// Route pour mettre à jour un utilisateur (PUT /users)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/user') !== false) {
    // Extraire l'ID utilisateur depuis l'URL
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID utilisateur est obligatoire pour la mise à jour.']);
        exit;
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageDir = 'images/users/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Mettre à jour l'utilisateur
    try {
        $response = $authController->updateUser($id, $data);
        echo json_encode(['message' => 'Utilisateur mis à jour avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'utilisateur : ' . $e->getMessage()]);
    }
}

// Route pour supprimer un utilisateur (DELETE /users)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/user') !== false) {
    // Extraire l'ID utilisateur depuis l'URL
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID utilisateur est obligatoire pour la suppression.']);
        exit;
    }

    try {
        $authController->deleteUser($id);
        echo json_encode(['message' => 'Utilisateur supprimé avec succès']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression de l\'utilisateur : ' . $e->getMessage()]);
    }
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les auteurs
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour récupérer tous les auteurs (GET /auteurs)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/auteurs') !== false) {
    try {
        $response = $auteurController->getAllAuteurs();
        echo json_encode(['message' => 'Liste des auteurs récupérée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des auteurs : ' . $e->getMessage()]);
    }
}

// Route pour récupérer un auteur par ID (GET /auteur)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
        exit;
    }

    try {
        $response = $auteurController->getAuteurById($id);
        if ($response) {
            echo json_encode(['message' => 'Auteur récupéré avec succès', 'data' => $response]);
        } 
        // else {
        //     http_response_code(404);
        //     echo json_encode(['error' => 'Auteur non trouvé']);
        // }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération de l\'auteur : ' . $e->getMessage()]);
    }
}

// Route pour ajouter un auteur (POST /auteurs)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/auteurs') !== false) {
    // Vérifiez si le répertoire existe, sinon créez-le
    $imageDir = 'images/auteurs/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageName = basename($_FILES['image']['name']); // Sécuriser le nom du fichier
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
    // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Valider les champs obligatoires
    if (empty($data['nom']) || empty($data['prenom'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs nom et prénom sont obligatoires']);
        exit;
    }

    // Ajouter l'auteur
    try {
        $response = $auteurController->addAuteur($data);
        http_response_code(201);
        echo json_encode(['message' => 'Auteur ajouté avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'auteur : ' . $e->getMessage()]);
    }
}

// Route pour mettre à jour un auteur (PUT /auteur)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
        exit;
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageDir = 'images/auteurs/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Mettre à jour l'auteur
    try {
        $response = $auteurController->updateAuteur($id, $data);
        echo json_encode(['message' => 'Auteur mis à jour avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'auteur : ' . $e->getMessage()]);
    }
}

// Route pour supprimer un auteur (DELETE /auteur)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
        exit;
    }

    try {
        $auteurController->deleteAuteur($id);
        echo json_encode(['message' => 'Auteur supprimé avec succès']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression de l\'auteur : ' . $e->getMessage()]);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les nationalités
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour récupérer toutes les nationalités (GET /nationalites)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/nationalites') !== false) {
    try {
        $response = $nationaliteController->getAllNationalites();
        echo json_encode(['message' => 'Liste des nationalités récupérée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des nationalités : ' . $e->getMessage()]);
    }
}

// Route pour récupérer une nationalité par ID (GET /nationalite)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
        exit;
    }

    try {
        $response = $nationaliteController->getNationaliteById($id);
        if ($response) {
            echo json_encode(['message' => 'Nationalité récupérée avec succès', 'data' => $response]);
        } 
        else {
            http_response_code(404);
            echo json_encode(['error' => 'Nationalité non trouvée']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération de la nationalité : ' . $e->getMessage()]);
    }
}

// Route pour ajouter une nationalité (POST /nationalites)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/nationalites') !== false) {
    // Vérifiez si le répertoire existe, sinon créez-le
    $imageDir = 'images/nationalites/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageName = basename($_FILES['image']['name']); // Sécuriser le nom du fichier
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
    // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Valider les champs obligatoires
    if (empty($data['nom_nationalite'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Le champ nom_nationalite est obligatoire']);
        exit;
    }

    // Ajouter la nationalité
    try {
        $response = $nationaliteController->addNationalite($data);
        http_response_code(201);
        echo json_encode(['message' => 'Nationalité ajoutée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout de la nationalité : ' . $e->getMessage()]);
    }
}

// Route pour mettre à jour une nationalité (PUT /nationalite)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
        exit;
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageDir = 'images/nationalites/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Mettre à jour la nationalité
    try {
        $response = $nationaliteController->updateNationalite($id, $data);
        echo json_encode(['message' => 'Nationalité mise à jour avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la mise à jour de la nationalité : ' . $e->getMessage()]);
    }
}

// Route pour supprimer une nationalité (DELETE /nationalite)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
        exit;
    }

    try {
        $nationaliteController->deleteNationalite($id);
        echo json_encode(['message' => 'Nationalité supprimée avec succès']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression de la nationalité : ' . $e->getMessage()]);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les ouvrages
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour récupérer tous les ouvrages (GET /ouvrages)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/ouvrages') !== false) {
    try {
        $response = $ouvrageController->getAllOuvrages();
        echo json_encode(['message' => 'Liste des ouvrages récupérée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des ouvrages : ' . $e->getMessage()]);
    }
}

// Route pour récupérer un ouvrage par ID (GET /ouvrage)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
        exit;
    }

    try {
        $response = $ouvrageController->getOuvrageById($id);
        if ($response) {
            echo json_encode(['message' => 'Ouvrage récupéré avec succès', 'data' => $response]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Ouvrage non trouvé']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération de l\'ouvrage : ' . $e->getMessage()]);
    }
}

// Route pour ajouter un ouvrage (POST /ouvrages)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/ouvrages') !== false) {
    // Vérifiez si le répertoire existe, sinon créez-le
    $imageDir = 'images/ouvrages/';
    if (!is_dir($imageDir)) {
        mkdir($imageDir, 0777, true);
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageName = basename($_FILES['image']['name']); // Sécuriser le nom du fichier
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Valider les champs obligatoires
    if (empty($data['titre']) || empty($data['auteur_id']) || empty($data['categorie_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs titre, auteur_id et categorie_id sont obligatoires']);
        exit;
    }

    // Ajouter l'ouvrage
    try {
        $response = $ouvrageController->addOuvrage($data);
        http_response_code(201);
        echo json_encode(['message' => 'Ouvrage ajouté avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout de l\'ouvrage : ' . $e->getMessage()]);
    }
}

// Route pour mettre à jour un ouvrage (PUT /ouvrage)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
        exit;
    }

    // Gestion du téléchargement de l'image
    $imagePath = null;
    if (isset($_FILES['image'])) {
        $imageDir = 'images/ouvrages/';
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $imageName = basename($_FILES['image']['name']);
        $imagePath = $imageDir . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors du téléchargement de l\'image']);
            exit;
        }
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Ajouter le chemin de l'image aux données
    if ($imagePath) {
        $data['image'] = $imagePath;
    }

    // Mettre à jour l'ouvrage
    try {
        $response = $ouvrageController->updateOuvrage($id, $data);
        echo json_encode(['message' => 'Ouvrage mis à jour avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la mise à jour de l\'ouvrage : ' . $e->getMessage()]);
    }
}

// Route pour supprimer un ouvrage (DELETE /ouvrage)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
        exit;
    }

    try {
        $ouvrageController->deleteOuvrage($id);
        echo json_encode(['message' => 'Ouvrage supprimé avec succès']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression de l\'ouvrage : ' . $e->getMessage()]);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes pour les catégories
///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour récupérer toutes les catégories (GET /categories)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/categories') !== false) {
    try {
        $response = $categorieController->getAllCategories();
        echo json_encode(['message' => 'Liste des catégories récupérée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération des catégories : ' . $e->getMessage()]);
    }
}

// Route pour récupérer une catégorie par ID (GET /categorie)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
        exit;
    }

    try {
        $response = $categorieController->getCategorieById($id);
        if ($response) {
            echo json_encode(['message' => 'Catégorie récupérée avec succès', 'data' => $response]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Catégorie non trouvée']);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la récupération de la catégorie : ' . $e->getMessage()]);
    }
}

// Route pour ajouter une catégorie (POST /categories)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/categories') !== false) {
    // Décoder les données JSON
    // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Valider les champs obligatoires
    if (empty($data['nom_categorie'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Le champ nom_categorie est obligatoire']);
        exit;
    }

    // Ajouter la catégorie
    try {
        $response = $categorieController->addCategorie($data);
        http_response_code(201);
        echo json_encode(['message' => 'Catégorie ajoutée avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout de la catégorie : ' . $e->getMessage()]);
    }
}

// Route pour mettre à jour une catégorie (PUT /categorie)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
        exit;
    }

    // Décoder les données JSON
   // Récupérer les données JSON si envoyées en raw
$jsonInput = json_decode(file_get_contents("php://input"), true);

// Récupérer les données form-data si envoyées
$formData = $_POST;

if ($jsonInput) {
    $data = $jsonInput;
} elseif (!empty($formData)) {
    $data = $formData;
} else {
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit;
}

echo json_encode(["message" => "Données reçues avec succès", "data" => $data]);

    // Mettre à jour la catégorie
    try {
        $response = $categorieController->updateCategorie($id, $data);
        echo json_encode(['message' => 'Catégorie mise à jour avec succès', 'data' => $response]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la mise à jour de la catégorie : ' . $e->getMessage()]);
    }
}

// Route pour supprimer une catégorie (DELETE /categorie)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
        exit;
    }

    try {
        $categorieController->deleteCategorie($id);
        echo json_encode(['message' => 'Catégorie supprimée avec succès']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la suppression de la catégorie : ' . $e->getMessage()]);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Routes inconnus
///////////////////////////////////////////////////////////////////////////////////////////////////////////////
else {
    echo json_encode(['error' => 'Route non trouvée']);
}

?>