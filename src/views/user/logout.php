<?php

session_start();
setcookie('remember', NULL, -1, '/');
unset($_COOKIE['remember']);
unset($_SESSION['auth']);

header('Location: /login.php?logout=1');

