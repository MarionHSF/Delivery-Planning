<?php
require '../../functions.php';

$_SESSION['lang'] = 'en-US';
header('Location: '.$_SERVER['HTTP_REFERER']);

