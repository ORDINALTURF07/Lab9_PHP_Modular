<?php
// views/header.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aplikasi PHP Modular</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
<header>
    <h1>Aplikasi PHP Modular</h1>
    <p>Data Barang (Database latihan1)</p>
</header>
<nav>
    <a href="index.php?page=dashboard">Dashboard</a>
    <a href="index.php?page=user/list">Data Barang</a>
    <a href="index.php?page=user/add">Tambah Barang</a>
<?php if (is_logged_in()): ?>
  <a href="index.php?page=auth/logout">Logout (<?= e($_SESSION['user_login']) ?>)</a>
<?php else: ?>
  <a href="index.php?page=auth/login">Login</a>
<?php endif; ?>
</nav>
<main>