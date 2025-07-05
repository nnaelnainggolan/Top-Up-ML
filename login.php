<?php
include 'includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    
    $query = "SELECT * FROM pengguna WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            
            
            if ($user['role'] == 'admin') {
                header('Location: dashboard_admin.php');
            } else {
                header('Location: dashboard_user.php');
            }
            exit();
        } else {
            $error = "Username atau password salah!";
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<?php include 'includes/header.php'; ?>
        <div class="form-container">
            <h2>Login</h2>
            <?php if (isset($error)): ?>
                <div style="color: red; margin-bottom: 15px; text-align: center;"><?php echo $error; ?></div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-gold" style="width: 100%;">Login</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">Belum punya akun? <a href="register.php">Daftar disini</a></p>
        </div>
<?php include 'includes/footer.php'; ?>