<?php
require_once './app/Controllers/HomeController.php';
require_once './app/Controllers/AuthController.php';
require_once './app/Controllers/AccountController.php';

// Đơn giản routing dựa trên URL
$base_path = '/BaiTapChuyenDePHP/PayAccount_MVC';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace($base_path, '', $uri);
$uri = rtrim($uri, '/');
$uri = $uri === '' ? '/' : $uri;
$method = $_SERVER['REQUEST_METHOD'];

error_log("URI: $uri, Method: $method"); // Debug

if ($uri === '/' && $method === 'GET') {
    $controller = new HomeController();
    $controller->index();
} elseif ($uri === '/login' && ($method === 'GET' || $method === 'POST')) {
    $controller = new AuthController();
    $controller->login();
} elseif ($uri === '/register' && ($method === 'GET' || $method === 'POST')) {
    $controller = new AuthController();
    $controller->register();
} elseif ($uri === '/logout' && $method === 'GET') {
    error_log("Logout route accessed"); // Debug
    $controller = new AuthController();
    $controller->logout();
} elseif ($uri === '/forgot-password' && ($method === 'GET' || $method === 'POST')) {
    $controller = new AuthController();
    $controller->forgotPassword();
} elseif (preg_match('/^\/profile(\?section=(profile|track-accounts|manage-users|manage-accounts|manage-games|manage-kyc|kyc-submit))?/', $uri) && ($method === 'GET' || $method === 'POST')) {
    error_log("Profile route accessed: $uri"); // Debug
    $controller = new AuthController();
    $controller->profile();
} elseif ($uri === '/sell-account' && ($method === 'GET' || $method === 'POST')) {
    error_log("Sell Account route accessed"); // Debug
    $controller = new AuthController();
    $controller->sellAccount();
} elseif (preg_match('/^\/accounts(\?game_id=\d+)?$/', $uri) && $method === 'GET') {
    error_log("Accounts route accessed: $uri"); // Debug
    $controller = new AccountController();
    $controller->index();
} elseif (preg_match('/^\/games\/([a-z0-9-]+)$/', $uri, $matches) && $method === 'GET') {
    error_log("Game detail route accessed: $uri, slug: {$matches[1]}"); // Debug
    $controller = new HomeController();
    $controller->gameDetail($matches[1]);
} elseif (preg_match('/^\/account\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    error_log("Account detail route accessed: $uri, account_id: {$matches[1]}");
    $controller = new HomeController();
    $controller->accountDetail($matches[1]);
} else {
    error_log("404 Not Found: URI is '$uri', Method: $method"); // Debug
    http_response_code(404);
    echo "404 Not Found: URI is '$uri'";
}
