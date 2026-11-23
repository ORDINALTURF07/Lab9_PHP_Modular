<?php
// modules/user/list.php
$sql = "SELECT id_barang, kategori, nama, gambar, harga_beli, harga_jual, stok FROM data_barang ORDER BY id_barang DESC";
$result = mysqli_query($conn, $sql);
?>

<section class="content">
    <h2>Data Barang</h2>

    <table>
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Harga Beli</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php
                    $gambarRaw = trim((string) ($row['gambar'] ?? ''));
                    $src = $gambarRaw === ''
                        ? ''
                        : (str_starts_with($gambarRaw, 'gambar/') ? $gambarRaw : 'gambar/' . $gambarRaw);
                    ?>
                    <tr>
                        <td>
                            <?php if ($src !== ''): ?>
                                <img src="<?= e($src) ?>" alt="<?= e($row['nama']) ?>" width="60">
                            <?php else: ?>
                                <span>-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($row['nama']) ?></td>
                        <td><?= e($row['kategori']) ?></td>
                        <td><?= e(formatRupiah((float) $row['harga_beli'])) ?></td>
                        <td><?= e(formatRupiah((float) $row['harga_jual'])) ?></td>
                        <td><?= e((string) $row['stok']) ?></td>
                        <td>
                            <a href="index.php?page=user/edit&id=<?= (int) $row['id_barang'] ?>">Ubah</a>
                            <a href="index.php?page=user/delete&id=<?= (int) $row['id_barang'] ?>" onclick="return confirm('Yakin ingin menghapus data barang ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Belum ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>