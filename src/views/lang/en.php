<?php
require '../../functions.php';

$_SESSION['lang'] = 'english';
header('Location: '.$_SERVER['HTTP_REFERER']);