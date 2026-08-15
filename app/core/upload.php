<?php
// nest/app/core/upload.php
// Helper d'upload de fichiers (dépend de config.php)

function handleFileUpload($fieldName, $subdir = 'products') {
    if (empty($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'path' => null, 'size' => 0];
    }

    $file = $_FILES[$fieldName];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Erreur lors du téléversement du fichier'];
    }

    $maxSize = 100 * 1024 * 1024; // 100 Mo
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'Fichier trop volumineux (max 100 Mo)'];
    }

    $allowedImages = ['image/png', 'image/jpeg', 'image/svg+xml', 'image/webp', 'image/gif'];
    $allowedFiles = ['application/zip', 'application/x-zip-compressed', 'application/octet-stream', 'application/vnd.android.package-archive', 'application/x-msdownload', 'application/msdownload', 'application/x-msdos-program', 'text/plain'];

    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $imageExts = ['png', 'jpg', 'jpeg', 'svg', 'webp', 'gif'];
    $fileExts = ['zip', 'apk', 'exe', 'msi', 'deb', 'rpm', 'tar', 'gz', 'bin', 'txt'];

    $isImage = in_array($ext, $imageExts);

    if ($isImage) {
        if (!in_array($file['type'], $allowedImages)) {
            return ['success' => false, 'message' => 'Type d\'image non autorisé'];
        }
    } else {
        if (!in_array($ext, $fileExts)) {
            return ['success' => false, 'message' => 'Type de fichier non autorisé'];
        }
    }

    $dir = UPLOAD_PATH . trim($subdir, '/') . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }

    $filename = date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
    $destination = $dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['success' => false, 'message' => 'Impossible d\'enregistrer le fichier'];
    }

    $relativePath = trim($subdir, '/') . '/' . $filename;

    return ['success' => true, 'path' => $relativePath, 'size' => $file['size']];
}
