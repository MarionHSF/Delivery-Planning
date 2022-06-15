<?php
require '../../functions.php';

if(!isset($_SESSION['auth'])){
    header('Location: /login.php?connexionOff=1');
    exit();
}

reconnectFromCookie();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$suppliers = new \Supplier\Suppliers($pdo);
$errors = [];
try{
    $supplier = $suppliers->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
        $suppliers->delete($supplier);
        header('Location: /views/supplier/list.php?supression=1');
    }catch (\Exception $e){
        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=1');
    }
    exit();
}
