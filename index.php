<?php
require_once ('config/database.php');
require_once ('routes/api.php');

// Point d'entrée
header("Content-Type: application/json");
echo json_encode(["message" => "Bienvenue à l'API de gestion de la bibliotheque d'ouvrage"]);
?>