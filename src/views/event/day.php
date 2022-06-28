<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$date = new DateTime($_GET['date']);
$events = new Event\Events($pdo);
$events = $events->getEventsBetweenByDay($date, $date);
$carriers =  new \Carrier\Carriers($pdo);

if($_SESSION['lang'] == 'fr_FR'){
    $date2 = $date->format('d-m-Y');
}else{
    $date2 =  $date->format('m-d-Y');
}

render('header', ['title' => Translation::of('dayOf').' '.$date2]);

?>

    <div class="container">
        <a class="text-decoration-none d-print-none printButton" href="javascript:window.print()">&#x1F5A8;</a>
        <h1 class="my-3"><?= Translation::of('dayOf') ?> <?= $date2 ?></h1>
        <?php foreach ( $events[$date->format('Y-m-d')] as $event): ?>
            <div class="calendar_event">
                <?php $carrier = $carriers->find($event['id_carrier'])->getName(); ?>
                <?= (new DateTime($event['start']))->format('H:i') ?> - <?= (new DateTime($event['end']))->format('H:i') ?> - <a href="/views/event/event.php?id=<?= $event['id'];?>"><?= $carrier ?></a>
            </div>
        <?php endforeach ?>
        <a class="btn btn-primary mt-3 d-print-none" href="/views/user/adminDashboard.php"><?= Translation::of('return') ?></a>
    </div>

<?php require '../footer.php'; ?>