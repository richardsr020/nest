<?php
// nest/app/api/auth/login.php - VERSION CORRIGÉE
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: ' . ($_SERVER['HTTP_ORIGIN'] ?? '*'));
header('Access-Control-Allow-Credentials: true');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/User.php';

// Démarrer la session une seule fois
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (empty($input['email']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
    exit;
}

$email = trim($input['email']);
$password = $input['password'];

try {
    // Find user by email
    $user = User::findByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        echo json_encode([
            'success' => true, 
            'message' => 'Connexion réussie',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    }
    
} catch (Exception $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la connexion']);
}
?>

<?php
// // nest/app/api/auth/login.php
// header('Content-Type: application/json');
// header('Access-Control-Allow-Origin: ' . APP_URL);
// header('Access-Control-Allow-Methods: POST');
// header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

// require_once __DIR__ . '/../../config.php';
// require_once __DIR__ . '/../../models/User.php';

// // Only allow POST requests
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//     http_response_code(405);
//     echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
//     exit;
// }

// // Get JSON input
// $input = json_decode(file_get_contents('php://input'), true);

// // Validate required fields
// if (empty($input['email']) || empty($input['password'])) {
//     http_response_code(400);
//     echo json_encode(['success' => false, 'message' => 'Email et mot de passe requis']);
//     exit;
// }

// $email = trim($input['email']);
// $password = $input['password'];

// try {
//     // Vérifier si le compte est verrouillé
//     if (User::isAccountLocked($email)) {
//         $remainingTime = User::getRemainingLockTime($email);
//         $minutes = ceil($remainingTime / 60);
        
//         http_response_code(423);
//         echo json_encode([
//             'success' => false, 
//             'message' => "Compte temporairement verrouillé. Réessayez dans $minutes minutes.",
//             'locked' => true,
//             'remaining_minutes' => $minutes
//         ]);
//         exit;
//     }

//     // Find user by email
//     $user = User::findByEmail($email);
    
//     if ($user && password_verify($password, $user['password'])) {
//         // Reset login attempts on success
//         User::resetLoginAttempts($email);
        
//         // Mettre à jour la dernière connexion
//         $db = Database::getConnection();
//         $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
//         $stmt->execute([$user['id']]);
        
//         // Set session
//         $_SESSION['user_id'] = $user['id'];
//         $_SESSION['user_name'] = $user['name'];
//         $_SESSION['user_email'] = $user['email'];
//         $_SESSION['user_role'] = $user['role'] ?? 'user';
//         $_SESSION['logged_in'] = true;
//         $_SESSION['login_time'] = time();
        
//         // Regenerate session ID for security
//         session_regenerate_id(true);
        
//         echo json_encode([
//             'success' => true, 
//             'message' => 'Connexion réussie',
//             'user' => [
//                 'id' => $user['id'],
//                 'name' => $user['name'],
//                 'email' => $user['email'],
//                 'role' => $user['role'] ?? 'user'
//             ]
//         ]);
//     } else {
//         // Increment failed attempts
//         if ($user) {
//             User::incrementLoginAttempts($email);
            
//             // Verrouiller le compte après 5 tentatives échouées
//             $login_attempts = $user['login_attempts'] + 1;
//             if ($login_attempts >= 5) {
//                 User::lockAccount($email, 15); // Bloquer 15 minutes
                
//                 http_response_code(423);
//                 echo json_encode([
//                     'success' => false, 
//                     'message' => 'Trop de tentatives échouées. Compte verrouillé pendant 15 minutes.',
//                     'locked' => true,
//                     'remaining_minutes' => 15
//                 ]);
//                 exit;
//             }
//         }
        
//         // Log failed attempt
//         error_log("Failed login attempt for email: " . $email);
        
//         $remaining_attempts = $user ? (5 - $login_attempts) : 5;
//         echo json_encode([
//             'success' => false, 
//             'message' => "Email ou mot de passe incorrect. Tentatives restantes: $remaining_attempts",
//             'remaining_attempts' => $remaining_attempts
//         ]);
//     }
    
// } catch (Exception $e) {
//     error_log("Login error: " . $e->getMessage());
//     echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la connexion']);
// }
?>