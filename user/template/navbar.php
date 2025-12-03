<nav class="navbar">
    <div class="nav-left">
        <img src="gambar/logo/logo.png" alt="Logo Hello Indonesia" height="70" style="margin-left: -25px;">
    </div>
    <div class="nav-center">
        <div class="nav-links">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $beranda_active = ($current_page == 'beranda.php') ? 'active' : '';
            $pariwisata_active = ($current_page == 'pariwisata.php') ? 'active' : '';
            $favorite_active = ($current_page == 'favorit.php') ? 'active' : '';
            ?>
            <a href="beranda.php" class="<?php echo $beranda_active; ?>">
                <i class="fa-solid fa-house"></i> Beranda
            </a>
            <a href="pariwisata.php" class="<?php echo $pariwisata_active; ?>">
                <i class="fa-solid fa-plane" class="<?php echo $favorite_active; ?>"></i> Pariwisata
            </a>
            <a href="favorit.php" class="<?php echo $favorite_active; ?>">
                <i class="fa-regular fa-heart"></i> Favorit
            </a>
        </div>
    </div>

    <div class="nav-right">
        <?php if (isset($_SESSION['username'])): ?>
            <div class="user-info">
                <span class="username"><i class="fa-solid fa-at"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../login/logout.php" class="login-btn"><i class="fa-solid fa-user-slash"></i> Logout</a>
            </div>

        <?php else: ?>
            <a href="../login/login.php" class="login-btn"><i class="fa-regular fa-user"></i> Login</a>
        <?php endif; ?>

    </div>
</nav>