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


////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour la connexion (POST /login)
if ($requestMethod === 'POST' && strpos($requestUri, '/login') !== false) {
    // Récupérer les données envoyées en POST
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['usernameOrEmail']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs usernameOrEmail et password sont obligatoires.']);
        exit;
    }
    
    echo $authController->login($data['usernameOrEmail'], $data['password']);
}

// Route pour ajouter un nouvel utilisateur
elseif ($requestMethod === 'POST' && strpos($requestUri, '/users') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Les champs username, email et password sont obligatoires.']);
        exit;
    }
    
    echo $authController->addUser($data);
}

// Route pour récupérer tous les utilisateurs
elseif ($requestMethod === 'GET' && strpos($requestUri, '/users') !== false) {
    echo $authController->getusers();
}

// Route pour mettre à jour un utilisateur
elseif ($requestMethod === 'PUT' && strpos($requestUri, '/users') !== false) {
    // Extraire l'ID utilisateur depuis l'URL
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID utilisateur est obligatoire pour la mise à jour.']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);
    echo $authController->updateusers($id, $data);
}


// Route pour supprimer un utilisateur
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/users') !== false) {
    // Extraire l'ID utilisateur depuis l'URL
    $id = $_GET['id'] ?? null;

    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID utilisateur est obligatoire pour la suppression.']);
        exit;
    }

    echo $authController->deleteusers($id);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Route pour récupérer tous les auteurs (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/auteurs') !== false) {
    $auteurController->getAllAuteurs();
}

// Route pour récupérer un auteur par ID (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $auteurController->getAuteurById($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
    }
}

// Route pour ajouter un auteur (POST)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/auteurs') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    $auteurController->addAuteur($data);
}

// Route pour mettre à jour un auteur (PUT)
elseif ($requestMethod === 'PUT' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);
    if ($id) {
        $auteurController->updateAuteur($id, $data);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
    }
}

// Route pour supprimer un auteur (DELETE)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/auteur') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $auteurController->deleteAuteur($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'auteur est obligatoire']);
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////


// Route pour récupérer toutes les catégories (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/categories') !== false) {
    $categorieController->getAllCategories();
}

// Route pour récupérer une catégorie par ID (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $categorieController->getCategorieById($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
    }
}

// Route pour ajouter une catégorie (POST)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/categories') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    $categorieController->addCategorie($data);
}

// Route pour mettre à jour une catégorie (PUT)
elseif ($requestMethod === 'PUT' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);
    if ($id) {
        $categorieController->updateCategorie($id, $data);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
    }
}

// Route pour supprimer une catégorie (DELETE)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/categorie') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $categorieController->deleteCategorie($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la catégorie est obligatoire']);
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////


// Route pour récupérer toutes les nationalités (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/nationalites') !== false) {
    $nationaliteController->getAllNationalites();
}

// Route pour récupérer une nationalité par ID (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $nationaliteController->getNationaliteById($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
    }
}

// Route pour ajouter une nationalité (POST)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/nationalites') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    $nationaliteController->addNationalite($data);
}

// Route pour mettre à jour une nationalité (PUT)
elseif ($requestMethod === 'PUT' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);
    if ($id) {
        $nationaliteController->updateNationalite($id, $data);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
    }
}

// Route pour supprimer une nationalité (DELETE)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/nationalite') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $nationaliteController->deleteNationalite($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de la nationalité est obligatoire']);
    }
}

/////////////////////////////////////////////////////////////////////////////////////////////////


// Route pour récupérer tous les ouvrages (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/ouvrages') !== false) {
    $ouvrageController->getAllOuvrages();
}

// Route pour récupérer un ouvrage par ID (GET)
elseif ($requestMethod === 'GET' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $ouvrageController->getOuvrageById($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
    }
}

// Route pour ajouter un ouvrage (POST)
elseif ($requestMethod === 'POST' && strpos($requestUri, '/ouvrages') !== false) {
    $data = json_decode(file_get_contents('php://input'), true);
    $ouvrageController->addOuvrage($data);
}

// Route pour mettre à jour un ouvrage (PUT)
elseif ($requestMethod === 'PUT' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;
    $data = json_decode(file_get_contents('php://input'), true);
    if ($id) {
        $ouvrageController->updateOuvrage($id, $data);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
    }
}

// Route pour supprimer un ouvrage (DELETE)
elseif ($requestMethod === 'DELETE' && strpos($requestUri, '/ouvrage') !== false) {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $ouvrageController->deleteOuvrage($id);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'L\'ID de l\'ouvrage est obligatoire']);
    }
}


/////////////////////////////////////////////////////////////////////////////////////////////////


// Route inconnue
else {
    echo json_encode(['error' => 'Route non trouvée']);
}

?>