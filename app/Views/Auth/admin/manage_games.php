<div class="card">
    <div class="card-body">
        <h4>Quản lý Games</h4>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addGameModal">Thêm Game</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên Game</th>
                    <th>Slug</th>
                    <th>Ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($games as $game): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($game['game_id']); ?></td>
                        <td><?php echo htmlspecialchars($game['game_name']); ?></td>
                        <td><?php echo htmlspecialchars($game['game_slug']); ?></td>
                        <td>
                            <?php if ($game['game_image']): ?>
                                <img src="<?php echo htmlspecialchars($game['game_image']); ?>" width="50" alt="Game Image">
                            <?php else: ?>
                                Không có ảnh
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editGameModal<?php echo $game['game_id']; ?>">Sửa</button>
                            <a href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-games&action=delete_game&id=' . $game['game_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Xóa game này?');">Xóa</a>
                        </td>
                    </tr>
                    <!-- Modal sửa trò chơi -->
                    <div class="modal fade" id="editGameModal<?php echo $game['game_id']; ?>" tabindex="-1" aria-labelledby="editGameModalLabel<?php echo $game['game_id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editGameModalLabel<?php echo $game['game_id']; ?>">Sửa trò chơi</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-games&action=edit_game&id=' . $game['game_id']); ?>" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input type="hidden" name="game_id" value="<?php echo htmlspecialchars($game['game_id']); ?>">
                                        <div class="mb-3">
                                            <label for="game_name_<?php echo $game['game_id']; ?>" class="form-label">Tên trò chơi</label>
                                            <input type="text" class="form-control" id="game_name_<?php echo $game['game_id']; ?>" name="game_name" value="<?php echo htmlspecialchars($game['game_name']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="game_slug_<?php echo $game['game_id']; ?>" class="form-label">Slug</label>
                                            <input type="text" class="form-control" id="game_slug_<?php echo $game['game_id']; ?>" name="game_slug" value="<?php echo htmlspecialchars($game['game_slug']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="game_image_<?php echo $game['game_id']; ?>" class="form-label">Ảnh trò chơi</label>
                                            <input type="file" class="form-control" id="game_image_<?php echo $game['game_id']; ?>" name="game_image" accept="image/*">
                                            <?php if ($game['game_image']): ?>
                                                <small>Hiện tại: <a href="<?php echo htmlspecialchars($game['game_image']); ?>" target="_blank">Xem ảnh</a></small>
                                            <?php endif; ?>
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
        <!-- Modal thêm trò chơi -->
        <div class="modal fade" id="addGameModal" tabindex="-1" aria-labelledby="addGameModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGameModalLabel">Thêm trò chơi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="<?php echo htmlspecialchars($basePath . '/profile?section=manage-games&action=create_game'); ?>" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="game_name_add" class="form-label">Tên trò chơi</label>
                                <input type="text" class="form-control" id="game_name_add" name="game_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="game_slug_add" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="game_slug_add" name="game_slug" placeholder="Ví dụ: delta-force" required>
                            </div>
                            <div class="mb-3">
                                <label for="game_image_add" class="form-label">Ảnh trò chơi</label>
                                <input type="file" class="form-control" id="game_image_add" name="game_image" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary">Thêm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>