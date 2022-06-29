<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$suppliers = new \Supplier\Suppliers($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $supplier = $suppliers->find($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $supplier->getName()]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifySupplier') ?>
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
                    <?= Translation::of('errorSupplier') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= h($supplier->getName()); ?></h1>
        <p><?= Translation::of('comment') ?> : <?= h($supplier->getComment()); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/supplier/edit.php?id=<?= $supplier->getId();?>"><?= Translation::of('modifySupplierTitle') ?></a>
            <a class="btn btn-primary mt-3" href="/views/supplier/delete.php?id=<?= $supplier->getId();?>"><?= Translation::of('deleteSupplierTitle') ?></a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/supplier/list.php"><?= Translation::of('return') ?></a></div>
    </div>

<?php require '../footer.php'; ?>