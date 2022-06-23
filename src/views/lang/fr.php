<?php
require '../../functions.php';

$_SESSION['lang'] = 'fr_FR';
header('Location: '.$_SERVER['HTTP_REFERER']);
