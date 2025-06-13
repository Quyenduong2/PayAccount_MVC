<!-- profile -->
<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <p><strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Họ và Tên:</strong> <?php echo htmlspecialchars($user['full_name']); ?></p>
        <p><strong>Vai trò:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        <p><strong>KYC:</strong> 
            <?php
            if ($user['is_kyc_verified']) {
                echo 'Đã xác minh';
            } else {
                if ($kyc_status === 'pending') {
                    echo 'Đợi xác minh';
                } elseif ($kyc_status === 'rejected') {
                    echo 'Bị từ chối';
                } else {
                    echo 'Chưa xác minh';
                }
            }
            ?>
        </p>
        <?php if (!$user['is_kyc_verified'] && $kyc_status !== 'pending'): ?>
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#kycModal">Đăng ký KYC</button>
            <div class="modal fade" id="kycModal" tabindex="-1" aria-labelledby="kycModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="kycModalLabel">Đăng ký KYC</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=kyc-submit'); ?>" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="kyc_full_name" class="form-label">Họ và Tên</label>
                                    <input type="text" class="form-control" id="kyc_full_name" name="kyc_full_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="id_number" class="form-label">Số CMND/CCCD</label>
                                    <input type="text" class="form-control" id="id_number" name="id_number" required>
                                </div>
                                <div class="mb-3">
                                    <label for="id_image" class="form-label">Ảnh CMND/CCCD</label>
                                    <input type="file" class="form-control" id="id_image" name="id_image" accept="image/*" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Gửi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <a href="<?php echo htmlspecialchars($basePath . '/'); ?>" class="btn btn-primary">Quay lại Trang Chủ</a>
    </div>
</div>