CREATE DATABASE IF NOT EXISTS topup_diamond;
USE topup_diamond;

--  Tabel pengguna
CREATE TABLE pengguna (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
);

--  Tabel paket_topup
CREATE TABLE paket_topup (
    id_paket INT(11) NOT NULL AUTO_INCREMENT,
    nama_paket VARCHAR(100) NOT NULL,
    jumlah_diamond INT(11) NOT NULL,
    harga DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (id_paket)
);

--  Tabel pesanan
CREATE TABLE pesanan (
    id_pesanan INT(11) NOT NULL AUTO_INCREMENT,
    id_user INT(11) NOT NULL,
    id_paket INT(11) NOT NULL,
    nickname_game VARCHAR(100) NOT NULL,
    id_game VARCHAR(50) NOT NULL,
    nomor_wa VARCHAR(20) NOT NULL,
    catatan TEXT,
    bukti_transfer VARCHAR(255),
    status_pembayaran ENUM('pending', 'disetujui', 'ditolak') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id_pesanan),
    FOREIGN KEY (id_user) REFERENCES pengguna(id) ON DELETE CASCADE,
    FOREIGN KEY (id_paket) REFERENCES paket_topup(id_paket) ON DELETE CASCADE
);

-- Data dummy
-- Insert pengguna (password: admin123 untuk admin, user123 untuk user)
INSERT INTO pengguna (username, password, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('gamer1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
('gamer2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

-- Insert paket topup
INSERT INTO paket_topup (nama_paket, jumlah_diamond, harga) VALUES
('Paket Starter', 86, 15000),
('Paket Regular', 170, 28000),
('Paket Premium', 344, 55000),
('Paket VIP', 700, 110000),
('Paket Ultimate', 1440, 220000);

-- Insert pesanan dummy
INSERT INTO pesanan (id_user, id_paket, nickname_game, id_game, nomor_wa, catatan, status_pembayaran) VALUES
(2, 1, 'ProGamer01', '123456789', '081234567890', 'Tolong proses cepat ya', 'pending'),
(3, 2, 'KingPlayer', '987654321', '081987654321', 'Terima kasih', 'disetujui');