<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

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
            header('Location: /views/user/adminsList.php?suppression=1');
        }else{
            header('Location: /views/user/customersList.php?suppression=1');
        }
    }catch (\Exception $e){
        header('Location: '.$_SERVER['HTTP_REFERER'].'&error=1');
    }
    exit();
}
