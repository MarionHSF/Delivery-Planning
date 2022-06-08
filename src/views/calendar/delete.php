<?php
require '../../functions.php';

$pdo = get_pdo();
$events = new \Calendar\Events($pdo);
$errors = [];
try{
    $event = $events->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
        $events->delete($event);
        header('Location: /?supression=1');
        exit();
}