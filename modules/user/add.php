<?php
// modules/user/add.php
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori   = trim($_POST['kategori'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $harga_beli = (float) ($_POST['harga_beli'] ?? 0);
    $harga_jual = (float) ($_POST['harga_jual'] ?? 0);
    $stok       = (int) ($_POST['stok'] ?? 0);
    $gambar     = '';

    if (
        $kategori === '' ||
        $nama === '' ||
        $harga_beli < 0 ||
        $harga_jual < 0 ||
        $stok < 0
    ) {
        $pesan = 'Data tidak valid. Periksa kembali isian.';
    } else {
        // Proses upload gambar ke folder gambar/ di root project
        if (
            isset($_FILES['gambar']) &&
            $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                $pesan = 'Upload gambar gagal.';
            } else {
                $folder = __DIR__ . '/../../gambar/';
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime  = $finfo->file($_FILES['gambar']['tmp_name']);

                $tipeGambar = [
                    'image/jpeg' => 'jpg',
                    'image/png'  => 'png',
                    'image/webp' => 'webp',
                ];

                if (!isset($tipeGambar[$mime])) {
                    $pesan = 'Format gambar harus JPG, PNG, atau WEBP.';
                } else {
                    $namaFile = bin2hex(random_bytes(8)) . '.' . $tipeGambar[$mime];
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $folder . $namaFile)) {
                        $gambar = 'gambar/' . $namaFile;
                    } else {
                        $pesan = 'Gagal menyimpan gambar.';
                    }
                }
            }
        }

        if ($pesan === '') {
            $sql  = "INSERT INTO data_barang (kategori, nama, gambar, harga_beli, harga_jual, stok) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "sssddi",
                $kategori,
                $nama,
                $gambar,
                $harga_beli,
                $harga_jual,
                $stok
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: index.php?page=user/list");
                exit;
            }

            $pesan = 'Gagal menyimpan data: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<section class="content">
    <h2>Tambah Barang</h2>

    <?php if ($pesan !== ''): ?>
        <p class="error-msg"><?= e($pesan) ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=user/add" enctype="multipart/form-data">
        <div class="input">
            <label for="kategori">Kategori</label>
            <input
                id="kategori"
                type="text"
                name="kategori"
                required
                value="<?= e($_POST['kategori'] ?? '') ?>"
            >
        </div>

        <div class="input">
            <label for="nama">Nama Barang</label>
            <input
                id="nama"
                type="text"
                name="nama"
                required
                value="<?= e($_POST['nama'] ?? '') ?>"
            >
        </div>

        <div class="input">
            <label for="gambar">Gambar Barang</label>
            <input
                id="gambar"
                type="file"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp"
            >
        </div>

        <div class="input">
            <label for="harga_beli">Harga Beli</label>
            <input
                id="harga_beli"
                type="number"
                name="harga_beli"
                min="0"
                required
                value="<?= e($_POST['harga_beli'] ?? '') ?>"
            >
        </div>

        <div class="input">
            <label for="harga_jual">Harga Jual</label>
            <input
                id="harga_jual"
                type="number"
                name="harga_jual"
                min="0"
                required
                value="<?= e($_POST['harga_jual'] ?? '') ?>"
            >
        </div>

        <div class="input">
            <label for="stok">Stok</label>
            <input
                id="stok"
                type="number"
                name="stok"
                min="0"
                required
                value="<?= e($_POST['stok'] ?? '') ?>"
            >
        </div>

        <div class="submit">
            <button type="submit">Simpan Barang</button>
            <a href="index.php?page=user/list">Kembali</a>
        </div>
    </form>
</section>