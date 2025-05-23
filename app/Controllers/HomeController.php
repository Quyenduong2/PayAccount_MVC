<?php
require_once './app/Models/Game.php';
require_once './app/Models/User.php';
require_once './app/Models/Account.php';

class HomeController
{
    private $userModel;
    private $gameModel;
    private $accountModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->gameModel = new Game();
        $this->accountModel = new Account();
    }

    public function index()
    {
        $gameModel = new Game();
        $games = $gameModel->getAllGames();

        // Kiểm tra cookie để lấy thông tin người dùng
        $user = null;
        if (isset($_COOKIE['auth_token'])) {
            $userModel = new User();
            $user = $userModel->getUserByToken($_COOKIE['auth_token']);
        }

        // Định nghĩa basePath
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        require './app/Views/home/index.php';
    }

    public function gameDetail($slug)
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        $user = isset($_COOKIE['auth_token']) ? $this->userModel->getUserByToken($_COOKIE['auth_token']) : false;
        $game = $this->gameModel->getGameBySlug($slug);
        $accounts = $game ? $this->accountModel->getAccountsByGameSlug($slug) : [];
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        if (!$game) {
            error_log("Game not found for slug: $slug");
        } else {
            error_log("Game found: {$game['game_name']}, slug: $slug, accounts: " . count($accounts));
        }

        require './app/Views/games/game_detail.php';
    }

     public function accountDetail($account_id) {
        $user = isset($_COOKIE['auth_token']) ? $this->userModel->getUserByToken($_COOKIE['auth_token']) : false;
        $account = $this->accountModel->getById($account_id);
        $images = $account ? $this->accountModel->getAccountImages($account_id) : [];
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        if (!$account) {
            error_log("Account not found for id: $account_id");
        } else {
            error_log("Account found: {$account['account_title']}, id: $account_id, images: " . count($images));
        }

        require 'app/Views/accounts/account_detail.php';
    }
}
