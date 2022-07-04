<?php
require '../../functions.php';

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$date = $_GET['date'];

$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
$floorMeterMax = $floorsMeterMax->find()->getFloorMeter();

$days = new \Day\Days($pdo);
try{
    $day = $days->find(new \DateTime($date));
    $dayFloorMeter = $day->getFloorMeter();
    if($dayFloorMeter < $floorMeterMax){
        echo 'limitNotReached';
    }else{
        echo 'limitReached';
    }
}catch (\Exception){
    echo 'limitNotReached';
}



