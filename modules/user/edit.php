<?php
// modules/user/edit.php
$pesan = '';

$id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: index.php?page=user/list");
    exit;
}

$stmtSelect = mysqli_prepare(
    $conn,
    "SELECT id_barang, kategori, nama, gambar, harga_beli, harga_jual, stok FROM data_barang WHERE id_barang = ?"
);
mysqli_stmt_bind_param($stmtSelect, "i", $id);
mysqli_stmt_execute($stmtSelect);
$resSelect = mysqli_stmt_get_result($stmtSelect);
$data = mysqli_fetch_assoc($resSelect);
mysqli_stmt_close($stmtSelect);

if (!$data) {
    header("Location: index.php?page=user/list");
    exit;
}

$kategori   = $data['kategori'];
$nama       = $data['nama'];
$harga_beli = (string) $data['harga_beli'];
$harga_jual = (string) $data['harga_jual'];
$stok       = (string) $data['stok'];
$gambarLama = (string) ($data['gambar'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kategori   = trim($_POST['kategori'] ?? '');
    $nama       = trim($_POST['nama'] ?? '');
    $harga_beli = trim($_POST['harga_beli'] ?? '');
    $harga_jual = trim($_POST['harga_jual'] ?? '');
    $stok       = trim($_POST['stok'] ?? '');

    if (
        $kategori === '' ||
        $nama === '' ||
        !is_numeric($harga_beli) ||
        !is_numeric($harga_jual) ||
        !is_numeric($stok) ||
        (float) $harga_beli < 0 ||
        (float) $harga_jual < 0 ||
        (int) $stok < 0
    ) {
        $pesan = 'Data tidak valid. Periksa kembali isian.';
    } else {
        $gambarBaru = $gambarLama;

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
                        $gambarBaru = 'gambar/' . $namaFile;

                        if ($gambarLama !== '' && str_starts_with($gambarLama, 'gambar/')) {
                            $fileLama = __DIR__ . '/../../' . $gambarLama;
                            if (is_file($fileLama)) {
                                @unlink($fileLama);
                            }
                        }
                    } else {
                        $pesan = 'Gagal menyimpan gambar.';
                    }
                }
            }
        }

        if ($pesan === '') {
            $sqlUpdate = "UPDATE data_barang
                          SET kategori = ?, nama = ?, gambar = ?, harga_beli = ?, harga_jual = ?, stok = ?
                          WHERE id_barang = ?";
            $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
            $hb = (float) $harga_beli;
            $hj = (float) $harga_jual;
            $st = (int) $stok;

            mysqli_stmt_bind_param(
                $stmtUpdate,
                "sssddii",
                $kategori,
                $nama,
                $gambarBaru,
                $hb,
                $hj,
                $st,
                $id
            );

            if (mysqli_stmt_execute($stmtUpdate)) {
                mysqli_stmt_close($stmtUpdate);
                header("Location: index.php?page=user/list");
                exit;
            }

            $pesan = 'Gagal mengubah data: ' . mysqli_stmt_error($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);
        }
    }
}

$previewLama = $gambarLama === ''
    ? ''
    : (str_starts_with($gambarLama, 'gambar/') ? $gambarLama : 'gambar/' . $gambarLama);
?>

<section class="content">
    <h2>Ubah Barang</h2>

    <?php if ($pesan !== ''): ?>
        <p class="error-msg"><?= e($pesan) ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php?page=user/edit&id=<?= (int) $id ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= (int) $id ?>">

        <div class="input">
            <label for="kategori">Kategori</label>
            <input
                id="kategori"
                type="text"
                name="kategori"
                required
                value="<?= e($kategori) ?>"
            >
        </div>

        <div class="input">
            <label for="nama">Nama Barang</label>
            <input
                id="nama"
                type="text"
                name="nama"
                required
                value="<?= e($nama) ?>"
            >
        </div>

        <div class="input">
            <label for="gambar">Gambar Barang</label>
            <?php if ($previewLama !== ''): ?>
                <p><img src="<?= e($previewLama) ?>" alt="<?= e($nama) ?>" width="80"></p>
            <?php endif; ?>
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
                value="<?= e($harga_beli) ?>"
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
                value="<?= e($harga_jual) ?>"
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
                value="<?= e($stok) ?>"
            >
        </div>

        <div class="submit">
            <button type="submit">Simpan Perubahan</button>
            <a href="index.php?page=user/list">Kembali</a>
        </div>
    </form>
</section>