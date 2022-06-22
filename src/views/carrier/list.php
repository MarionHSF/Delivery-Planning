<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$carriers = new Carrier\Carriers($pdo);
$carriers = $carriers->getCarriers();
render('header', ['title' => Translation::of('carriersList')]);
?>

    <div class="container">
        <div class="carrier">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('createCarrier') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['suppression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('deleteCarrier') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1><?= Translation::of('carriersList') ?></h1>
            </div>

            <table class="carrier_table">
                <?php foreach ($carriers as $carrier): ?>
                    <tr>
                        <td><?= $carrier['name']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/carrier/carrier.php?id=<?= $carrier['id'];?>"> <?= Translation::of('see') ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/carrier/add.php"><?=Translation::of('createCarrierTitle')  ?></a>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/user/adminDashboard.php"><?=Translation::of('return')  ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>