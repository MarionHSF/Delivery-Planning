<?php
require '../../functions.php';

$_SESSION['lang'] = 'french';
header('Location: '.$_SERVER['HTTP_REFERER']);
