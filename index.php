<?php
// nest/index.php
require_once __DIR__ . '/app/config.php';

$page = $_GET['page'] ?? 'home';

// Router simple
switch ($page) {
    case 'home':
        include 'app/views/home.php';
        break;
        
    case 'auth':
        include 'app/views/auth.php';
        break;
        
    case 'dashboard':
        include 'app/views/admin/dashboard.php';
        break;
    
    case 'entities':
        include 'app/views/entities.php';
        break;
    case 'new_create':
        include 'app/views/admin/create_entitie.php';
        break;
    default:
        http_response_code(404);
        echo "<h1>Page non trouvée</h1>";
        break;
}
?>
