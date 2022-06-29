<?php

require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$datas = [
    'date' => $_GET['date'],
];

$days = new \Day\Days($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $day = $days->hydrate(new \Day\Day(), $datas);
    $days->create($day);
    header('Location: /views/event/day.php?date='.$_GET['date'].'&validationDay=1');
    exit();
}