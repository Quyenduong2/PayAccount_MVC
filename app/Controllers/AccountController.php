<?php
require_once './app/Models/Account.php';
require_once './app/Models/Game.php';
require_once './app/Core/Controller.php';

class AccountController extends Controller {
    public function index() {
        $game_id = isset($_GET['game_id']) ? (int)$_GET['game_id'] : 1;
        $accountModel = new Account();
        $accounts = $accountModel->getById($game_id);

        $gameModel = new Game();
        $games = $gameModel->getAllGames();
        $game = array_filter($games, function($g) use ($game_id) {
            return $g['game_id'] == $game_id;
        });
        $game = reset($game);

         $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

         $this->view('accounts/account_detail', [
            'accounts' => $accounts,
            'games' => $games,
            'game' => $game,
            'basePath' => $basePath
        ]);
    }
}
?>