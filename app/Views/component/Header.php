<?php
$isLoggedIn = isset($user) && $user !== false;
$userName = $isLoggedIn ? $user['full_name'] : '';
$basePath = $basePath ?? '/BaiTapChuyenDePHP/PayAccount_MVC';
$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
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
                        <li class="nav-item">
                            <!-- Giỏ hàng icon -->
                            <a class="nav-link position-relative" href="#" id="cartIcon" data-bs-toggle="modal" data-bs-target="#cartModal">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount"><?php echo $cartCount; ?></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Search form -->
       
    </header>

    <!-- Modal Giỏ hàng -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-black" id="cartModalLabel">Giỏ hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="cartModalBody">
                    <!-- Nội dung giỏ hàng sẽ được load bằng AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kết quả tìm kiếm -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Kết quả tìm kiếm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="searchResults">
                    <!-- Kết quả sẽ được render ở đây -->
                </div>
            </div>
        </div>
    </div>