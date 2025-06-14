<?php
require_once './app/Models/User.php';
require_once './app/Models/Account.php';
require_once './app/Models/Game.php';

require_once __DIR__ . '/../../vendor/PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../../vendor/PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../../vendor/PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AuthController
{
    public function login()
    {

        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        // Chỉ xử lý POST từ form đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'], $_POST['password']) && !isset($_POST['username'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $remember = isset($_POST['remember']) ? true : false;

            error_log("Login attempt: email=$email"); // Debug

            $userModel = new User();
            $user = $userModel->login($email, $password);

            if ($user) {
                $cookie_duration = $remember ? (30 * 24 * 60 * 60) : (24 * 60 * 60);
                setcookie('auth_token', $user['auth_token'], time() + $cookie_duration, '/BaiTapChuyenDePHP/PayAccount_MVC/', '', false, true);
                header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/');
                error_log("Login successful for email: $email"); // Debug
                $_SESSION['user'] = [
                    'user_id'   => $user['user_id'],
                    'email'     => $user['email'],
                    'full_name' => $user['full_name']
                ];
                $this->sendMail(
                    $user['email'],
                    $user['full_name'],
                    'Đăng nhập PayAccount',
                    "Chào {$user['full_name']},<br>Bạn vừa đăng nhập vào PayAccount.<br>Nếu không phải bạn, hãy đổi mật khẩu ngay!"
                );
                exit;
            } else {
                $error = "Email hoặc mật khẩu không đúng.";
            }
        }
        error_log("Login page accessed, Method: {$_SERVER['REQUEST_METHOD']}, POST: " . print_r($_POST, true)); // Debug

        require './app/Views/Auth/login.php';
    }
    public function register()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        error_log("Register page accessed, Method: {$_SERVER['REQUEST_METHOD']}, POST: " . print_r($_POST, true)); // Debug

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['full_name'], $_POST['password'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $full_name = $_POST['full_name'];
            $password = $_POST['password'];

            error_log("Register attempt: username=$username, email=$email, full_name=$full_name"); // Debug

            $userModel = new User();
            if ($userModel->register($username, $email, $full_name, $password)) {
                // Gửi mail xác nhận đăng ký
                $this->sendMail(
                    $email,
                    $full_name,
                    'Xác nhận đăng ký PayAccount',
                    "Chào $full_name,<br>Cảm ơn bạn đã đăng ký tài khoản tại PayAccount.<br>Chúc bạn trải nghiệm vui vẻ!"
                );
                header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/login');
                exit;
            } else {
                $error = "Đăng ký thất bại. Email hoặc tên đăng nhập có thể đã tồn tại.";
                error_log("Register failed: email=$email, error=$error");
            }
        }

        require './app/Views/Auth/register.php';
    }

    private function sendMail($toEmail, $toName, $subject, $bodyHtml)
    {
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = function ($str, $level) {
                error_log("SMTP Debug level $level: $str");
            };

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'dgiaquyen40@gmail.com'; // Thay bằng email của bạn
            $mail->Password = 'zdnlgzpjkqvx mskb'; // Thay bằng App Password
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
    }public function logout()
{
    $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
    if (session_status() === PHP_SESSION_NONE) session_start();
    session_unset();
    session_destroy();

    // Xóa cookie đăng nhập nếu có
    if (isset($_COOKIE['auth_token'])) {
        setcookie('auth_token', '', time() - 3600, '/');
    }
    header('Location: ' . $basePath . '/');
    exit;
}
  

    public function forgotPassword()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'] ?? null;
            $userModel = new User();
            $user = $userModel->getUserByEmail($email);
            if ($user) {
                if ($password) {
                    // Cập nhật mật khẩu mới
                    if ($userModel->updatePassword($email, $password)) {
                        $this->sendMail(
                            $email,
                            $user['full_name'],
                            'Đặt lại mật khẩu PayAccount',
                            "Chào {$user['full_name']},<br>Bạn vừa đặt lại mật khẩu thành công.<br>Nếu không phải bạn, hãy liên hệ hỗ trợ!"
                        );
                        $success = "Mật khẩu đã được cập nhật. Vui lòng đăng nhập.";
                    } else {
                        $error = "Cập nhật mật khẩu thất bại.";
                    }
                }
            } else {
                $error = "Email không tồn tại.";
            }
        }
        require './app/Views/Auth/forgot_password.php';
    }

    public function profile()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';

        if (!isset($_COOKIE['auth_token'])) {
            error_log("No auth_token cookie found");
            header('Location: ' . $basePath . '/login ');
            exit;
        }
        $userModel = new User();
        $user = $userModel->getUserByToken($_COOKIE['auth_token']);
        if (!$user || !isset($user['user_id'])) {
            error_log("Invalid user or missing user id for token: " . ($_COOKIE['auth_token'] ?? 'empty'));
            setcookie('auth_token', '', time() - 3600, '/BaiTapChuyenDePHP/PayAccount_MVC/');
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/login');
            exit;
        }

        $section = $_GET['section'] ?? ($user['role'] === 'admin' ? 'dashboard' : 'profile');
        $users = [];
        $accounts = [];
        $user_accounts = [];
        $games = [];
        $kyc_requests = [];
        $success = $_SESSION['success'] ?? ''; // Lấy thông báo từ session
        $error = $_SESSION['error'] ?? '';
        unset($_SESSION['success'], $_SESSION['error']); // Xóa sau khi lấy
        $kyc_status = $userModel->getKycStatus($user['user_id']);

        if ($user['role'] === 'admin') {
            $users = $userModel->getAllUsers() ?? [];
            $accountModel = new Account();
            $accounts = $accountModel->getAll() ?? [];
            $gameModel = new Game();
            $games = $gameModel->getAllGames() ?? [];
            $kyc_requests = $userModel->getKycRequests() ?? [];

            // Quản lý trò chơi
            if ($section === 'manage-games') {
                error_log("Manage games accessed, action: " . ($_GET['action'] ?? 'none'));
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create_game') {
                    $game_name = $_POST['game_name'] ?? '';
                    $game_slug = $_POST['game_slug'] ?? '';
                    $game_image = null;
                    if (isset($_FILES['game_image']) && $_FILES['game_image']['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = 'public/uploads/games/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        $game_image = $upload_dir . uniqid() . '_' . basename($_FILES['game_image']['name']);
                        if (!move_uploaded_file($_FILES['game_image']['tmp_name'], $game_image)) {
                            $error = 'Tải ảnh lên thất bại.';
                            error_log("Create game failed: Image upload error");
                        }
                    }
                    // Tạo game_slug tự động nếu không có
                    if (empty($game_slug)) {
                        $game_slug = strtolower(str_replace(' ', '-', trim($game_name)));
                    }
                    error_log("Create game attempt: game_name=$game_name, game_slug=$game_slug, game_image=$game_image");
                    if (empty($game_name) || empty($game_slug)) {
                        $error = 'Vui lòng điền tên trò chơi và slug.';
                        error_log("Create game failed: Missing game_name or game_slug");
                    } else {
                        $result = $gameModel->create($game_name, $game_slug, $game_image);
                        if ($result) {
                            $success = 'Thêm trò chơi thành công.';
                            error_log("Create game success");
                        } else {
                            $error = 'Thêm trò chơi thất bại.';
                            error_log("Create game failed: Model create returned false");
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit_game' && isset($_GET['id'])) {
                    $game_id = $_GET['id'];
                    $game_name = $_POST['game_name'] ?? '';
                    $game_slug = $_POST['game_slug'] ?? '';
                    $game_image = $gameModel->getGameById($game_id)['game_image'] ?? null;
                    if (isset($_FILES['game_image']) && $_FILES['game_image']['error'] === UPLOAD_ERR_OK) {
                        $upload_dir = 'public/uploads/games/';
                        if (!is_dir($upload_dir)) {
                            mkdir($upload_dir, 0777, true);
                        }
                        $game_image = $upload_dir . uniqid() . '_' . basename($_FILES['game_image']['name']);
                        if (!move_uploaded_file($_FILES['game_image']['tmp_name'], $game_image)) {
                            $error = 'Tải ảnh lên thất bại.';
                            error_log("Edit game failed: Image upload error for game_id=$game_id");
                        }
                    }
                    // Tạo game_slug tự động nếu không có
                    if (empty($game_slug)) {
                        $game_slug = strtolower(str_replace(' ', '-', trim($game_name)));
                    }
                    error_log("Edit game attempt: game_id=$game_id, game_name=$game_name, game_slug=$game_slug, game_image=$game_image");
                    if (empty($game_name) || empty($game_slug)) {
                        $error = 'Vui lòng điền tên trò chơi và slug.';
                        error_log("Edit game failed: Missing game_name or game_slug");
                    } else {
                        $result = $gameModel->update($game_id, $game_name, $game_slug, $game_image);
                        if ($result) {
                            $success = 'Sửa trò chơi thành công.';
                            error_log("Edit game success: game_id=$game_id");
                        } else {
                            $error = 'Sửa trò chơi thất bại.';
                            error_log("Edit game failed: Model update returned false for game_id=$game_id");
                        }
                    }
                } elseif (isset($_GET['action']) && $_GET['action'] === 'delete_game' && isset($_GET['id'])) {
                    $game_id = $_GET['id'];
                    error_log("Delete game attempt: game_id=$game_id");
                    $result = $gameModel->delete($game_id);
                    if ($result) {
                        $success = 'Xóa trò chơi thành công.';
                        error_log("Delete game success");
                    } else {
                        $error = 'Xóa trò chơi thất bại.';
                        error_log("Delete game failed: Model delete returned false for game_id=$game_id");
                    }
                }
            }

            // Quản lý tài khoản
            if ($section === 'manage-accounts') {
                error_log("Manage accounts accessed, action: " . ($_GET['action'] ?? 'none'));
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_account') {
                    $game_id = $_POST['game_id'] ?? '';
                    $user_id = $_POST['user_id'] ?? '';
                    $account_title = $_POST['account_title'] ?? '';
                    $price = $_POST['price'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $facebook_link = $_POST['facebook_link'] ?? null;
                    $zalo_link = $_POST['zalo_link'] ?? null;
                    error_log("Add account attempt: game_id=$game_id, user_id=$user_id, title=$account_title, price=$price");
                    if (empty($game_id) || empty($user_id) || empty($account_title) || empty($price) || empty($description)) {
                        $error = 'Vui lòng điền đầy đủ thông tin.';
                        error_log("Add account failed: Missing required fields");
                    } else {
                        $result = $accountModel->create($user_id, $game_id, $account_title, $price, $description, $facebook_link, $zalo_link);
                        if ($result) {
                            $success = 'Thêm tài khoản thành công.';
                            error_log("Add account success");
                        } else {
                            $error = 'Thêm tài khoản thất bại.';
                            error_log("Add account failed: Model create returned false");
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit_account' && isset($_GET['id'])) {
                    $account_id = $_GET['id'];
                    $account_title = $_POST['account_title'] ?? '';
                    $price = $_POST['price'] ?? '';
                    $description = $_POST['description'] ?? '';
                    $status = $_POST['status'] ?? 'pending';
                    error_log("Edit account attempt: account_id=$account_id, title=$account_title, price=$price, status=$status");
                    if (empty($account_title) || empty($price) || empty($description)) {
                        $error = 'Vui lòng điền đầy đủ thông tin.';
                        error_log("Edit account failed: Missing required fields");
                    } elseif (!in_array($status, ['pending', 'approved', 'sold', 'rejected'])) {
                        $error = 'Trạng thái không hợp lệ.';
                        error_log("Edit account failed: Invalid status=$status");
                    } else {
                        $result = $accountModel->update($account_id, $account_title, $price, $description, $status);
                        if ($result) {
                            $success = 'Sửa tài khoản thành công.';
                            error_log("Edit account success: account_id=$account_id");
                        } else {
                            $error = 'Sửa tài khoản thất bại.';
                            error_log("Edit account failed: Model update returned false for account_id=$account_id");
                        }
                    }
                } elseif (isset($_GET['action']) && $_GET['action'] === 'delete_account' && isset($_GET['id'])) {
                    $account_id = $_GET['id'];
                    error_log("Delete account attempt: account_id=$account_id");
                    $result = $accountModel->delete($account_id);
                    if ($result) {
                        $success = 'Xóa tài khoản thành công.';
                        error_log("Delete account success");
                    } else {
                        $error = 'Xóa tài khoản thất bại.';
                        error_log("Delete account failed: Model delete returned false");
                    }
                }
            }

            // Quản lý người dùng
            if ($section === 'manage-users') {
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'add_user') {
                    $username = $_POST['username'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $password = $_POST['password'] ?? '';
                    $role = $_POST['role'] ?? 'user';
                    if (empty($username) || empty($email) || empty($password)) {
                        $error = 'Vui lòng điền đầy đủ thông tin.';
                    } elseif (!in_array($role, ['user', 'admin'])) {
                        $error = 'Vai trò không hợp lệ.';
                    } else {
                        if ($userModel->register($username, $email, $password, $role)) {
                            $success = 'Thêm người dùng thành công.';
                        } else {
                            $error = 'Thêm người dùng thất bại.';
                        }
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'edit_user' && isset($_GET['id'])) {
                    $user_id = $_GET['id'];
                    $username = $_POST['username'] ?? '';
                    $email = $_POST['email'] ?? '';
                    $role = $_POST['role'] ?? 'user';
                    $full_name = $_POST['full_name'] ?? null;
                    if (empty($username) || empty($email)) {
                        $error = 'Vui lòng điền đầy đủ thông tin.';
                    } elseif (!in_array($role, ['user', 'admin'])) {
                        $error = 'Vai trò không hợp lệ.';
                    } else {
                        if ($userModel->updateUser($user_id, $username, $email, $role, $full_name)) {
                            $success = 'Sửa người dùng thành công.';
                        } else {
                            $error = 'Sửa người dùng thất bại.';
                        }
                    }
                } elseif (isset($_GET['action']) && $_GET['action'] === 'delete_user' && isset($_GET['id'])) {
                    $user_id = $_GET['id'];
                    if ($user_id == $user['user_id']) {
                        $error = 'Không thể xóa tài khoản của chính bạn.';
                    } elseif ($userModel->deleteUser($user_id)) {
                        $success = 'Xóa người dùng thành công.';
                    } else {
                        $error = 'Xóa người dùng thất bại.';
                    }
                }
            }

            // Quản lý KYC
            if ($section === 'manage-kyc') {
                if (isset($_GET['action'], $_GET['id'])) {
                    $request_id = $_GET['id'];
                    $action = $_GET['action'];
                    if ($action === 'approve_kyc' || $action === 'reject_kyc') {
                        $status = $action === 'approve_kyc' ? 'approved' : 'rejected';
                        if ($userModel->updateKycStatus($request_id, $status, $user['user_id'])) {
                            $success = 'Cập nhật trạng thái KYC thành công.';
                        } else {
                            $error = 'Cập nhật trạng thái KYC thất bại.';
                        }
                    }
                    $kyc_requests = $userModel->getKycRequests();
                }
            }
        } elseif ($user['role'] === 'user' && $section === 'track-accounts') {
            $accountModel = new Account();
            $user_accounts = $accountModel->getByUserId($user['user_id']);
        }

        if ($section === 'kyc-submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $kyc_full_name = $_POST['kyc_full_name'] ?? '';
            $id_number = $_POST['id_number'] ?? '';
            if (isset($_FILES['id_image']) && $_FILES['id_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = 'public/uploads/kyc/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                $id_image = $upload_dir . uniqid() . '_' . basename($_FILES['id_image']['name']);
                if (move_uploaded_file($_FILES['id_image']['tmp_name'], $id_image)) {
                    if ($userModel->submitKycRequest($user['user_id'], $kyc_full_name, $id_number, $id_image)) {
                        $success = 'Yêu cầu KYC đã được gửi, chờ admin xét duyệt.';
                        $kyc_status = 'pending';
                    } else {
                        $error = 'Gửi yêu cầu KYC thất bại.';
                    }
                } else {
                    $error = 'Tải ảnh lên thất bại.';
                }
            } else {
                $error = 'Vui lòng tải lên ảnh CMND/CCCD.';
            }
            $section = 'profile';
        }

        error_log("Profile page rendered, user: {$user['email']}, role: {$user['role']}, section: $section, success: '$success', error: '$error'");
        require './app/Views/Auth/profile.php';
    }

    public function sellAccount()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        if (!isset($_COOKIE['auth_token'])) {
            error_log("Sell account: No auth_token, redirecting to login");
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/login');
            exit;
        }
        $userModel = new User();
        $user = $userModel->getUserByToken($_COOKIE['auth_token']);
        if (!$user || ($user['role'] === 'user' && !$user['is_kyc_verified'])) {
            error_log("Sell account: User not verified or invalid, redirecting to profile");
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/profile');
            exit;
        }

        $gameModel = new Game();
        $games = $gameModel->getGames(100);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Sell account: POST request received");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));

            $game_id = $_POST['game_id'] ?? '';
            $account_name = $_POST['account_name'] ?? '';
            $price = $_POST['price'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($game_id && $account_name && $price >= 0 && !empty($_FILES['images']['name'][0])) {
                $accountModel = new Account();
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "$basePath/public/uploads/accounts/{$user['user_id']}/";
                $baseUrl = "http://localhost$basePath/public/uploads/accounts/{$user['user_id']}/";

                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Xử lý upload ảnh
                $image_urls = [];
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                $max_files = 5;
                $uploaded_files = $_FILES['images'];

                for ($i = 0; $i < min(count($uploaded_files['name']), $max_files); $i++) {
                    if ($uploaded_files['error'][$i] === UPLOAD_ERR_OK) {
                        $ext = strtolower(pathinfo($uploaded_files['name'][$i], PATHINFO_EXTENSION));
                        if (in_array($ext, $allowed_extensions)) {
                            $filename = uniqid('account_') . '.' . $ext;
                            $dest_path = $uploadDir . $filename;
                            if (move_uploaded_file($uploaded_files['tmp_name'][$i], $dest_path)) {
                                $image_urls[] = $baseUrl . $filename;
                            } else {
                                error_log("Sell account: Failed to move uploaded file: " . $uploaded_files['name'][$i]);
                            }
                        } else {
                            error_log("Sell account: Invalid file extension: " . $uploaded_files['name'][$i]);
                        }
                    }
                }

                // Tạo tài khoản và lưu ảnh
                if ($accountModel->create($user['user_id'], $game_id, $account_name, $price, $description, $image_urls)) {
                    if (empty($image_urls)) {
                        $warning = "Tài khoản đã được đăng bán, nhưng không có ảnh nào được upload.";
                    } else {
                        $success = "Đăng bán tài khoản thành công với " . count($image_urls) . " ảnh!";
                    }
                } else {
                    $error = "Đăng bán thất bại. Vui lòng thử lại.";
                    error_log("Sell account: Failed to create account");
                }
            } else {
                $error = "Vui lòng điền đầy đủ thông tin và upload ít nhất một ảnh.";
                error_log("Sell account: Invalid form data or no images uploaded");
            }
        }

        error_log("Sell Account page accessed, user: {$user['email']}");
        require './app/Views/Auth/sell_account.php';
    }

    public function addGame()
    {
        $basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
        if (!isset($_COOKIE['auth_token'])) {
            error_log("Add game: No auth_token, redirecting to login");
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/login');
            exit;
        }
        $userModel = new User();
        $user = $userModel->getUserByToken($_COOKIE['auth_token']);
        if (!$user || $user['role'] !== 'admin') {
            error_log("Add game: User not admin, redirecting to profile");
            header('Location: /BaiTapChuyenDePHP/PayAccount_MVC/profile');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Add game: POST request received");
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));

            $game_name = $_POST['game_name'] ?? '';
            $game_slug = $_POST['game_slug'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($game_name && $game_slug && !empty($_FILES['game_image']['name'])) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "$basePath/public/uploads/games/";
                $baseUrl = "http://localhost$basePath/public/uploads/games/";

                // Tạo thư mục nếu chưa tồn tại
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // Xử lý upload ảnh
                $image = $_FILES['game_image'];
                if ($image['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
                    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
                    if (in_array($ext, $allowed_extensions)) {
                        $filename = uniqid('game_') . '.' . $ext;
                        $dest_path = $uploadDir . $filename;
                        if (move_uploaded_file($image['tmp_name'], $dest_path)) {
                            $game_image = $baseUrl . $filename;

                            // Lưu game vào database
                            $gameModel = new Game();
                            if ($gameModel->create($game_name, $game_slug, $game_image, $description)) {
                                $success = "Thêm game thành công!";
                            } else {
                                $error = "Thêm game thất bại. Vui lòng thử lại.";
                                error_log("Add game: Failed to create game");
                            }
                        } else {
                            $error = "Không thể lưu ảnh game.";
                            error_log("Add game: Failed to move uploaded file: " . $image['name']);
                        }
                    } else {
                        $error = "Định dạng ảnh không hợp lệ (chỉ hỗ trợ JPG, PNG, GIF).";
                        error_log("Add game: Invalid file extension: " . $image['name']);
                    }
                } else {
                    $error = "Lỗi khi upload ảnh.";
                    error_log("Add game: Upload error code: " . $image['error']);
                }
            } else {
                $error = "Vui lòng điền đầy đủ thông tin và upload ảnh game.";
                error_log("Add game: Invalid form data or no image uploaded");
            }
        }

        error_log("Add Game page accessed, user: {$user['email']}");
        require './app/Views/Auth/add_game.php';
    }
}
