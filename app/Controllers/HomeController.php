<?php
require_once './app/Models/Game.php';
require_once './app/Models/User.php';
require_once './app/Models/Account.php';
require_once './app/Core/Controller.php';

class HomeController extends Controller
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

        $this->view('home/index', [
            'games' => $games,
            'user' => $user,
            'basePath' => $basePath
        ]);
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

        $this->view('games/game_detail', [
            'game' => $game,
            'accounts' => $accounts,
            'user' => $user,
            'basePath' => $basePath
        ]);
    }

    public function accountDetail($account_id)
    {
        $user = isset($_COOKIE['auth_token']) ? $this->userModel->getUserByToken($_COOKIE['auth_token']) : false;
        $account = $this->accountModel->getById($account_id);
        $images = $account ? $this->accountModel->getAccountImages($account_id) : [];
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        if (!$account) {
            error_log("Account not found for id: $account_id");
        } else {
            error_log("Account found: {$account['account_title']}, id: $account_id, images: " . count($images));
        }

        $this->view('accounts/account_detail', [
            'account' => $account,
            'images' => $images,
            'user' => $user,
            'basePath' => $basePath
        ]);
    }

    public function search()
{
    $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
    $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
    $games = [];
    if ($keyword !== '') {
        $games = $this->gameModel->getGames(20, $keyword);
    }
    // Nếu là ajax thì chỉ render phần body kết quả
    if (isset($_GET['ajax'])) {
        foreach ($games as $game) {
            echo '<div class="mb-2">';
            echo '<a href="' . $basePath . '/games/' . htmlspecialchars($game['game_slug']) . '">';
            echo '<img src="' . htmlspecialchars($game['game_image']) . '" alt="" style="width:40px;height:40px;object-fit:cover;margin-right:8px;">';
            echo htmlspecialchars($game['game_name']);
            echo '</a>';
            echo '</div>';
        }
        if (empty($games)) {
            echo '<div class="alert alert-warning">Không tìm thấy game phù hợp.</div>';
        }
        exit;
    }
    $this->view('home/index', [
        'games' => $games,
        'keyword' => $keyword,
        'basePath' => $basePath
    ]);
}
}
