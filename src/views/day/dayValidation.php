<?php

require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$date = new DateTime($_GET['date']);
$days = new \Day\Days($pdo);

$datas = [
   'validation_date' => date('Y-m-d H:i'),
    'validation' => 'yes'
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $day = $days->find($date);
    $days->hydrate($day, $datas);
    $days->validate($day);
    header('Location: /views/day/day.php?date='.$_GET['date'].'&validationDay=1');
    exit();
}