<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_FLOAT);
    
    if ($jumlah !== false && $jumlah > 0) {
        if (tambahSaldo($jumlah)) {
            header('Location: index.php');
            exit;
        } else {
            $error = "Gagal menambah saldo";
        }
    } else {
        $error = "Jumlah tidak valid";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Saldo - Financial Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Tambah Saldo</h1>
        
        <?php if (isset($error)): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="jumlah">Jumlah Saldo:</label>
                <input type="number" id="jumlah" name="jumlah" step="1000" min="1000" required>
            </div>
            <div class="action-buttons">
                <button type="submit" class="btn btn-primary">Tambah Saldo</button>
            </div>
        </form>
        
        <a href="index.php" class="back-link">Kembali ke Beranda</a>
    </div>
</body>
</html>