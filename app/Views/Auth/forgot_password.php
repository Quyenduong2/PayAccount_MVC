<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayAccount - Quên Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2>Quên Mật Khẩu</h2>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                <?php elseif (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <form action="<?php echo $basePath; ?>/forgot-password" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <?php if (isset($user)): ?>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật Khẩu Mới</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary"><?php echo isset($user) ? 'Cập Nhật Mật Khẩu' : 'Kiểm Tra Email'; ?></button>
                </form>
                <p class="mt-3"><a href="<?php echo $basePath; ?>/login">Quay lại đăng nhập</a></p>
            </div>
        </div>
    </div>
</body>
</html>