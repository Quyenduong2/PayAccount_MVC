<div class="container mt-4">
    <h2 class="mb-4">Thanh toán đơn hàng</h2>
    <form method="post" action="" class="card p-4 shadow-sm" style="max-width: 500px; margin: auto;">
        <div class="mb-3">
            <label for="guest_email" class="form-label">Email nhận xác nhận đơn hàng</label>
            <input type="email" name="guest_email" id="guest_email" class="form-control" required placeholder="Nhập email của bạn">
        </div>
        <div class="mb-3">
            <label for="method" class="form-label">Phương thức thanh toán</label>
            <select name="method" id="method" class="form-control" required>
                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                <option value="qr">Chuyển khoản qua QR Code</option>
            </select>
        </div>
        <div id="qrCodeBox" class="mb-3" style="display:none;">
            <p>Quét mã QR để thanh toán:</p>
            <img src="<?php echo $basePath; ?>/public/images/qr-code.png" alt="QR Code" style="max-width:200px;">
        </div>
        <button type="submit" class="btn btn-success w-100">Đặt hàng</button>
    </form>
</div>
<script>
const methodSelect = document.getElementById('method');
if (methodSelect) {
    methodSelect.addEventListener('change', function() {
        document.getElementById('qrCodeBox').style.display = this.value === 'qr' ? 'block' : 'none';
    });
}
</script>