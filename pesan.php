<?php
include 'includes/koneksi.php';
include 'includes/header.php';

// Periksa session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Jika ada id_paket di URL, berarti user memilih paket tertentu
$id_paket = isset($_GET['id_paket']) ? (int)$_GET['id_paket'] : 0;

// Ambil data paket jika id_paket ada
$selected_paket = null;
if ($id_paket > 0) {
    $query_paket = "SELECT * FROM paket_topup WHERE id_paket = $id_paket";
    $result_paket = mysqli_query($koneksi, $query_paket);
    $selected_paket = mysqli_fetch_assoc($result_paket);
}

// Proses form pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paket = (int)$_POST['id_paket'];
    $nickname = mysqli_real_escape_string($koneksi, $_POST['nickname']);
    $id_game = mysqli_real_escape_string($koneksi, $_POST['id_game']);
    $nomor_wa = mysqli_real_escape_string($koneksi, $_POST['nomor_wa']);
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);

    // Validasi dan proses file upload
    $bukti_transfer = null;
    if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['bukti_transfer']['type'];
        $file_size = $_FILES['bukti_transfer']['size'];

        if (!in_array($file_type, $allowed_types)) {
            $error = "Format file tidak didukung. Hanya JPG dan PNG yang diperbolehkan.";
        } elseif ($file_size > 2 * 1024 * 1024) { // 2MB
            $error = "Ukuran file terlalu besar. Maksimal 2MB.";
        } else {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_name = uniqid() . '_' . basename($_FILES['bukti_transfer']['name']);
            $target_path = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['bukti_transfer']['tmp_name'], $target_path)) {
                $bukti_transfer = $target_path;
            } else {
                $error = "Gagal mengunggah file.";
            }
        }
    }

    // Simpan ke database jika tidak ada error
    if (!isset($error)) {
        $query = "INSERT INTO pesanan (id_user, id_paket, nickname_game, id_game, nomor_wa, catatan, bukti_transfer) 
                  VALUES ($user_id, $id_paket, '$nickname', '$id_game', '$nomor_wa', '$catatan', '$bukti_transfer')";

        if (mysqli_query($koneksi, $query)) {
            $success = "Pesanan berhasil dibuat! Admin akan memverifikasi pembayaran Anda.";
        } else {
            $error = "Terjadi kesalahan: " . mysqli_error($koneksi);
        }
    }
}
?>

<div class="form-container">
    <h2>Pesan Top Up Diamond</h2>
    <?php if (isset($success)): ?>
        <div style="color: green; margin-bottom: 15px; text-align: center;"><?php echo $success; ?></div>
        <div style="text-align: center;">
            <a href="riwayat.php" class="btn">Lihat Riwayat Pesanan</a>
        </div>
    <?php else: ?>
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="pesan.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="id_paket">Pilih Paket Diamond</label>
                <select id="id_paket" name="id_paket" required>
                    <option value="">-- Pilih Paket --</option>
                    <?php
                    $query_all_paket = "SELECT * FROM paket_topup ORDER BY jumlah_diamond ASC";
                    $result_all_paket = mysqli_query($koneksi, $query_all_paket);

                    while ($paket = mysqli_fetch_assoc($result_all_paket)) {
                        $selected = ($id_paket == $paket['id_paket']) ? 'selected' : '';
                        echo "<option value='{$paket['id_paket']}' $selected>{$paket['nama_paket']} - {$paket['jumlah_diamond']} Diamond (Rp " . number_format($paket['harga'], 0, ',', '.') . ")</option>";
                    }
                    ?>
                </select>
            </div>

            <?php if ($selected_paket): ?>
                <div class="card" style="margin-bottom: 20px; background-color: rgba(74, 144, 226, 0.1);">
                    <h3>Detail Paket Dipilih</h3>
                    <p><strong>Nama Paket:</strong> <?php echo $selected_paket['nama_paket']; ?></p>
                    <p><strong>Jumlah Diamond:</strong> <?php echo $selected_paket['jumlah_diamond']; ?></p>
                    <p><strong>Harga:</strong> Rp <?php echo number_format($selected_paket['harga'], 0, ',', '.'); ?></p>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label for="nickname">Nickname Game</label>
                <input type="text" id="nickname" name="nickname" required>
            </div>

            <div class="form-group">
                <label for="id_game">ID Game</label>
                <input type="text" id="id_game" name="id_game" required>
            </div>

            <div class="form-group">
                <label for="nomor_wa">Nomor WhatsApp</label>
                <input type="text" id="nomor_wa" name="nomor_wa" required placeholder="Contoh: 081234567890">
            </div>

            <div class="form-group">
                <label for="catatan">Catatan Tambahan (Opsional)</label>
                <textarea id="catatan" name="catatan" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="bukti_transfer">Upload Bukti Transfer</label>
                <input type="file" id="bukti_transfer" name="bukti_transfer" accept="image/jpeg,image/png" required>
                <small>Format: JPG/PNG (Maksimal 2MB)</small>
            </div>

            <button type="submit" class="btn btn-gold" style="width: 100%;">Pesan Sekarang</button>
        </form>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
