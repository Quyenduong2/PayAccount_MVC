<div class="container mt-4">
    <h2>Quản lý người dùng</h2>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <!-- Form thêm người dùng -->
    <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-users&action=add_user'); ?>" class="mb-4">
        <div class="row">
            <div class="col-md-3 mb-3">
                <input type="text" class="form-control" name="username" placeholder="Tên đăng nhập" required>
            </div>
            <div class="col-md-3 mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="col-md-3 mb-3">
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
            </div>
            <div class="col-md-3 mb-3">
                <select class="form-control" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Thêm người dùng</button>
    </form>
    <!-- Danh sách người dùng -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên đăng nhập</th>
                <th>Email</th>
                <th>Họ và Tên</th>
                <th>Vai trò</th>
                <th>KYC</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td><?php echo $user['is_kyc_verified'] ? 'Đã xác minh' : 'Chưa xác minh'; ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal<?php echo $user['user_id']; ?>">Sửa</button>
                        <a href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-users&action=delete_user&id=' . $user['user_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa user này?');">Xóa</a>
                    </td>
                </tr>
                <!-- Modal sửa người dùng -->
                <div class="modal fade" id="editUserModal<?php echo $user['user_id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel<?php echo $user['user_id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel<?php echo $user['user_id']; ?>">Sửa người dùng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-users&action=edit_user&id=' . $user['user_id']); ?>">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="username<?php echo $user['user_id']; ?>" class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control" id="username<?php echo $user['user_id']; ?>" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email<?php echo $user['user_id']; ?>" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email<?php echo $user['user_id']; ?>" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="full_name<?php echo $user['user_id']; ?>" class="form-label">Họ và Tên</label>
                                        <input type="text" class="form-control" id="full_name<?php echo $user['user_id']; ?>" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="role<?php echo $user['user_id']; ?>" class="form-label">Vai trò</label>
                                        <select class="form-control" id="role<?php echo $user['user_id']; ?>" name="role" required>
                                            <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                            <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
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