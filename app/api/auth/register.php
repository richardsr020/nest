<?php
// nest/app/api/auth/register.php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../models/User.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate required fields
$required_fields = ['name', 'email', 'password'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Tous les champs obligatoires sont requis']);
        exit;
    }
}

$name = trim($input['name']);
$email = trim($input['email']);
$password = $input['password'];
$accepted_terms = isset($input['accepted_terms']) ? (bool)$input['accepted_terms'] : false;
$newsletter_subscribed = isset($input['newsletter_subscribed']) ? (bool)$input['newsletter_subscribed'] : false;

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Adresse email invalide']);
    exit;
}

// Validate name length
if (strlen($name) < 2) {
    echo json_encode(['success' => false, 'message' => 'Le nom doit contenir au moins 2 caractères']);
    exit;
}

// Validate password strength
if (strlen($password) < 8) {
    echo json_encode(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 8 caractères']);
    exit;
}

// Check if terms are accepted
if (!$accepted_terms) {
    echo json_encode(['success' => false, 'message' => 'Vous devez accepter les conditions d\'utilisation']);
    exit;
}

try {
    // Check if email already exists
    $existingUser = User::findByEmail($email);
    if ($existingUser) {
        echo json_encode(['success' => false, 'message' => 'Cette adresse email est déjà utilisée']);
        exit;
    }

    // Create new user with additional fields.
    // Le PREMIER utilisateur inscrit devient automatiquement administrateur.
    $isFirstUser = (User::countAll() === 0);
    $role = $isFirstUser ? 'super_admin' : 'user';
    $result = User::create($name, $email, $password, $accepted_terms, $newsletter_subscribed, $role);
    
    if ($result) {
        // Get the newly created user
        $user = User::findByEmail($email);
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? $role;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();

        // Log the registration for analytics
        error_log("New user registered: " . $email . " | Role: " . ($isFirstUser ? 'super_admin (premier utilisateur)' : 'user') . " | Newsletter: " . ($newsletter_subscribed ? 'yes' : 'no'));
        
        echo json_encode([
            'success' => true, 
            'message' => $isFirstUser
                ? 'Compte créé avec succès ! Vous êtes le premier utilisateur : vous êtes maintenant administrateur.'
                : 'Compte créé avec succès!',
            'is_admin' => $isFirstUser,
            'role' => $role,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $role,
                'newsletter_subscribed' => $newsletter_subscribed
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du compte']);
    }
    
} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la création du compte']);
}
?>