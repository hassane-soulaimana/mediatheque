
<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);
ini_set('display_errors', 0);

/**
 * Point d'entrée principal de l'application PHP MVC
 * 
 * Ce fichier initialise l'application et lance le système de routing
 */

// Démarrer la session
session_start();

// Charger la configuration
require_once '../config/database.php';

// Charger les fichiers core
require_once CORE_PATH . '/database.php';
require_once CORE_PATH . '/router.php';
require_once CORE_PATH . '/view.php';

// Charger les fichiers utilitaires
require_once INCLUDE_PATH . '/helpers.php';

// Charger les modèles
require_once MODEL_PATH . '/user_model.php';
require_once MODEL_PATH . '/media_model.php';
require_once MODEL_PATH . '/admin_model.php';
require_once MODEL_PATH . '/profile_model.php';




// Activer l'affichage des erreurs en développement
// À désactiver en production
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Lancer le système de routing
dispatch();
