<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Tambah paket baru
if (isset($_GET['action']) && $_GET['action'] == 'tambah' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $jumlah_diamond = (int)$_POST['jumlah_diamond'];
    $harga = (float)$_POST['harga'];
    
    $query = "INSERT INTO paket_topup (nama_paket, jumlah_diamond, harga) 
              VALUES ('$nama_paket', $jumlah_diamond, $harga)";
    
    if (mysqli_query($koneksi, $query)) {
        $success = "Paket berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan paket: " . mysqli_error($koneksi);
    }
}

// Edit paket
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paket = (int)$_GET['id'];
    $nama_paket = mysqli_real_escape_string($koneksi, $_POST['nama_paket']);
    $jumlah_diamond = (int)$_POST['jumlah_diamond'];
    $harga = (float)$_POST['harga'];
    
    $query = "UPDATE paket_topup 
              SET nama_paket = '$nama_paket', 
                  jumlah_diamond = $jumlah_diamond, 
                  harga = $harga 
              WHERE id_paket = $id_paket";
    
    if (mysqli_query($koneksi, $query)) {
        $success = "Paket berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui paket: " . mysqli_error($koneksi);
    }
}

// Hapus paket
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id_paket = (int)$_GET['id'];
    
    // Cek apakah paket digunakan di pesanan
    $check_query = "SELECT COUNT(*) as total FROM pesanan WHERE id_paket = $id_paket";
    $check_result = mysqli_query($koneksi, $check_query);
    $check_data = mysqli_fetch_assoc($check_result);
    
    if ($check_data['total'] > 0) {
        $error = "Paket tidak dapat dihapus karena sudah digunakan dalam pesanan!";
    } else {
        $query = "DELETE FROM paket_topup WHERE id_paket = $id_paket";
        
        if (mysqli_query($koneksi, $query)) {
            $success = "Paket berhasil dihapus!";
        } else {
            $error = "Gagal menghapus paket: " . mysqli_error($koneksi);
        }
    }
}

// Ambil data paket untuk edit
$paket_to_edit = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $id_paket = (int)$_GET['id'];
    $query = "SELECT * FROM paket_topup WHERE id_paket = $id_paket";
    $result = mysqli_query($koneksi, $query);
    $paket_to_edit = mysqli_fetch_assoc($result);
}

// Ambil semua paket
$query_all = "SELECT * FROM paket_topup ORDER BY jumlah_diamond ASC";
$result_all = mysqli_query($koneksi, $query_all);
?>

        <div class="dashboard-header">
            <h2>Kelola Paket Diamond</h2>
            <p>Tambahkan, edit, atau hapus paket top up diamond</p>
        </div>

        <?php if (isset($success)): ?>
            <div style="color: green; margin-bottom: 15px; text-align: center;"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ((isset($_GET['action']) && ($_GET['action'] == 'tambah' || $_GET['action'] == 'edit'))): ?>
            <div class="form-container">
                <h2><?php echo ($_GET['action'] == 'tambah') ? 'Tambah' : 'Edit'; ?> Paket Diamond</h2>
                <form method="POST" action="kelola_paket.php?action=<?php echo $_GET['action']; ?><?php echo (isset($_GET['id'])) ? '&id='.$_GET['id'] : ''; ?>">
                    <div class="form-group">
                        <label for="nama_paket">Nama Paket</label>
                        <input type="text" id="nama_paket" name="nama_paket" required 
                               value="<?php echo ($paket_to_edit) ? $paket_to_edit['nama_paket'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_diamond">Jumlah Diamond</label>
                        <input type="number" id="jumlah_diamond" name="jumlah_diamond" required min="1"
                               value="<?php echo ($paket_to_edit) ? $paket_to_edit['jumlah_diamond'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" required min="1000" step="1000"
                               value="<?php echo ($paket_to_edit) ? $paket_to_edit['harga'] : ''; ?>">
                    </div>
                    <button type="submit" class="btn btn-gold">Simpan</button>
                    <a href="kelola_paket.php" class="btn" style="margin-left: 10px;">Batal</a>
                </form>
            </div>
        <?php else: ?>
            <div style="text-align: right; margin-bottom: 20px;">
                <a href="kelola_paket.php?action=tambah" class="btn btn-gold">Tambah Paket Baru</a>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Jumlah Diamond</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($paket = mysqli_fetch_assoc($result_all)): ?>
                            <tr>
                                <td><?php echo $paket['nama_paket']; ?></td>
                                <td><?php echo $paket['jumlah_diamond']; ?></td>
                                <td>Rp <?php echo number_format($paket['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <a href="kelola_paket.php?action=edit&id=<?php echo $paket['id_paket']; ?>" class="btn">Edit</a>
                                    <a href="kelola_paket.php?action=hapus&id=<?php echo $paket['id_paket']; ?>" class="btn" 
                                       onclick="return confirm('Yakin ingin menghapus paket ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

<?php include 'includes/footer.php'; ?>