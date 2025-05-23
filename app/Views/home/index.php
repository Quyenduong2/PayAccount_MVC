<?php
// Kiểm tra trạng thái đăng nhập từ cookie
$isLoggedIn = isset($user) && $user !== false;
$userName = $isLoggedIn ? $user['full_name'] : '';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/public/css/main.css">
</head>

<body>
  <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-md">
                <a class="navbar-brand" href="<?php echo htmlspecialchars($basePath); ?>">PayAccount</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#games"><i class="fas fa-gamepad me-1"></i> Danh mục game</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#payment-methods"><i class="fas fa-credit-card me-1"></i> Hình thức thanh toán</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#guide"><i class="fas fa-book me-1"></i> Hướng dẫn mua bán</a>
                        </li>
                        <li class="nav-item d-flex">
                            <?php if ($isLoggedIn): ?>
                                <div class="dropdown login-nav">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-1"></i> <?php echo htmlspecialchars($userName); ?>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?php echo htmlspecialchars($basePath . '/profile'); ?>"><i class="fas fa-user-circle me-1"></i> Thông tin cá nhân</a></li>
                                        <li><a class="dropdown-item" href="<?php echo htmlspecialchars($basePath . '/logout'); ?>"><i class="fas fa-sign-out-alt me-1"></i> Đăng xuất</a></li>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/login'); ?>"><i class="fas fa-sign-in-alt me-1"></i> Đăng Nhập</a>
                                <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/register'); ?>"><i class="fa-solid fa-address-card me-1"></i> Đăng Ký</a>
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="search-container">
            <input id="searchInput" type="search" class="search-input" placeholder="Search...">
            <span class="search-icon">🔍</span>
        </div>
    </header>

    <section class="game-categories" id="games">
        <div class="container">
            <h2>Danh Mục Game</h2>
            <?php if (empty($games)): ?>
                <p class="text-center">Chưa có game nào.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($games as $game): ?>
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="game-card">
                                <a href="<?php echo htmlspecialchars($basePath . '/games/' . $game['game_slug']); ?>">
                                    <img loading="lazy" src="<?php echo htmlspecialchars($game['game_image']); ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?>">
                                </a>
                                <a href="<?php echo htmlspecialchars($basePath . '/games/' . $game['game_slug']); ?>">
                                    <h3><?php echo htmlspecialchars($game['game_name']); ?></h3>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="guide" id="guide">
        <div class="container">
            <h2>Hướng Dẫn Mua Bán</h2>
            <p>Đăng ký tài khoản, xác thực KYC để đăng bán. Chọn tài khoản ưng ý, thêm vào giỏ hàng và thanh toán an toàn. Liên hệ người bán qua FB/Zalo để nhận thông tin tài khoản.</p>
        </div>
    </section>

    <footer>
        <p>© 2025 Payaccount. <a href="#contact">Liên hệ</a> | <a href="#terms">Điều khoản</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js" integrity="sha384-RuyvpeZCxMJCqVUGFI0Do1mQrods/hhxYlcVfGPOfQtPJh0JCw12tUAZ/Mv10S7D" crossorigin="anonymous"></script>
</body>

</html>