<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Hitung jumlah paket
$query_paket = "SELECT COUNT(*) as total FROM paket_topup";
$result_paket = mysqli_query($koneksi, $query_paket);
$total_paket = mysqli_fetch_assoc($result_paket)['total'];

// Hitung jumlah pesanan
$query_pesanan = "SELECT COUNT(*) as total FROM pesanan";
$result_pesanan = mysqli_query($koneksi, $query_pesanan);
$total_pesanan = mysqli_fetch_assoc($result_pesanan)['total'];

// Hitung pesanan pending
$query_pending = "SELECT COUNT(*) as total FROM pesanan WHERE status_pembayaran = 'pending'";
$result_pending = mysqli_query($koneksi, $query_pending);
$total_pending = mysqli_fetch_assoc($result_pending)['total'];
?>

        <div class="dashboard-header">
            <h2>Selamat Datang, Admin <?php echo $_SESSION['username']; ?></h2>
            <p>Dashboard Admin - DiamondTopUp</p>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>Total Paket</h3>
                <p><?php echo $total_paket; ?> Paket</p>
                <a href="kelola_paket.php" class="btn" style="display: inline-block; margin-top: 10px;">Kelola Paket</a>
            </div>
            <div class="card">
                <h3>Total Pesanan</h3>
                <p><?php echo $total_pesanan; ?> Pesanan</p>
                <a href="validasi_admin.php" class="btn" style="display: inline-block; margin-top: 10px;">Validasi Pesanan</a>
            </div>
            <div class="card">
                <h3>Pesanan Pending</h3>
                <p><?php echo $total_pending; ?> Pesanan</p>
                <a href="validasi_admin.php?filter=pending" class="btn" style="display: inline-block; margin-top: 10px;">Lihat Pending</a>
            </div>
        </div>

        <div class="dashboard-header">
            <h2>Quick Actions</h2>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>Tambah Paket Baru</h3>
                <p>Tambahkan paket diamond baru ke sistem</p>
                <a href="kelola_paket.php?action=tambah" class="btn" style="display: inline-block; margin-top: 10px;">Tambah Paket</a>
            </div>
            <div class="card">
                <h3>Lihat Semua Pesanan</h3>
                <p>Lihat daftar semua pesanan dari user</p>
                <a href="validasi_admin.php" class="btn" style="display: inline-block; margin-top: 10px;">Lihat Pesanan</a>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>