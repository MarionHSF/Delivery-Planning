<?php
require '../../functions.php';

$pdo = get_pdo();
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
    $carriers->delete($carrier);
    header('Location: /views/carrier/list.php?supression=1');
    exit();
}
