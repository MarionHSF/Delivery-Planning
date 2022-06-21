<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$carriers = new \Carrier\Carriers($pdo);
$errors = [];
try{
    $carrier = $carriers->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
        $carriers->delete($carrier);
        header('Location: /views/carrier/list.php?supression=1');
    }catch (\Exception $e){
        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=1');
    }
    exit();
}
