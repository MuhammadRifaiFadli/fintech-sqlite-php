<?php
require_once 'functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keterangan = filter_input(INPUT_POST, 'keterangan', FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    if (!empty($keterangan) && $id !== false) {
        if (editKeteranganPengeluaran($id, $keterangan)) {
            header('Location: index.php?tab=harian');
            exit;
        } else {
            $error = "Gagal mengupdate keterangan";
        }
    } else {
        $error = "Data tidak valid";
    }
}

if ($id !== false) {
    $pengeluaran = getPengeluaranById($id);
    
    if (!$pengeluaran) {
        header('Location: index.php');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Keterangan - Financial Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Keterangan Pengeluaran</h1>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($pengeluaran['id']); ?>">
            <div class="form-group">
                <label for="keterangan">Keterangan:</label>
                <input type="text" id="keterangan" name="keterangan" 
                       value="<?php echo htmlspecialchars($pengeluaran['keterangan']); ?>" required>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-secondary">Update Keterangan</button>
            </div>
        </form>
        
        <a href="index.php" class="back-link">Kembali ke Beranda</a>
    </div>
</body>
</html>