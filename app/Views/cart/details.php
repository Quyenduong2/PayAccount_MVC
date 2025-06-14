<div class="container mt-4">
    <h2>Chi tiết giỏ hàng</h2>
    <?php if (empty($accounts)): ?>
        <p>Giỏ hàng trống.</p>
    <?php else: ?>
        <?php foreach ($accounts as $acc): ?>
            <div class="cart-item d-flex align-items-center mb-3">
                <img src="<?php echo htmlspecialchars($acc['image_url']); ?>" style="width:60px;height:60px;object-fit:cover;margin-right:10px;">
                <div>
                    <h6><?php echo htmlspecialchars($acc['account_title'] ?? ''); ?></h6>
                    <p>Giá: <?php echo isset($acc['price']) ? number_format($acc['price']) : '0'; ?> VND</p>
                    <p><?php echo htmlspecialchars($acc['description'] ?? ''); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
        <a href="<?php echo $basePath; ?>/cart/checkout" class="btn btn-success w-100">Thanh toán</a>
    <?php endif; ?>
</div>