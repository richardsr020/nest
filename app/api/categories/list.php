<?php
// nest/app/api/categories/list.php
require_once __DIR__ . '/../../config.php';

header('Content-Type: application/json');

require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/Product.php';

$categories = Category::all();
$withCounts = Product::countByCategory();

$result = [];
foreach ($categories as $category) {
    $cat = $category;
    $cat['total_products'] = 0;
    foreach ($withCounts as $c) {
        if ($c['id'] == $category['id']) {
            $cat['total_products'] = (int)$c['total'];
            break;
        }
    }
    $result[] = $cat;
}

echo json_encode(['success' => true, 'categories' => $result]);
