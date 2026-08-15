<?php
// nest/app/api/projects/list.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../models/Project.php';

$filters = ['is_active' => true];
if (!empty($_GET['category']) && in_array($_GET['category'], ['software', 'electronics', 'iot', 'manufacturing'])) {
    $filters['category'] = $_GET['category'];
}

$projects = Project::all($filters);
echo json_encode(['success' => true, 'projects' => $projects]);
