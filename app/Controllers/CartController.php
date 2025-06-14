<?php
require_once './app/Core/Controller.php';
require_once './app/Models/Account.php';

require_once __DIR__ . '/../../vendor/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        // AJAX trả về HTML nhỏ gọn cho modal
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
                echo '<a href="' . $basePath . '/cart/details" class="btn btn-primary mt-3 w-100 btn-cart-details">Xem chi tiết & Thanh toán</a>';
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
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user'])) {
            // Nếu là AJAX, trả về JSON báo lỗi
            if (isset($_GET['ajax'])) {
                echo json_encode(['success' => false, 'redirect' => '/BaiTapChuyenDePHP/PayAccount_MVC/login']);
                exit;
            }
            // Nếu không phải AJAX, chuyển hướng về trang đăng nhập
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/login');
            exit;
        }

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

    // Hiển thị chi tiết giỏ hàng (có ảnh, mô tả, giá, nút thanh toán)
    public function details()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        $cart = $_SESSION['cart'] ?? [];
        $accountModel = new Account();
        $accounts = [];
        foreach ($cart as $id) {
            $acc = $accountModel->getById($id);
            if (!$acc) continue;

            // Lấy ảnh account (ưu tiên account_images)
            $images = $accountModel->getAccountImages($id);
            if (!empty($images) && !empty($images[0]['image_path'])) {
                $img = ltrim($images[0]['image_path'], '/');
                $acc['image_url'] = $basePath . '/' . $img;
            } else {
                // Nếu không có ảnh account, lấy ảnh game
                $gameImage = null;
                // Lấy game_id từ $acc
                if (!empty($acc['game_id'])) {
                    // Lấy model Game
                    require_once './app/Models/Game.php';
                    $gameModel = new Game();
                    $game = $gameModel->getGameById($acc['game_id']);
                    if ($game && !empty($game['game_image'])) {
                        $gameImage = ltrim($game['game_image'], '/');
                    }
                }
                if ($gameImage) {
                    $acc['image_url'] = $basePath . '/' . $gameImage;
                } else {
                    $acc['image_url'] = $basePath . '/public/images/placeholder.png';
                }
            }
            $accounts[] = $acc;
        }

        // Lấy user từ session hoặc cookie
        $user = null;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        // Nếu không phải AJAX, render view chi tiết (nếu muốn)
        $this->view('cart/details', [
            'accounts' => $accounts,
            'basePath' => $basePath,
            'user'     => $user
        ]);
    }

    // Trang checkout + xử lý POST
    public function checkout()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cart = $_SESSION['cart'] ?? [];
            $accountModel = new Account();
            $purchased = [];
            foreach ($cart as $id) {
                $acc = $accountModel->getById($id);
                if ($acc) {
                    $purchased[] = [
                        'title'    => $acc['account_title'],
                        'username' => $acc['username'] ?? '',
                        'password' => $acc['password'] ?? ''
                    ];
                }
            }
            // Lấy email và tên người dùng đăng nhập
            $userEmail = '';
            $userName  = '';
            if (isset($_SESSION['user'])) {
                $userEmail = $_SESSION['user']['email'];
                $userName  = $_SESSION['user']['full_name'] ?? '';
            } elseif (isset($_POST['guest_email'])) {
                $userEmail = $_POST['guest_email'];
                $userName  = 'Khách hàng';
            }
            if (isset($_SESSION['user'])) {
                $userEmail = $_SESSION['user']['email'];
                $userName  = $_SESSION['user']['full_name'] ?? '';
            } elseif (isset($_COOKIE['auth_token'])) {
                require_once './app/Models/User.php';
                $userModel = new User();
                $user = $userModel->getUserByToken($_COOKIE['auth_token']);
                if ($user && !empty($user['email'])) {
                    $userEmail = $user['email'];
                    $userName  = $user['full_name'] ?? '';
                }
            }

            // Luôn lấy email từ input
            $userEmail = $_POST['guest_email'] ?? '';
            $userName  = 'Khách hàng';


            // Gửi email nếu có email và có tài khoản đã mua
            if ($userEmail && !empty($purchased)) {
                $body = "Cảm ơn bạn đã đặt hàng tại PayAccount!<br>Dưới đây là thông tin tài khoản bạn đã mua:<br>";
                foreach ($purchased as $acc) {
                    $body .= "<hr><b>Tên tài khoản:</b> " . htmlspecialchars($acc['title']) . "<br>";
                    $body .= "<b>Username:</b> " . htmlspecialchars($acc['username']) . "<br>";
                    $body .= "<b>Password:</b> " . htmlspecialchars($acc['password']) . "<br>";
                }
                $body .= "<br>Chúc bạn trải nghiệm vui vẻ!<br>PayAccount Team";
                $this->sendMail($userEmail, $userName, 'Xác nhận đặt hàng PayAccount', $body);
            }
            // Xóa giỏ hàng, lưu tài khoản đã mua
            $_SESSION['cart'] = [];
            $_SESSION['purchased'] = $purchased;



            header('Location: ' . $basePath . '/cart/confirm');
            exit;
        }


        $user = null;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        $this->view('cart/checkout', [
            'basePath' => $basePath,
            'user'     => $user
        ]);
    }

    public function confirm()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        $accounts = isset($_SESSION['purchased']) && is_array($_SESSION['purchased']) ? $_SESSION['purchased'] : [];


        // Lấy user từ session hoặc cookie
        $user = null;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
        }

        $this->view('cart/confirm', [
            'accounts' => $accounts,
            'basePath' => $basePath,
            'user'     => $user
        ]);
    }

    private function sendMail($toEmail, $toName, $subject, $bodyHtml)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2; // Bật debug chi tiết
            $mail->Debugoutput = function ($str, $level) {
                error_log("SMTP Debug level $level: $str");
            };

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dgiaquyen40@gmail.com'; // Thay bằng email của bạn
            $mail->Password = 'zdnlgzpjkqvxmskb'; // Thay bằng App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('dgiaquyen40@gmail.com', 'PayAccount');
            $mail->addAddress($toEmail, $toName);
            $mail->CharSet = 'UTF-8';
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $bodyHtml;
            $mail->AltBody = strip_tags($bodyHtml);

            $mail->send();
            error_log("Email sent successfully to $toEmail");
        } catch (Exception $e) {
            error_log("Failed to send email to $toEmail: {$mail->ErrorInfo}");
        }
    }
}
