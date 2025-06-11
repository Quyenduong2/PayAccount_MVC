<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Thông Tin Cá Nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            position: fixed;
            height: 100%;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #333;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="mb-4"><a  class="nav-link" href="<?php echo htmlspecialchars($basePath . '/'); ?>">PayAccount</a></h4>
        <ul class="nav flex-column">
            <?php if ($user['role'] === 'user'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'profile' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile'); ?>">
                        <i class="fas fa-user me-2"></i> Hồ Sơ
                    </a>
                </li>
                <?php if ($user['is_kyc_verified']): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $section === 'sell-account' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/sell-account'); ?>">
                            <i class="fas fa-shopping-cart me-2"></i> Sell Account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $section === 'track-accounts' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile?section=track-accounts'); ?>">
                            <i class="fas fa-list me-2"></i> Theo dõi Sell Account
                        </a>
                    </li>
                <?php endif; ?>
            <?php elseif ($user['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'dashboard' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile'); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'manage-users' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-users'); ?>">
                        <i class="fas fa-users me-2"></i> Quản lý Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'manage-accounts' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-accounts'); ?>">
                        <i class="fas fa-gamepad me-2"></i> Quản lý Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'sell-account' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/sell-account'); ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Sell Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'manage-games' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-games'); ?>">
                        <i class="fas fa-dice me-2"></i> Quản lý Games
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $section === 'manage-kyc' ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-kyc'); ?>">
                        <i class="fas fa-check-circle me-2"></i> Quản lý KYC
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/logout'); ?>">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng Xuất
                </a>
            </li>
        </ul>
    </div>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4"><?php echo $user['role'] === 'admin' ? 'Admin Dashboard' : 'Thông Tin Cá Nhân'; ?></h2>

        <?php if ($user['role'] === 'user'): ?>
            <?php if ($section === 'profile'): ?>
                <?php require 'user/profileUser.php'; ?>
            <?php elseif ($section === 'track-accounts'): ?>
                <?php require 'user/track_accounts.php'; ?>
            <?php endif; ?>
        <?php elseif ($user['role'] === 'admin'): ?>
            <?php if ($section === 'dashboard'): ?>
                <?php require 'admin/dashboard.php'; ?>
            <?php elseif ($section === 'manage-users'): ?>
                <?php require 'admin/manage_users.php'; ?>
            <?php elseif ($section === 'manage-accounts'): ?>
                <?php require 'admin/manage_accounts.php'; ?>
            <?php elseif ($section === 'manage-games'): ?>
                <?php require 'admin/manage_games.php'; ?>
            <?php elseif ($section === 'manage-kyc'): ?>
                <?php require 'admin/manage_kyc_requests.php'; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>