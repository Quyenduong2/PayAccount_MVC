<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- CSS   -->
     <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath . '/public/css/main.scss'); ?>">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Đăng Nhập</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form action="<?php echo htmlspecialchars($basePath . '/login'); ?>" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Nhớ mật khẩu</label>
                </div>
                <button type="submit" class="btn-login">Đăng Nhập</button>
            </form>
            <div class="text-center mt-3">
                <p><a href="<?php echo htmlspecialchars($basePath . '/forgot-password'); ?>">Quên mật khẩu?</a></p>
                <p>Chưa có tài khoản? <a href="<?php echo htmlspecialchars($basePath . '/register'); ?>">Đăng ký ngay</a></p>
            </div>
             <div class="text-end mt-3">
                <p><a href="<?php echo htmlspecialchars($basePath); ?>">Về trang chủ</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>