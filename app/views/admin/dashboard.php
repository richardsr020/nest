<?php
// nest/app/views/admin/dashboard.php
// Protection basique de la page - Version simplifiée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: /nest/?page=login');
    exit;
}

// Vérifier si c'est un admin (vérification basique)
if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'super_admin') {
    header('Location: /nest/?page=home');
    exit;
}

if (!isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin') {
    header('Location: /nest/?page=home');
    exit;
}

$page_title = "Dashboard Admin - " . APP_NAME;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="/nest/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/nest/public/css/fontawesome.css">
    <link rel="stylesheet" href="/nest/public/css/admin.css">
</head>
<body>
    <div class="admin-dashboard">
        <!-- Header Admin -->
        <div class="admin-header">
            <div class="container">
                <div class="admin-nav">
                    <a href="/nest/" class="back-button">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour au site</span>
                    </a>
                    <h1>Dashboard Administrateur</h1>
                    <div class="admin-user">
                        <i class="fas fa-user-shield"></i>
                        <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <button id="logout-btn" class="logout-button" title="Déconnexion">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Admin -->
        <nav class="admin-navbar">
            <div class="container">
                <div class="nav-links">
                    <a href="/nest/?page=admin" class="nav-link active">
                        <i class="fas fa-chart-bar"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="/nest/?page=admin-entities" class="nav-link">
                        <i class="fas fa-cube"></i>
                        <span>Entités</span>
                    </a>
                    <a href="/nest/?page=admin-users" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Utilisateurs</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="container">
            <!-- Statistiques Principales -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="totalUsers">0</h3>
                        <p>Utilisateurs</p>
                    </div>
                </div>

                <div class="stat-card success">
                    <div class="stat-icon">
                        <i class="fas fa-cube"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="totalEntities">0</h3>
                        <p>Entités</p>
                    </div>
                </div>

                <div class="stat-card warning">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="totalDownloads">0</h3>
                        <p>Téléchargements</p>
                    </div>
                </div>

                <div class="stat-card info">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3 id="totalActive">0</h3>
                        <p>Total Actifs</p>
                    </div>
                </div>
            </div>

            <div class="dashboard-content">
                <!-- Graphique et Entités Récents -->
                <div class="content-grid">
                    <!-- Statistiques Récentes -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Activité Récente</h3>
                        </div>
                        <div class="card-body">
                            <div class="stats-chart">
                                <canvas id="activityChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Entités Récents -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Utilisateurs Récents</h3>
                        </div>
                        <div class="card-body">
                            <div class="recent-list" id="recentUsers">
                                <div class="loading-text">Chargement des utilisateurs...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations système -->
                <div class="content-grid">
                    <!-- Informations serveur -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Informations Système</h3>
                        </div>
                        <div class="card-body">
                            <div class="system-info">
                                <div class="info-item">
                                    <span class="info-label">Utilisateur:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Email:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['user_email']); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Rôle:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($_SESSION['user_role'] ?? 'user'); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Session démarrée:</span>
                                    <span class="info-value" id="sessionTime"><?php echo date('H:i:s'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides -->
                    <div class="content-card">
                        <div class="card-header">
                            <h3>Actions Rapides</h3>
                        </div>
                        <div class="card-body">
                            <div class="quick-actions">
                                <button class="action-btn" onclick="window.location.href='/nest/?page=admin-users'">
                                    <i class="fas fa-users"></i>
                                    <span>Gérer les utilisateurs</span>
                                </button>
                                <button class="action-btn" onclick="window.location.href='/nest/?page=admin-entities'">
                                    <i class="fas fa-cube"></i>
                                    <span>Gérer les entités</span>
                                </button>
                                <button class="action-btn" id="refreshStats">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Actualiser les stats</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Gestion de la déconnexion
    document.getElementById('logout-btn').addEventListener('click', async function() {
        if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
            try {
                const response = await fetch('/nest/app/api/auth/logout.php');
                const data = await response.json();
                
                if (data.success) {
                    window.location.href = '/nest/?page=home';
                } else {
                    alert('Erreur lors de la déconnexion');
                }
            } catch (error) {
                console.error('Logout error:', error);
                alert('Erreur de déconnexion');
            }
        }
    });

    // Actualiser les stats
    document.getElementById('refreshStats').addEventListener('click', function() {
        window.location.reload();
    });

    // Mettre à jour l'heure de la session
    function updateSessionTime() {
        const now = new Date();
        document.getElementById('sessionTime').textContent = now.toLocaleTimeString();
    }
    setInterval(updateSessionTime, 1000);

    // Graphique simple d'activité
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Activité',
                    data: [12, 19, 8, 15, 12, 10, 7],
                    borderColor: '#0072ff',
                    backgroundColor: 'rgba(0, 114, 255, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Charger les utilisateurs récents
        loadRecentUsers();
    });

    async function loadRecentUsers() {
        try {
            // Pour l'instant, on simule des données
            const users = [
                { name: 'Jean Dupont', email: 'jean@example.com', joined: '2024-01-15' },
                { name: 'Marie Martin', email: 'marie@example.com', joined: '2024-01-14' },
                { name: 'Pierre Lambert', email: 'pierre@example.com', joined: '2024-01-13' }
            ];

            const container = document.getElementById('recentUsers');
            container.innerHTML = users.map(user => `
                <div class="recent-item">
                    <div class="item-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="item-content">
                        <h4>${user.name}</h4>
                        <span class="item-meta">${user.email}</span>
                    </div>
                    <div class="item-badge">
                        ${new Date(user.joined).toLocaleDateString('fr-FR')}
                    </div>
                </div>
            `).join('');
        } catch (error) {
            console.error('Error loading users:', error);
            document.getElementById('recentUsers').innerHTML = '<div class="error-text">Erreur de chargement</div>';
        }
    }
    </script>

    <style>
    .system-info {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }
    .info-label {
        font-weight: 600;
        color: #333;
    }
    .info-value {
        color: #666;
    }
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .action-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        background: #0072ff;
        color: white;
        border-color: #0072ff;
    }
    .logout-button {
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 5px;
        margin-left: 10px;
    }
    .logout-button:hover {
        color: #ff4757;
    }
    </style>
</body>
</html>