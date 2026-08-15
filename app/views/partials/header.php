<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo isset($page_title) ? e($page_title) : APP_NAME; ?></title>
    <meta name="description" content="<?php echo APP_NAME; ?> - Ingénierie logicielle (conception et déploiement sur VPS/cloud) et ingénierie électronique (systèmes embarqués, IoT, objets connectés). Premier fabricant d'objets électroniques en Afrique.">
    <meta name="author" content="<?php echo APP_NAME; ?> Corporation">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.4.0/css/all.css">
    <link rel="stylesheet" href="<?php echo css('theme.css'); ?>">
    <?php if (file_exists(PUBLIC_PATH . 'css/bootstrap.min.css')): ?>
    <link rel="stylesheet" href="<?php echo css('bootstrap.min.css'); ?>">
    <?php endif; ?>
    <link rel="icon" type="image/x-icon" href="<?php echo image('favicon.ico'); ?>">
</head>
<body>
<?php include __DIR__ . '/nav.php'; ?>
