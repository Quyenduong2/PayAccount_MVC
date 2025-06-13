<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Đăng Bán Tài Khoản</title>
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
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
    <div class="sidebar ">
        <h4 class="mb-4">PayAccount</h4>
        <ul class="nav flex-column">
            <?php if ($user['role'] === 'user'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile'); ?>">
                        <i class="fas fa-user me-2"></i> Hồ Sơ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo htmlspecialchars($basePath . '/sell-account'); ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Sell Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile?section=track-accounts'); ?>">
                        <i class="fas fa-list me-2"></i> Theo dõi Sell Account
                    </a>
                </li>
            <?php elseif ($user['role'] === 'admin'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile'); ?>">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-users'); ?>">
                        <i class="fas fa-users me-2"></i> Quản lý Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-accounts'); ?>">
                        <i class="fas fa-gamepad me-2"></i> Quản lý Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo htmlspecialchars($basePath . '/sell-account'); ?>">
                        <i class="fas fa-shopping-cart me-2"></i> Sell Account
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-games'); ?>">
                        <i class="fas fa-dice me-2"></i> Quản lý Games
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-kyc'); ?>">
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
        <h2 class="mb-4">Đăng Bán Tài Khoản</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($basePath . '/sell-account'); ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="game_id" class="form-label">Chọn Game</label>
                <select class="form-select" id="game_id" name="game_id" required>
                    <option value="">Chọn game</option>
                    <?php foreach ($games as $game): ?>
                        <option value="<?php echo htmlspecialchars($game['game_id']); ?>"><?php echo htmlspecialchars($game['game_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="account_name" class="form-label">Tên Tài Khoản</label>
                <input type="text" class="form-control" id="account_name" name="account_name" required>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Giá (VNĐ)</label>
                <input type="number" class="form-control" id="price" name="price" min="0" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô Tả</label>
                <textarea class="form-control" id="description" name="description" rows="4"></textarea>
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Hình Ảnh Tài Khoản (5-10 ảnh)</label>
                <input type="file" class="form-control" id="images" name="images[]" accept="image/*" multiple required>
                <small class="form-text text-muted">Chọn ít nhất 5 và tối đa 10 hình ảnh.</small>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Bán</button>
            <a href="<?php echo htmlspecialchars($basePath . '/profile'); ?>" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>