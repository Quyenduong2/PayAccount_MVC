<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo htmlspecialchars($basePath . '/public/css/main.scss'); ?>">
</head>

<body>
    <div class="register_body">
        <div class="register-container">
            <h2 class="text-center mb-4">Đăng Ký</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="<?php echo htmlspecialchars($basePath . '/register'); ?>" method="POST" id="registerForm">
                <div class="form-group">
                    <label for="username" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="full_name" class="form-label">Họ và Tên</label>
                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Mật Khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-register">Đăng Ký</button>
            </form>
            <div class="text-center mt-3">
                <p>Đã có tài khoản? <a href="<?php echo htmlspecialchars($basePath . '/login'); ?>">Đăng nhập</a></p>
            </div>
            <div class="text-end mt-3">
                <p><a href="<?php echo htmlspecialchars($basePath); ?>">Về trang chủ</a></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('registerForm').onsubmit = function(event) {
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const fullName = document.getElementById('full_name').value;
            const password = document.getElementById('password').value;

            if (username.length < 3) {
                alert('Tên đăng nhập phải có ít nhất 3 ký tự.');
                event.preventDefault();
                return false;
            }
            if (!email.includes('@')) {
                alert('Vui lòng nhập email hợp lệ.');
                event.preventDefault();
                return false;
            }
            if (fullName.length < 2) {
                alert('Họ và tên phải có ít nhất 2 ký tự.');
                event.preventDefault();
                return false;
            }
            if (password.length < 6) {
                alert('Mật khẩu phải có ít nhất 6 ký tự.');
                event.preventDefault();
                return false;
            }
            console.log('Form submitted to: ' + this.action); // Debug
        };
    </script>
</body>

</html>