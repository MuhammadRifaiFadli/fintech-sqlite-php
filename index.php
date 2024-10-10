<?php
require_once 'functions.php';
$saldo = getSaldo();

// Menentukan tab yang aktif
$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'harian';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Financial Management</h1>
        
        <div class="saldo-info">
            <h2>Saldo Saat Ini: Rp <?php echo number_format($saldo, 0, ',', '.'); ?></h2>
            <div class="action-buttons">
                <a href="tambah-saldo.php" class="btn btn-primary">Tambah Saldo</a>
                <a href="tambah-pengeluaran.php" class="btn btn-secondary">Catat Pengeluaran</a>
            </div>
        </div>

        <div class="pengeluaran-section">
            <h2>Pengeluaran</h2>
            <!-- Tab menggunakan link PHP -->
            <div class="tabs">
                <a href="?tab=harian" class="tab-btn <?php echo $activeTab === 'harian' ? 'active' : ''; ?>">Harian</a>
                <a href="?tab=mingguan" class="tab-btn <?php echo $activeTab === 'mingguan' ? 'active' : ''; ?>">Mingguan</a>
                <a href="?tab=bulanan" class="tab-btn <?php echo $activeTab === 'bulanan' ? 'active' : ''; ?>">Bulanan</a>
            </div>

            <?php
            // Menampilkan konten berdasarkan tab yang aktif
            if ($activeTab === 'harian') {
                echo "<h3>Pengeluaran Harian</h3>";
                $pengeluaran = getPengeluaranHarian();
                $dateFormat = 'H:i';
            } elseif ($activeTab === 'mingguan') {
                echo "<h3>Pengeluaran Mingguan</h3>";
                $pengeluaran = getPengeluaranMingguan();
                $dateFormat = 'd/m/Y';
            } else {
                echo "<h3>Pengeluaran Bulanan</h3>";
                $pengeluaran = getPengeluaranBulanan();
                $dateFormat = 'd/m/Y';
            }

            // Menampilkan data pengeluaran
            while ($row = $pengeluaran->fetchArray(SQLITE3_ASSOC)) {
                echo "<div class='pengeluaran-item'>";
                echo "<span class='tanggal'>" . date($dateFormat, strtotime($row['tanggal'])) . "</span>";
                echo "<span class='keterangan'>" . htmlspecialchars($row['keterangan']) . "</span>";
                echo "<span class='jumlah'>Rp " . number_format($row['jumlah'], 0, ',', '.') . "</span>";
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>