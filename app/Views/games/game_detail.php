<?php
$isLoggedIn = isset($user) && $user !== false;
$userName = $isLoggedIn ? htmlspecialchars($user['full_name']) : '';

// Xử lý game_image để loại bỏ ./ và đảm bảo đường dẫn đúng
$imagePath = $game['game_image'] ?? 'placeholder.jpg';
$imagePath = preg_replace('/^\.\//', '', $imagePath); // Loại bỏ ./
$imageUrl = $basePath . '/' . $imagePath;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - <?php echo htmlspecialchars($game['game_name'] ?? 'Game Không Tồn Tại'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath); ?>/public/css/main.scss">
    <style>
        .card-body {
            background-color: var(--color-dark);
        }

        .game-detail {
            padding: 50px 0 !important;
        }

        .game-detail h2{
            background-color: var(--color-primary-blur);
            color: var(--color-dark);
        }

        .game-detail img {
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }


        .game-detail .account-card {
            background: #fff !important;
            border: 2px solid var(--color-secondary);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
            position: relative;
        }

        .game-detail .account-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px var(--color-primary-blur);
        }

        .game-detail .account-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--color-primary), var(--color-secondary));
        }

        .game-detail .account-card .card-body {
            padding: 20px;
        }

        .game-detail .account-card h5 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--color-primary);
            margin-bottom: 15px;
        }

        .game-detail .account-card p {
            font-size: 1rem;
            color: var(--color-primary);
            margin-bottom: 10px;
        }

        .game-detail .account-card p strong {
            color: var(--color-secondary);
        }

        .game-detail .account-card a.contact-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .game-detail .account-card a.contact-link:hover {
            color: var(--color-secondary);
        }

        .game-detail .account-card .btn-cart {
            background-color: var(--color-primary);
            border: none;
            color: var(--color-dark);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
            text-decoration: none;
        }

        .game-detail .account-card .btn-cart:hover {
            background-color: var(--color-secondary);
            color: #fff;
            transform: scale(1.05);
        }
    </style>
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
    <section class="game-detail">
        <div class="container-fluid">

            <?php if (!$game): ?>
                <div class="alert alert-danger">Game không tồn tại.</div>
            <?php else: ?>

                <h2><?php echo htmlspecialchars($game['game_name']); ?></h2>
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($game['game_name']); ?>">
                    </div>
                    <div class="col-md-8">
                        <h4>Thông tin game</h4>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($game['game_slug']); ?></p>
                    </div>
                </div>

                <h3 class="mt-5">Tài khoản liên quan</h3>
                <?php if (empty($accounts)): ?>
                    <p>Chưa có tài khoản nào cho game này.</p>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($accounts as $account): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card account-card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($account['account_title']); ?></h5>
                                        <p><strong>Giá:</strong> <?php echo number_format($account['price'], 0, ',', '.') . ' VND'; ?></p>
                                        <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($account['description']); ?></p>
                                        <?php if ($account['facebook_link']): ?>
                                            <p><strong>Facebook:</strong> <a href="<?php echo htmlspecialchars($account['facebook_link']); ?>" class="contact-link" target="_blank">Liên hệ</a></p>
                                        <?php endif; ?>
                                        <?php if ($account['zalo_link']): ?>
                                            <p><strong>Zalo:</strong> <a href="<?php echo htmlspecialchars($account['zalo_link']); ?>" class="contact-link" target="_blank">Liên hệ</a></p>
                                        <?php endif; ?>

                                         <div class="d-flex gap-2">
                                            <a href="#" class="btn btn-cart">Thêm vào giỏ hàng</a>
                                            <a href="<?php echo htmlspecialchars($basePath . '/account/' . $account['account_id']); ?>" class="btn btn-cart">Xem chi tiết</a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>


    <footer>
        <p>© 2025 Payaccount. <a href="#contact">Liên hệ</a> | <a href="#terms">Điều khoản</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>