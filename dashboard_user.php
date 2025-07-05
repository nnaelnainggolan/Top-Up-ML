<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit();
}

// Hitung jumlah pesanan user
$user_id = $_SESSION['user_id'];
$query_pesanan = "SELECT COUNT(*) as total FROM pesanan WHERE id_user = $user_id";
$result_pesanan = mysqli_query($koneksi, $query_pesanan);
$total_pesanan = mysqli_fetch_assoc($result_pesanan)['total'];

// Hitung pesanan pending
$query_pending = "SELECT COUNT(*) as total FROM pesanan WHERE id_user = $user_id AND status_pembayaran = 'pending'";
$result_pending = mysqli_query($koneksi, $query_pending);
$total_pending = mysqli_fetch_assoc($result_pending)['total'];
?>

        <div class="dashboard-header">
            <h2>Selamat Datang, <?php echo $_SESSION['username']; ?></h2>
            <p>Dashboard User - DiamondTopUp</p>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>Total Pesanan</h3>
                <p><?php echo $total_pesanan; ?> Pesanan</p>
            </div>
            <div class="card">
                <h3>Pesanan Pending</h3>
                <p><?php echo $total_pending; ?> Pesanan</p>
            </div>
            <div class="card">
                <h3>Buat Pesanan Baru</h3>
                <p>Top up diamond untuk game favorit Anda</p>
                <a href="paket.php" class="btn" style="display: inline-block; margin-top: 10px;">Pesan Sekarang</a>
            </div>
        </div>

        <div class="dashboard-header">
            <h2>Panduan Top Up</h2>
        </div>

        <div class="card-container">
            <div class="card">
                <h3>1. Pilih Paket</h3>
                <p>Pilih paket diamond yang sesuai dengan kebutuhan Anda.</p>
            </div>
            <div class="card">
                <h3>2. Isi Data</h3>
                <p>Isi nickname game, ID game, dan nomor WhatsApp.</p>
            </div>
            <div class="card">
                <h3>3. Upload Bukti</h3>
                <p>Upload bukti transfer pembayaran.</p>
            </div>
            <div class="card">
                <h3>4. Tunggu Proses</h3>
                <p>Diamond akan masuk dalam waktu kurang dari 5 menit.</p>
            </div>
        </div>

<?php include 'includes/footer.php'; ?>