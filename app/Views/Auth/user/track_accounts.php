<!-- card -->
<div class="card">
    <div class="card-body">
        <h4>Theo dõi danh sách Sell Account</h4>
        <?php if (empty($user_accounts)): ?>
            <p>Chưa có tài khoản nào đang bán.</p>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Game</th>
                        <th>Tên Account</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user_accounts as $acc): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($acc['account_id']); ?></td>
                            <td><?php echo htmlspecialchars($acc['game_name']); ?></td>
                            <td><?php echo htmlspecialchars($acc['account_title']); ?></td>
                            <td><?php echo number_format($acc['price'], 0, ',', '.'); ?> VNĐ</td>
                            <td><?php echo htmlspecialchars($acc['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>