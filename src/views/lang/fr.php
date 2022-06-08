<?php
require '../../functions.php';

$_SESSION['lang'] = 'fr-FR';
header('Location: '.$_SERVER['HTTP_REFERER']);
