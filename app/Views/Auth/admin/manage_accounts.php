<!-- manage account -->
<div class="container mt-4">
    <h2>Quản lý tài khoản</h2>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <!-- Form thêm tài khoản -->
    <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-accounts&action=add_account'); ?>" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <select class="form-control" name="game_id" required>
                    <option value="">Chọn trò chơi</option>
                    <?php foreach ($games as $game): ?>
                        <option value="<?php echo htmlspecialchars($game['game_id']); ?>"><?php echo htmlspecialchars($game['game_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-control" name="user_id" required>
                    <option value="">Chọn người dùng</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['user_id']); ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" name="account_title" placeholder="Tiêu đề" required>
            </div>
            <div class="col-md-3 mb-3">
                <input type="number" class="form-control" name="price" placeholder="Giá (VNĐ)" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <textarea class="form-control" name="description" placeholder="Mô tả" required></textarea>
            </div>
            <div class="col-md-3 mb-3">
                <input type="url" class="form-control" name="facebook_link" placeholder="Link Facebook (tùy chọn)">
            </div>
            <div class="col-md-3 mb-3">
                <input type="url" class="form-control" name="zalo_link" placeholder="Link Zalo (tùy chọn)">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm tài khoản</button>
    </form>
    <!-- Danh sách tài khoản -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Trò chơi</th>
                <th>Người đăng</th>
                <th>Tiêu đề</th>
                <th>Giá</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><?php echo htmlspecialchars($account['account_id']); ?></td>
                    <td>
                        <?php
                        $game = $gameModel->getGameById($account['game_id']);
                        echo htmlspecialchars($game['game_name'] ?? 'N/A');
                        ?>
                    </td>
                    <td>
                        <?php
                        $user = $userModel->getUserById($account['user_id']);
                        echo htmlspecialchars($user['username'] ?? 'N/A');
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($account['account_title']); ?></td>
                    <td><?php echo number_format($account['price']) . ' VNĐ'; ?></td>
                    <td>
                        <?php
                        switch ($account['status']) {
                            case 'pending':
                                echo 'Đang chờ';
                                break;
                            case 'approved':
                                echo 'Đã duyệt';
                                break;
                            case 'sold':
                                echo 'Đã bán';
                                break;
                            case 'rejected':
                                echo 'Bị từ chối';
                                break;
                            default:
                                echo 'Không xác định';
                        }
                        ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAccountModal<?php echo $account['account_id']; ?>">Sửa</button>
                        <a href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-accounts&action=delete_account&id=' . $account['account_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa tài khoản này?');">Xóa</a>
                    </td>
                </tr>
                <!-- Modal sửa tài khoản -->
                <div class="modal fade" id="editAccountModal<?php echo $account['account_id']; ?>" tabindex="-1" aria-labelledby="editAccountModalLabel<?php echo $account['account_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editAccountModalLabel<?php echo $account['account_id']; ?>">Sửa tài khoản</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-accounts&action=edit_account&id=' . $account['account_id']); ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="account_title<?php echo $account['account_id']; ?>" class="form-label">Tiêu đề</label>
                                        <input type="text" class="form-control" id="account_title<?php echo $account['account_id']; ?>" name="account_title" value="<?php echo htmlspecialchars($account['account_title']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="price<?php echo $account['account_id']; ?>" class="form-label">Giá (VNĐ)</label>
                                        <input type="number" class="form-control" id="price<?php echo $account['account_id']; ?>" name="price" value="<?php echo htmlspecialchars($account['price']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="description<?php echo $account['account_id']; ?>" class="form-label">Mô tả</label>
                                        <textarea class="form-control" id="description<?php echo $account['account_id']; ?>" name="description" required><?php echo htmlspecialchars($account['description']); ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="status<?php echo $account['account_id']; ?>" class="form-label">Trạng thái</label>
                                        <select class="form-control" id="status<?php echo $account['account_id']; ?>" name="status" required>
                                            <option value="pending" <?php echo $account['status'] === 'pending' ? 'selected' : ''; ?>>Đang chờ</option>
                                            <option value="approved" <?php echo $account['status'] === 'approved' ? 'selected' : ''; ?>>Đã duyệt</option>
                                            <option value="sold" <?php echo $account['status'] === 'sold' ? 'selected' : ''; ?>>Đã bán</option>
                                            <option value="rejected" <?php echo $account['status'] === 'rejected' ? 'selected' : ''; ?>>Bị từ chối</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?php echo htmlspecialchars($basePath . '/profile'); ?>" class="btn btn-primary">Quay lại Dashboard</a>
</div>