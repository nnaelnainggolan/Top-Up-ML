<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil riwayat pesanan user
$query = "SELECT p.*, pt.nama_paket, pt.jumlah_diamond, pt.harga 
          FROM pesanan p
          JOIN paket_topup pt ON p.id_paket = pt.id_paket
          WHERE p.id_user = $user_id
          ORDER BY p.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

        <div class="dashboard-header">
            <h2>Riwayat Pesanan</h2>
            <p>Daftar semua pesanan yang pernah Anda lakukan</p>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Paket</th>
                            <th>Detail</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo date('d M Y H:i', strtotime($pesanan['created_at'])); ?></td>
                                <td>
                                    <strong><?php echo $pesanan['nama_paket']; ?></strong><br>
                                    <?php echo $pesanan['jumlah_diamond']; ?> Diamond<br>
                                    Rp <?php echo number_format($pesanan['harga'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <strong>Nickname:</strong> <?php echo $pesanan['nickname_game']; ?><br>
                                    <strong>ID Game:</strong> <?php echo $pesanan['id_game']; ?><br>
                                    <strong>WhatsApp:</strong> <?php echo $pesanan['nomor_wa']; ?>
                                </td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    if ($pesanan['status_pembayaran'] == 'pending') {
                                        $status_class = 'status-pending';
                                    } elseif ($pesanan['status_pembayaran'] == 'disetujui') {
                                        $status_class = 'status-disetujui';
                                    } else {
                                        $status_class = 'status-ditolak';
                                    }
                                    ?>
                                    <span class="<?php echo $status_class; ?>">
                                        <?php echo ucfirst($pesanan['status_pembayaran']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 40px;">
                <h3>Anda belum memiliki riwayat pesanan</h3>
                <p>Mulai pesan diamond untuk game favorit Anda sekarang!</p>
                <a href="paket.php" class="btn btn-gold">Lihat Paket Diamond</a>
            </div>
        <?php endif; ?>

<?php include 'includes/footer.php'; ?>