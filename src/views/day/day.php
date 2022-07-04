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
$days = new \Day\Days($pdo);
$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
$floorMeterMax = $floorsMeterMax->find()->getFloorMeter();
try{
    $day = $days->find($date);
    $validation = $day->getValidation();
}catch (\Exception){

}
if($_SESSION['lang'] == 'fr_FR'){
    $date2 = $date->format('d-m-Y');
}else{
    $date2 =  $date->format('m-d-Y');
}

render('header', ['title' => Translation::of('dayOf') . ' ' .$date2]);

?>
    <div class="container">
        <?php if(isset($_GET['validationDay'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('validationDay') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['errorDB'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorDB') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1 class="my-3"><?= Translation::of('dayOf') ?> <?= $date2 ?></h1>
    <?php if(!empty($events)){ ?>
        <a class="text-decoration-none d-print-none printButton" href="javascript:window.print()">&#x1F5A8;</a>
        <?php foreach ( $events[$date->format('Y-m-d')] as $event): ?>
            <div class="mt-3">
                <?php $carrier = $carriers->find($event['id_carrier'])->getName(); ?>
                <?= (new DateTime($event['start']))->format('H:i') ?> - <?= (new DateTime($event['end']))->format('H:i') ?> - <a href="/views/event/event.php?id=<?= $event['id'];?>"><?= $carrier ?></a>
            </div>
        <?php endforeach ?>
            <div class="mt-5 border p-2">
                <p><?= Translation::of('floorMeter') ?> : <?= $day->getFloorMeter() ?></p>
            <?php if($day->getFloorMeter() < $floorMeterMax){ ?>
                <p><?= Translation::of('limitFloorMeter') ?></p>
            <?php }else{ ?>
                <p class="text-danger"><?= Translation::of('limitFloorMeterReached') ?></p>
            <?php } ?>
            </div>
        <?php if($validation == "no"){ ?>
            <div>
                <a class="btn btn-primary mt-5 d-print-none" href="/views/day/dayValidation.php?date=<?= $_GET['date'] ?>"><?= Translation::of('dayValidation') ?></a>
            </div>
        <?php }else{ ?>
            <?php if($_SESSION['lang'] == 'fr_FR'){
                $date = $day->getValidationDate()->format('d-m-Y H:i');
            }else{
                $date = $day->getValidationDate()->format('m-d-Y H:i');
            }?>
            <div class="mt-5 border p-2">
                <p><?= Translation::of('dayValidationText') ?> <?= $date ?>.</p>
            </div>
        <?php }?>
    <?php }else{ ?>
        <p class="mt-5"><b><?= Translation::of('emptyAppointement') ?></b></p>
    <?php } ?>
        <div>
            <a class="btn btn-primary mt-3 d-print-none" href="/views/user/adminDashboard.php"><?= Translation::of('return') ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>