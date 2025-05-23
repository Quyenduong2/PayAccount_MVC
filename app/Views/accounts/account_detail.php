<?php
$isLoggedIn = isset($user) && $user !== false;
$userName = $isLoggedIn ? htmlspecialchars($user['full_name']) : '';
$basePath = '/BaiTapChuyenDePHP/PayAccount_MVC';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Chi tiết tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath . '/public/css/main.scss'); ?>">
    <style>
        .account-detail {
            padding: 50px 0;
        }

        .account-detail .carousel-item img {
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin: 0 auto;
        }

        .account-detail .account-info {
            background: var(--color-dark);
            border: 2px solid var(--color-secondary);
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 20px;
            margin-top: 20px;
        }

        .account-detail h2 {
            color: var(--color-primary);
            font-weight: 700;
            margin-bottom: 20px;
        }

        .account-detail p {
            font-size: 1.1rem;
            color: var(--color-primary);
            margin-bottom: 10px;
        }

        .account-detail p strong {
            color: var(--color-secondary);
        }

        .account-detail a.contact-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .account-detail a.contact-link:hover {
            color: var(--color-secondary);
        }

        .account-detail .btn-cart {
            background-color: var(--color-primary);
            border: none;
            color: var(--color-dark);
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .account-detail .btn-cart:hover {
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
                            <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '#games'); ?>"><i class="fas fa-gamepad me-1"></i> Danh mục game</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '#payment-methods'); ?>"><i class="fas fa-credit-card me-1"></i> Hình thức thanh toán</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '#guide'); ?>"><i class="fas fa-book me-1"></i> Hướng dẫn mua bán</a>
                        </li>
                        <li class="nav-item d-flex">
                            <?php if ($isLoggedIn): ?>
                                <div class="dropdown login-nav">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user me-1"></i> <?php echo $userName; ?>
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
            <input id="searchInput" type="search" class="search-input" placeholder="Tìm kiếm game...">
            <span class="search-icon">🔍</span>
        </div>
    </header>

    <section class="account-detail">
        <div class="container">


            <?php if (!$account): ?>
                <div class="alert alert-danger">Tài khoản không tồn tại.</div>
            <?php else: ?>
                <h2><?php echo htmlspecialchars($account['account_title']); ?></h2>

                <?php if (!empty($images)): ?>
                    <div id="accountImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php foreach ($images as $index => $image): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="<?php echo $image['image_path']; ?>" class="d-block w-100" alt="Ảnh tài khoản" crossorigin="anonymous">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#accountImagesCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#accountImagesCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                <?php else: ?>
                    <p>Chưa có ảnh cho tài khoản này.</p>
                <?php endif; ?>

                <div class="account-info">
                    <p><strong>Giá:</strong> <?php echo number_format($account['price'], 0, ',', '.') . ' VND'; ?></p>
                    <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($account['description']); ?></p>
                    <?php if ($account['facebook_link']): ?>
                        <p><strong>Facebook:</strong> <a href="<?php echo htmlspecialchars($account['facebook_link']); ?>" class="contact-link" target="_blank">Liên hệ</a></p>
                    <?php endif; ?>
                    <?php if ($account['zalo_link']): ?>
                        <p><strong>Zalo:</strong> <a href="<?php echo htmlspecialchars($account['zalo_link']); ?>" class="contact-link" target="_blank">Liên hệ</a></p>
                    <?php endif; ?>
                    <a href="#" class="btn btn-cart">Thêm vào giỏ hàng</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <p>© 2025 Payaccount. <a href="#contact">Liên hệ</a> | <a href="#terms">Điều khoản</a></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>