    <footer>
        <p>© 2025 Payaccount. <a href="#contact">Liên hệ</a> | <a href="#terms">Điều khoản</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Giỏ hàng modal
        const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
        const cartModalBody = document.getElementById('cartModalBody');
        const cartCount = document.getElementById('cartCount');
    
        function loadCart() {
            fetch('<?php echo $basePath; ?>/cart?ajax=1')
                .then(res => res.text())
                .then(html => {
                    cartModalBody.innerHTML = html;
                    // Cập nhật số lượng
                    const count = cartModalBody.querySelectorAll('.cart-item').length;
                    cartCount.textContent = count;
                });
        }
    
        document.getElementById('cartIcon').addEventListener('click', function(e) {
            e.preventDefault();
            loadCart();
            cartModal.show();
        });
    
        // Thêm vào giỏ hàng (AJAX)
        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-add-cart')) {
                e.preventDefault();
                const url = e.target.getAttribute('href');
                fetch(url + '&ajax=1')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            loadCart();
                            cartModal.show();
                        }
                    });
            }
            // Xóa khỏi giỏ hàng (AJAX)
            if (e.target.classList.contains('btn-remove-cart')) {
                e.preventDefault();
                const url = e.target.getAttribute('href');
                fetch(url + '&ajax=1')
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            loadCart();
                        }
                    });
            }
        });
    
        // Tìm kiếm modal
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));
    
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const keyword = searchInput.value.trim();
            if (!keyword) return;
            fetch('<?php echo $basePath; ?>/search?q=' + encodeURIComponent(keyword) + '&ajax=1')
                .then(res => res.text())
                .then(html => {
                    searchResults.innerHTML = html;
                    searchModal.show();
                })
                .catch(() => {
                    searchResults.innerHTML = '<div class="alert alert-danger">Có lỗi xảy ra khi tìm kiếm.</div>';
                    searchModal.show();
                });
        });
    });
    </script>
    </body>
    </html>