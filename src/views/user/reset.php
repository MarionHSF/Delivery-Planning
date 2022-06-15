<?php

require '../../functions.php';

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$user = $users->find($_GET['id']);

if ($user && $user->getResetToken() == $_GET['token']) {
    if($user->getResetAt() > date('Y-m-d H:i:s', strtotime('-10 minutes'))){
        header('Location: /views/user/editPassword.php?id='.$user->getId().'&resetPassword=1');
        exit();
    }else{
        header('Location: /login.php?errorToken=1');
        exit();
    }
} else {
    header('Location: /login.php?errorToken=1');
    exit();
}

