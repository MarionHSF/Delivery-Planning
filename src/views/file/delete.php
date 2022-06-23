<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$files = new \File\Files($pdo);
$errors = [];
try{
    $file = $files->find($_GET['fileId'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $files->delete($file);
    exit();
}
