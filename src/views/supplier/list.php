<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$suppliers = new Supplier\Suppliers($pdo);
$suppliers = $suppliers->getSuppliers();
render('header', ['title' => Translation::of('suppliersList')]);
?>

    <div class="container">
        <div class="supplier">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('createSupplier') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['supression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('deleteSupplier') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1><?= Translation::of('suppliersList') ?></h1>
            </div>

            <table class="supplier_table">
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= $supplier['name']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/supplier/supplier.php?id=<?= $supplier['id'];?>"><?= Translation::of('see') ?></h1></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/supplier/add.php"><?= Translation::of('createSupplierTitle') ?></a>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/user/adminDashboard.php"><?=Translation::of('return')  ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>