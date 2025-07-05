<?php include 'includes/header.php'; ?>
        <section class="hero">
            <div class="hero-content">
                <h2>Top Up Diamond Game Online</h2>
                <p>Proses cepat, aman, dan terpercaya .</p>
                <?php if (!isset($_SESSION['username'])): ?>
                    <div class="hero-buttons">
                        <a href="register.php" class="btn">Daftar Sekarang</a>
                        <a href="login.php" class="btn btn-gold">Login</a>
                    </div>
                <?php else: ?>
                    <div class="hero-buttons">
                        <a href="paket.php" class="btn">Lihat Paket</a>
                        <a href="dashboard_<?php echo $_SESSION['role']; ?>.php" class="btn btn-gold">Dashboard</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="features">
            <h2 class="section-title">Kenapa Memilih Kami?</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Proses Cepat</h3>
                    <p>Diamond masuk dalam waktu kurang dari 5 menit setelah pembayaran diverifikasi.</p>
                </div>
                <div class="card">
                    <h3>Harga Terbaik</h3>
                    <p>Kami menawarkan harga kompetitif dengan bonus tambahan untuk member setia.</p>
                </div>
                <div class="card">
                    <h3>24/7 Support</h3>
                    <p>Tim support kami siap membantu Anda kapan saja melalui WhatsApp.</p>
                </div>
            </div>
        </section>
<?php include 'includes/footer.php'; ?>