<?php

require '../../functions.php';

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$user = $users->find($_GET['id']);

if($user && $user->getConfirmationToken() == $_GET['token']){
    $users->confirmAccount($user);
    $_SESSION['auth'] = $user;
    header('Location: /views/user/user.php?id=' . $user->getId().'&validation=1');
    exit();
}else{
    header('Location: /login.php?errorToken=1');
    exit();
}
