<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// memeriksa session login
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Filter status
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Validasi pesanan
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_pesanan = (int)$_GET['id'];
    $status = $_GET['action'] == 'approve' ? 'disetujui' : 'ditolak';
    
    $query = "UPDATE pesanan SET status_pembayaran = '$status' WHERE id_pesanan = $id_pesanan";
    
    if (mysqli_query($koneksi, $query)) {
        $success = "Status pesanan berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui status pesanan: " . mysqli_error($koneksi);
    }
}

// Query untuk mendapatkan pesanan
$query = "SELECT p.*, u.username, pt.nama_paket, pt.jumlah_diamond, pt.harga 
          FROM pesanan p
          JOIN pengguna u ON p.id_user = u.id
          JOIN paket_topup pt ON p.id_paket = pt.id_paket";
          
if ($filter != 'all') {
    $query .= " WHERE p.status_pembayaran = '$filter'";
}

$query .= " ORDER BY p.created_at DESC";
$result = mysqli_query($koneksi, $query);
?>

        <div class="dashboard-header">
            <h2>Validasi Pesanan</h2>
            <p>Kelola dan validasi pesanan dari user</p>
        </div>

        <?php if (isset($success)): ?>
            <div style="color: green; margin-bottom: 15px; text-align: center;"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <div style="margin-bottom: 20px;">
            <strong>Filter Status:</strong>
            <a href="validasi_admin.php?filter=all" class="btn <?php echo ($filter == 'all') ? 'btn-gold' : ''; ?>">Semua</a>
            <a href="validasi_admin.php?filter=pending" class="btn <?php echo ($filter == 'pending') ? 'btn-gold' : ''; ?>">Pending</a>
            <a href="validasi_admin.php?filter=disetujui" class="btn <?php echo ($filter == 'disetujui') ? 'btn-gold' : ''; ?>">Disetujui</a>
            <a href="validasi_admin.php?filter=ditolak" class="btn <?php echo ($filter == 'ditolak') ? 'btn-gold' : ''; ?>">Ditolak</a>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Paket</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($pesanan = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo date('d M Y H:i', strtotime($pesanan['created_at'])); ?></td>
                                <td><?php echo $pesanan['username']; ?></td>
                                <td>
                                    <strong><?php echo $pesanan['nama_paket']; ?></strong><br>
                                    <?php echo $pesanan['jumlah_diamond']; ?> Diamond<br>
                                    Rp <?php echo number_format($pesanan['harga'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <strong>Nickname:</strong> <?php echo $pesanan['nickname_game']; ?><br>
                                    <strong>ID Game:</strong> <?php echo $pesanan['id_game']; ?><br>
                                    <strong>WhatsApp:</strong> <?php echo $pesanan['nomor_wa']; ?><br>
                                    <?php if ($pesanan['bukti_transfer']): ?>
                                        <strong>Bukti:</strong> <a href="<?php echo $pesanan['bukti_transfer']; ?>" target="_blank">Lihat</a>
                                    <?php endif; ?>
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
                                <td>
                                    <?php if ($pesanan['status_pembayaran'] == 'pending'): ?>
                                        <a href="validasi_admin.php?action=approve&id=<?php echo $pesanan['id_pesanan']; ?>" class="btn">Setujui</a>
                                        <a href="validasi_admin.php?action=reject&id=<?php echo $pesanan['id_pesanan']; ?>" class="btn">Tolak</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card" style="text-align: center; padding: 40px;">
                <h3>Tidak ada pesanan</h3>
                <p>Tidak ada pesanan dengan status <?php echo $filter; ?> ditemukan.</p>
            </div>
        <?php endif; ?>

<?php include 'includes/footer.php'; ?>