<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$carriers = new \Carrier\Carriers($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $carrier = $carriers->find($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $carrier->getName()]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                   <?= Translation::of('modifyCarrier') ?>
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
                    <?= Translation::of('errorCarrier') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= h($carrier->getName()); ?></h1>
        <p><?= Translation::of('comment') ?> : <?= h($carrier->getComment()); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/carrier/edit.php?id=<?= $carrier->getId();?>"><?= Translation::of('modifyCarrierTitle') ?></a>
            <a class="btn btn-primary mt-3" href="/views/carrier/delete.php?id=<?= $carrier->getId();?>"><?= Translation::of('deleteCarrierTitle') ?></a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/carrier/list.php"><?= Translation::of('return') ?></a></div>
    </div>

<?php require '../footer.php'; ?>