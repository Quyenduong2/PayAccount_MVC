<?php
require_once './app/Core/Controller.php';
require_once './app/Models/Account.php';

class CartController extends Controller
{
    public function index()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        $cart = $_SESSION['cart'] ?? [];
        $accounts = [];

        if (!empty($cart)) {
            $accountModel = new Account();
            foreach ($cart as $account_id) {
                $acc = $accountModel->getById($account_id);
                if ($acc) $accounts[] = $acc;
            }
        }

        // Nếu là AJAX thì trả về HTML nhỏ gọn cho modal
        if (isset($_GET['ajax'])) {
            if (empty($accounts)) {
                echo '<p>Giỏ hàng trống.</p>';
            } else {
                echo '<ul class="list-group">';
                foreach ($accounts as $acc) {
                    echo '<li class="list-group-item d-flex justify-content-between align-items-center cart-item">';
                    echo htmlspecialchars($acc['account_title']) . ' - ' . number_format($acc['price']) . ' VND';
                    echo '<a href="' . $basePath . '/cart/remove?id=' . $acc['account_id'] . '" class="btn btn-danger btn-sm btn-remove-cart ms-2">Xóa</a>';
                    echo '</li>';
                }
                echo '</ul>';
            }
            exit;
        }

        $this->view('cart/index', [
            'accounts' => $accounts,
            'basePath' => $basePath
        ]);
    }

    public function add()
    {
        $account_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($account_id) {
            if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
            if (!in_array($account_id, $_SESSION['cart'])) {
                $_SESSION['cart'][] = $account_id;
            }
        }
        if (isset($_GET['ajax'])) {
            echo json_encode(['success' => true]);
            exit;
        }
        header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/cart');
        exit;
    }

    public function remove()
    {
        $account_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($account_id && isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array_diff($_SESSION['cart'], [$account_id]);
        }
        if (isset($_GET['ajax'])) {
            echo json_encode(['success' => true]);
            exit;
        }
        header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/cart');
        exit;
    }
}