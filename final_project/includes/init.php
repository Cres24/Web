<?php
declare(strict_types=1);

// Central bootstrap for all pages (sessions, DB, helpers).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

date_default_timezone_set('Asia/Kathmandu');

if (!defined('BASE_URL')) {
    // Get the script's directory depth to calculate the app root
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $scriptParts = array_filter(explode('/', $scriptDir));
    
    // Find "final_project" or the app root (last meaningful directory)
    $base = '/' . end($scriptParts);
    
    // Fallback: if we can't determine, use the dirname
    if ($base === '/' || empty($base) || strlen($base) === 1) {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
    }
    
    define('BASE_URL', $base);
}

function url_path(string $path): string {
    if ($path === '') return BASE_URL . '/';
    if ($path[0] === '/') return $path;
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect(string $path): void {
    header('Location: ' . url_path($path));
    exit();
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (!isset($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function is_logged_in(): bool {
    return isset($_SESSION['user']) && isset($_SESSION['user']['id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        set_flash('error', 'Please login to continue.');
        redirect('Login/login.php');
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

?>

