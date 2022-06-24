<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndAdminExcept2Rights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new \Event\Events($pdo);
$errors = [];
try{
    $event = $events->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $date = new \DateTime(date('Y-m-d H:i:s'));
    $limitDate = $event->getStart()->modify('-24 hours');
    if($_SESSION['auth']->getIdRole() == 1 && $date > $limitDate){
        header('Location: /views/event/event.php?id='.$event->getId().'&limitDate=1');
    }else{
        $events->delete($event);
        if($_SESSION['auth']->getIdRole() == 1){
            header('Location: /views/user/userDashboard.php?id='.$_SESSION['auth']->getId().'&suppression=1');
            exit();
        }else{
            header('Location: /views/user/adminDashboard.php?suppression=1');
            exit();
        }
    }
}
