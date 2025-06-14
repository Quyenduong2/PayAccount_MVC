    <footer>
        <p>© 2025 Payaccount. <a href="#contact">Liên hệ</a> | <a href="#terms">Điều khoản</a></p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const base = '<?php echo $basePath; ?>';
            const cartIcon = document.getElementById('cartIcon');
            const cartModal = new bootstrap.Modal(document.getElementById('cartModal'));
            const cartBody = document.getElementById('cartModalBody');
            const cartCount = document.getElementById('cartCount');

            function loadCart() {
                fetch(base + '/cart?ajax=1')
                    .then(r => r.text())
                    .then(html => {
                        cartBody.innerHTML = html;
                        // cập nhật badge
                        const cnt = cartBody.querySelectorAll('.cart-item').length;
                        cartCount.textContent = cnt;
                        // Gắn sự kiện cho nút xem chi tiết
                    });
            }

            if (cartIcon) {
                cartIcon.addEventListener('click', e => {
                    e.preventDefault();
                    loadCart();
                    cartModal.show();
                });
            }

            // delegate thêm/xóa cart
            document.body.addEventListener('click', e => {
                const t = e.target;
                if (t.matches('.btn-add-cart')) {
                    e.preventDefault();
                    fetch(t.href + '&ajax=1')
                        .then(r => r.json())
                        .then(res => {
                            if (res.redirect) {
                                window.location.href = res.redirect;
                            } else {
                                loadCart(); // Cập nhật lại modal giỏ hàng
                                cartModal.show(); // Hiện modal nếu muốn
                            }
                        });
                }
                if (t.matches('.btn-remove-cart')) {
                    e.preventDefault();
                    fetch(t.href + '&ajax=1').then(r => r.json()).then(() => loadCart());
                }
            });

            // search modal
            const searchForm = document.getElementById('searchForm');
            const searchModalEl = document.getElementById('searchModal');
            const searchBody = document.getElementById('searchResults');

            if (searchForm && searchModalEl && searchBody) {
                const searchModal = new bootstrap.Modal(searchModalEl);

                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const kw = encodeURIComponent(document.getElementById('searchInput').value);
                    if (!kw) return;
                    fetch(base + '/search?q=' + kw + '&ajax=1')
                        .then(r => r.text())
                        .then(html => {
                            searchBody.innerHTML = html;
                            searchModal.show();
                        });
                });
            }
        });
    </script>

    </body>

    </html>