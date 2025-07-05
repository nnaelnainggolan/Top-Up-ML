<?php
include 'includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);
    
    // Validasi
    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Cek username sudah ada atau belum
        $check_query = "SELECT * FROM pengguna WHERE username = '$username'";
        $check_result = mysqli_query($koneksi, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user baru
            $insert_query = "INSERT INTO pengguna (username, password, role) VALUES ('$username', '$hashed_password', 'user')";
            
            if (mysqli_query($koneksi, $insert_query)) {
                $success = "Registrasi berhasil! Silakan login.";
                header('refresh:2;url=login.php');
            } else {
                $error = "Terjadi kesalahan saat registrasi: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<?php include 'includes/header.php'; ?>
        <div class="form-container">
            <h2>Register</h2>
            <?php if (isset($error)): ?>
                <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <div style="color: green; margin-bottom: 15px; text-align: center;"><?php echo $success; ?></div>
            <?php else: ?>
                <form action="register.php" method="POST">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-gold" style="width: 100%;">Daftar</button>
                </form>
                <p style="text-align: center; margin-top: 15px;">Sudah punya akun? <a href="login.php">Login disini</a></p>
            <?php endif; ?>
        </div>
<?php include 'includes/footer.php'; ?>