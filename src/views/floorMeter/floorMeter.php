<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
try{
    $floorMeterMax = $floorsMeterMax->find();
} catch (\Exception $e){
    e404();
}
render('header', ['title' => Translation::of('floorMeterMax')]);
?>

    <div class="container mt-5">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifyFloorMeterMaxText') ?>
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
        <?php if(isset($_GET['error'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorFloorMeterMax') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= Translation::of('floorMeterMax') ?></h1>
        <p class="mt-5"><?= h($floorMeterMax->getFloorMeter()); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/floorMeter/edit.php"><?= Translation::of('modifyFloorMeterMaxTitle') ?></a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/user/adminDashboard.php"><?= Translation::of('return') ?></a></div>
    </div>

<?php require '../footer.php'; ?>