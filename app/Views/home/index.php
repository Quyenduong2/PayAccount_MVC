 <form class="search-container " id="searchForm" action="<?php echo $basePath; ?>/search" method="get" style="max-width:300px;">
     <input name="q" id="searchInput" type="search" class="search-input" placeholder="Tìm kiếm game...">
     <button type="submit" class="search-icon">🔍</button>
 </form>
 <section class="game-categories" id="games">
     <div class="container">
         <h2> <?php if (isset($keyword)): ?>
                 Kết quả tìm kiếm cho "<?php echo htmlspecialchars($keyword); ?>"
             <?php else: ?>
                 Danh sách game
             <?php endif; ?></h2>
         <?php if (empty($games)): ?>
             <p class="text-center">Chưa có game nào.</p>
         <?php else: ?>
             <div class="row">
                 <?php foreach ($games as $game): ?>
                     <div class="col-md-4 col-sm-6 mb-4">
                         <div class="game-card">
                             <a href="<?php echo htmlspecialchars($basePath . '/games/' . $game['game_slug']); ?>">
                                 <img loading="lazy" src="<?php echo htmlspecialchars($game['game_image']); ?>" alt="<?php echo htmlspecialchars($game['game_name']); ?>">
                             </a>
                             <a href="<?php echo htmlspecialchars($basePath . '/games/' . $game['game_slug']); ?>">
                                 <h3><?php echo htmlspecialchars($game['game_name']); ?></h3>
                             </a>
                         </div>
                     </div>
                 <?php endforeach; ?>
             </div>
         <?php endif; ?>
     </div>
 </section>

 <section class="guide" id="guide">
     <div class="container">
         <h2>Hướng Dẫn Mua Bán</h2>
         <p>Đăng ký tài khoản, xác thực KYC để đăng bán. Chọn tài khoản ưng ý, thêm vào giỏ hàng và thanh toán an toàn. Liên hệ người bán qua FB/Zalo để nhận thông tin tài khoản.</p>
     </div>
 </section>