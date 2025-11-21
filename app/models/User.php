<?php
// nest/app/models/User.php
require_once __DIR__ . '/../data/database.php';

class User {
    public static function create($name, $email, $password, $accepted_terms = false, $newsletter_subscribed = false) {
        $db = Database::getConnection();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $db->prepare("INSERT INTO users 
            (name, email, password, accepted_terms, newsletter_subscribed, accepted_terms_at, newsletter_subscribed_at, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW(), ?, NOW())");
        
        $newsletter_timestamp = $newsletter_subscribed ? date('Y-m-d H:i:s') : null;
        
        return $stmt->execute([
            $name, 
            $email, 
            $hashedPassword, 
            $accepted_terms ? 1 : 0, 
            $newsletter_subscribed ? 1 : 0,
            $newsletter_timestamp
        ]);
    }
    
    public static function findByEmail($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function findById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, name, email, accepted_terms, newsletter_subscribed, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function updateNewsletterSubscription($user_id, $subscribed) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET newsletter_subscribed = ?, newsletter_subscribed_at = ? WHERE id = ?");
        $timestamp = $subscribed ? date('Y-m-d H:i:s') : null;
        return $stmt->execute([$subscribed ? 1 : 0, $timestamp, $user_id]);
    }

    // Ajoutez ces méthodes dans User.php
    public static function incrementLoginAttempts($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET login_attempts = login_attempts + 1 WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public static function lockAccount($email, $minutes = 15) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET locked_until = DATE_ADD(NOW(), INTERVAL ? MINUTE) WHERE email = ?");
        return $stmt->execute([$minutes, $email]);
    }

    public static function resetLoginAttempts($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE users SET login_attempts = 0, locked_until = NULL WHERE email = ?");
        return $stmt->execute([$email]);
    }

    public static function isAccountLocked($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT locked_until FROM users WHERE email = ? AND locked_until > NOW()");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }

    public static function getRemainingLockTime($email) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT TIMESTAMPDIFF(SECOND, NOW(), locked_until) as remaining_seconds FROM users WHERE email = ? AND locked_until > NOW()");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? max(0, $result['remaining_seconds']) : 0;
    }
}
?>