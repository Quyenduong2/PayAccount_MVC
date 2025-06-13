<?php
$isLoggedIn = isset($user) && $user !== false;
$userName = $isLoggedIn ? htmlspecialchars($user['full_name']) : '';

// Xử lý game_image để loại bỏ ./ và đảm bảo đường dẫn đúng
$imagePath = $game['game_image'] ?? 'placeholder.jpg';
$imagePath = preg_replace('/^\.\//', '', $imagePath); // Loại bỏ ./
$imageUrl = $basePath . '/' . $imagePath;
?>


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
                                            <a href="<?php echo $basePath . '/cart/add?id=' . $account['account_id']; ?>" class="btn btn-cart btn-add-cart">Thêm vào giỏ hàng</a>
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


 