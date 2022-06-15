<?php
require '../../functions.php';

if(!isset($_SESSION['auth'])){
    header('Location: /login.php?connexionOff=1');
    exit();
}

reconnectFromCookie();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$errors = [];
try{
    $user = $users->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    try{
        $id_role = $user->getIdRole();
        $users->delete($user);
        if($id_role != 1){
            header('Location: /views/user/adminsList.php?supression=1');
        }else{
            header('Location: /views/user/customersList.php?supression=1');
        }
    }catch (\Exception $e){
        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=1');
    }
    exit();
}
