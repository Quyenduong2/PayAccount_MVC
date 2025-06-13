<!-- manage kyc -->
<div class="card">
    <div class="card-body">
        <h4>Quản lý KYC Requests</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Họ và Tên</th>
                    <th>Số CMND/CCCD</th>
                    <th>Ảnh</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kyc_requests as $req): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($req['request_id']); ?></td>
                        <td><?php echo htmlspecialchars($req['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($req['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($req['id_number']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($req['id_image']); ?>" target="_blank">Xem ảnh</a></td>
                        <td><?php echo htmlspecialchars($req['status']); ?></td>
                        <td>
                            <?php if ($req['status'] === 'pending'): ?>
                                <a href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-kyc&action=approve_kyc&id=' . $req['request_id']); ?>" class="btn btn-sm btn-success">Phê duyệt</a>
                                <a href="<?php echo htmlspecialchars($basePath . '/profile?section=manage-kyc&action=reject_kyc&id=' . $req['request_id']); ?>" class="btn btn-sm btn-danger">Không duyệt</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>