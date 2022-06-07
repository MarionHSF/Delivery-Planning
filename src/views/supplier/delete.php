<?php
require '../../functions.php';

$pdo = get_pdo();
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
    $suppliers->delete($supplier);
    header('Location: /views/supplier/list.php?supression=1');
    exit();
}
