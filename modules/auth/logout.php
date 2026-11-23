<?php
// modules/auth/logout.php
unset($_SESSION['user_login']);
header('Location: index.php?page=auth/login');
exit;