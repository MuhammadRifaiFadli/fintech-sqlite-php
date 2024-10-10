<?php
function connectDB() {
    $db = new SQLite3('financial.db');
    return $db;
}

function initializeDB() {
    $db = connectDB();
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS saldo (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            jumlah DECIMAL(10,2) NOT NULL,
            tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    $db->exec('
        CREATE TABLE IF NOT EXISTS pengeluaran (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            jumlah DECIMAL(10,2) NOT NULL,
            keterangan TEXT,
            tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ');
    
    return true;
}

function getSaldo() {
    $db = connectDB();
    
    $result = $db->query('SELECT COALESCE(SUM(jumlah), 0) as total FROM saldo');
    $saldoRow = $result->fetchArray(SQLITE3_ASSOC);
    $totalSaldo = $saldoRow['total'];
    
    $result = $db->query('SELECT COALESCE(SUM(jumlah), 0) as total FROM pengeluaran');
    $pengeluaranRow = $result->fetchArray(SQLITE3_ASSOC);
    $totalPengeluaran = $pengeluaranRow['total'];
    
    return $totalSaldo - $totalPengeluaran;
}

function tambahSaldo($jumlah) {
    $db = connectDB();
    $current_time = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
    
    $stmt = $db->prepare('INSERT INTO saldo (jumlah, tanggal) VALUES (:jumlah, :tanggal)');
    $stmt->bindValue(':jumlah', $jumlah, SQLITE3_FLOAT);
    $stmt->bindValue(':tanggal', $current_time, SQLITE3_TEXT);
    return $stmt->execute();
}

function tambahPengeluaran($jumlah, $keterangan) {
    $db = connectDB();
    $current_time = (new DateTime('now', new DateTimeZone('Asia/Jakarta')))->format('Y-m-d H:i:s');
   
    $stmt = $db->prepare('INSERT INTO pengeluaran (jumlah, keterangan, tanggal) VALUES (:jumlah, :keterangan, :tanggal)');
    $stmt->bindValue(':jumlah', $jumlah, SQLITE3_FLOAT);
    $stmt->bindValue(':keterangan', $keterangan, SQLITE3_TEXT);
    $stmt->bindValue(':tanggal', $current_time, SQLITE3_TEXT);
    return $stmt->execute();
}

function getPengeluaranHarian() {
    $db = connectDB();
    return $db->query('SELECT * FROM pengeluaran WHERE date(tanggal) = date("now") ORDER BY tanggal DESC');
}

function getPengeluaranMingguan() {
    $db = connectDB();
    return $db->query('SELECT * FROM pengeluaran WHERE tanggal >= date("now", "-7 days") ORDER BY tanggal DESC');
}

function getPengeluaranBulanan() {
    $db = connectDB();
    return $db->query('SELECT * FROM pengeluaran WHERE strftime("%Y-%m", tanggal) = strftime("%Y-%m", "now") ORDER BY tanggal DESC');
}

initializeDB();
?>
