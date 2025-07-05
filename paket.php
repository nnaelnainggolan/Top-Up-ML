<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit;
}

// Ambil data paket dari database
$query = "SELECT * FROM paket_topup ORDER BY jumlah_diamond ASC";
$result = mysqli_query($koneksi, $query);
?>

<div class="dashboard-header">
    <h2>Paket Diamond Tersedia</h2>
    <p>Pilih paket yang sesuai dengan kebutuhan Anda</p>
</div>

<div class="card-container">
    <?php while ($paket = mysqli_fetch_assoc($result)): ?>
        <div class="card diamond-card">
            
            <div class="diamond-image">
                <img src="diamond6.jpg-<?php echo $paket['jumlah_diamond']; ?>.png" 
                     alt="<?php echo $paket['nama_paket']; ?>"
                     onerror="this.src='diamond5.jpg'">
            </div>
            
            <div class="diamond-details">
                <h3><?php echo $paket['nama_paket']; ?></h3>
                <div class="diamond-count">
                    <span class="count"><?php echo $paket['jumlah_diamond']; ?></span>
                    <span class="diamond-text">Diamond</span>
                </div>
                <div class="price">Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></div>
                <a href="pesan.php?id_paket=<?php echo $paket['id_paket']; ?>" class="btn btn-gold">Beli</a>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php include 'includes/footer.php'; ?>