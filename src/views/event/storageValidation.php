<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
receptionValidationRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new \Event\Events($pdo);

try{
    $event = $events->find($_GET['id']);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $events->validationStorage($event);
    header('Location: /views/event/event.php?id='.$_GET['id'].'&storage=1');
    exit();
}