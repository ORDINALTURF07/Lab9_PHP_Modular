<?php
// modules/user/delete.php
$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmtSelect = mysqli_prepare($conn, "SELECT gambar FROM data_barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtSelect, "i", $id);
    mysqli_stmt_execute($stmtSelect);
    $resSelect = mysqli_stmt_get_result($stmtSelect);
    $row = mysqli_fetch_assoc($resSelect);
    mysqli_stmt_close($stmtSelect);

    $stmtDelete = mysqli_prepare($conn, "DELETE FROM data_barang WHERE id_barang = ?");
    mysqli_stmt_bind_param($stmtDelete, "i", $id);
    mysqli_stmt_execute($stmtDelete);
    mysqli_stmt_close($stmtDelete);

    if ($row && !empty($row['gambar']) && str_starts_with((string) $row['gambar'], 'gambar/')) {
        $filePath = __DIR__ . '/../../' . $row['gambar'];
        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }
}

header("Location: index.php?page=user/list");
exit;