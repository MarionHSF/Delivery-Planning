<?php
require '../../functions.php';

$_SESSION['lang'] = 'en_GB';
header('Location: '.$_SERVER['HTTP_REFERER']);

