<div class="container mt-4">
  <div class="alert alert-success">
    <h2>Thanh toán thành công!</h2>
    <p>Thông tin tài khoản đã được gửi về email bạn vừa nhập.</p>
  </div>
  <?php if (empty($accounts)): ?>
    <p>Không có tài khoản nào.</p>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($accounts as $acc): ?>
        <div class="list-group-item mb-2">
          <h5><?php echo htmlspecialchars($acc['title']); ?></h5>
          <p>Tài khoản: <strong><?php echo htmlspecialchars((string)($acc['username'] ?? '')); ?></strong></p>
          <p>Mật khẩu: <strong><?php echo htmlspecialchars((string)($acc['password'] ?? '')); ?></strong></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <a href="<?php echo $basePath; ?>/" class="btn btn-secondary mt-3">Về trang chủ</a>
</div>