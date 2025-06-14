
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
                   <a href="<?php echo $basePath . '/cart/add?id=' . $account['account_id']; ?>" class="btn btn-cart btn-add-cart">Thêm vào giỏ hàng</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
