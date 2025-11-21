#!/bin/bash

# nest/script.sh
echo "Création de la nouvelle architecture simple..."

# Supprimer l'ancienne structure (sauf les dossiers public)
rm -rf app/controllers/*
rm -rf app/models/*
rm -rf app/views/*
rm -rf app/api/*
rm -f app/router.php
rm -f app/config.php
rm -f app/data/database.php

# Créer la structure des dossiers
mkdir -p app/models
mkdir -p app/views
mkdir -p app/api
mkdir -p app/data

# Créer les fichiers essentiels

# 1. Fichier de configuration
cat > app/config.php << 'EOF'
<?php
// nest/app/config.php
define('DB_HOST', 'localhost');
define('DB_NAME', 'nest_software');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configuration de l'application
define('APP_URL', 'http://localhost/nest');
define('UPLOAD_DIR', __DIR__ . '/../public/uploads/');

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
EOF

# 2. Fichier de base de données
cat > app/data/database.php << 'EOF'
<?php
// nest/app/data/database.php
require_once __DIR__ . '/../config.php';

class Database {
    private static $connection = null;
    
    public static function getConnection() {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
?>
EOF

# 3. Index.php principal
cat > index.php << 'EOF'
<?php
// nest/index.php
require_once __DIR__ . '/app/config.php';

$page = $_GET['page'] ?? 'home';

// Router simple
switch ($page) {
    case 'home':
        include 'app/views/home.php';
        break;
        
    case 'login':
        include 'app/views/login.php';
        break;
        
    case 'register':
        include 'app/views/register.php';
        break;
        
    case 'dashboard':
        include 'app/views/dashboard.php';
        break;
        
    default:
        http_response_code(404);
        echo "<h1>Page non trouvée</h1>";
        break;
}
?>
EOF

# 4. Modèle User
cat > app/models/User.php << 'EOF'
<?php
// nest/app/models/User.php
require_once __DIR__ . '/../data/database.php';

class User {
    public static function create($email, $password, $name) {
        $db = Database::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users (email, password, name) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $hashedPassword, $name]);
    }
    
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
EOF

# 5. API Auth
mkdir -p app/api/auth
cat > app/api/auth/register.php << 'EOF'
<?php
// nest/app/api/auth/register.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    $name = $data['name'] ?? '';
    
    if (empty($email) || empty($password) || empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
        exit;
    }
    
    // Vérifier si l'email existe déjà
    if (User::findByEmail($email)) {
        echo json_encode(['success' => false, 'message' => 'Email déjà utilisé']);
        exit;
    }
    
    // Créer l'utilisateur
    if (User::create($email, $password, $name)) {
        echo json_encode(['success' => true, 'message' => 'Compte créé avec succès']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création']);
    }
}
?>
EOF

cat > app/api/auth/login.php << 'EOF'
<?php
// nest/app/api/auth/login.php
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    $user = User::findByEmail($email);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        
        echo json_encode([
            'success' => true, 
            'message' => 'Connexion réussie',
            'user' => ['id' => $user['id'], 'name' => $user['name']]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email ou mot de passe incorrect']);
    }
}
?>
EOF

# 6. Views simples
cat > app/views/home.php << 'EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Nest Software</title>
    <link rel="stylesheet" href="/nest/public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenue chez Nest Software</h1>
        <p>Votre partenaire en solutions logicielles</p>
        <div>
            <a href="/nest/?page=login" class="btn btn-primary">Connexion</a>
            <a href="/nest/?page=register" class="btn btn-outline-primary">Inscription</a>
        </div>
    </div>
</body>
</html>
EOF

cat > app/views/login.php << 'EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Nest Software</title>
    <link rel="stylesheet" href="/nest/public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Connexion</h2>
                <form id="loginForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/nest/app/api/auth/login.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({email, password})
                });
                
                const data = await response.json();
                const messageDiv = document.getElementById('message');
                
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    setTimeout(() => {
                        window.location.href = '/nest/?page=dashboard';
                    }, 1000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            } catch (error) {
                document.getElementById('message').innerHTML = '<div class="alert alert-danger">Erreur de connexion</div>';
            }
        });
    </script>
</body>
</html>
EOF

cat > app/views/register.php << 'EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Nest Software</title>
    <link rel="stylesheet" href="/nest/public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Inscription</h2>
                <form id="registerForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
                <div id="message" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            try {
                const response = await fetch('/nest/app/api/auth/register.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({name, email, password})
                });
                
                const data = await response.json();
                const messageDiv = document.getElementById('message');
                
                if (data.success) {
                    messageDiv.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                    setTimeout(() => {
                        window.location.href = '/nest/?page=login';
                    }, 2000);
                } else {
                    messageDiv.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                }
            } catch (error) {
                document.getElementById('message').innerHTML = '<div class="alert alert-danger">Erreur d\'inscription</div>';
            }
        });
    </script>
</body>
</html>
EOF

cat > app/views/dashboard.php << 'EOF'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Nest Software</title>
    <link rel="stylesheet" href="/nest/public/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Tableau de bord</h2>
        <p>Bienvenue dans votre espace personnel</p>
        <a href="/nest/?page=home" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</body>
</html>
EOF

# 7. .htaccess
cat > .htaccess << 'EOF'
RewriteEngine On

# Autoriser l'accès direct aux APIs et aux fichiers publics
RewriteCond %{REQUEST_URI} ^/nest/app/api/ [NC]
RewriteCond %{REQUEST_URI} ^/nest/public/ [NC]
RewriteRule ^ - [L]

# Rediriger les autres requêtes vers index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?page=$1 [QSA,L]

# Pour la racine
RewriteRule ^$ index.php?page=home [L]
EOF

# 8. Fichier SQL pour la base de données
cat > app/data/nest-software.sql << 'EOF'
CREATE DATABASE IF NOT EXISTS nest_software;
USE nest_software;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (email, password, name) VALUES 
('admin@nest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrateur');
EOF

echo "✅ Nouvelle architecture créée avec succès!"
echo "📁 Structure:"
echo "   - index.php (routage simple)"
echo "   - app/views/ (vues avec JS intégré)"
echo "   - app/api/ (APIs/contrôleurs JSON)"
echo "   - app/models/ (modèles DB)"
echo "📋 Prochaines étapes:"
echo "   1. Configurez la base de données dans app/config.php"
echo "   2. Importez app/data/nest-software.sql dans MySQL"
echo "   3. Testez l'accès: http://localhost/nest/"