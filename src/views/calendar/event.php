<?php
require '../../functions.php';

use Translation\Translation;

if(!isset($_SESSION['auth'])){
    header('Location: /login.php?connexionOff=1');
    exit();
}

reconnectFromCookie();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new \Calendar\Events($pdo);
$carriers =  new \Carrier\Carriers($pdo);
$suppliers =  new \Supplier\Suppliers($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $event = $events->find($_GET['id']);
    $carrier = $carriers->find($event->getIdCarrier())->getName();
    $ids_suppliers = $events->findIdsSuppliers($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $event->getName()]);
?>

    <div class="container">
       <h1><?= h($event->getName()); ?></h1>
        <ul>
            <li><?= Translation::of('entryDate') ?> : <?= $event->getEntryDate()->format('d/m/Y'); ?></li>
            <li><?= Translation::of('carrier') ?> : <?= $carrier ?></li>
            <li><?= Translation::of('supplier') ?> : <?php
                                    foreach ($ids_suppliers as $id_supplier) {
                                        $suppliersName[] = $suppliers->find($id_supplier['id_supplier'])->getName();
                                    }
                                    echo implode(', ',$suppliersName);
                                ?></li>
            <li><?= Translation::of('orderNumber') ?> : <?= h($event->getOrder()); ?></li>
            <li><?= Translation::of('phoneNumber') ?> : <?= h($event->getPhone()); ?></li>
            <li><?= Translation::of('email') ?> : <?= h($event->getEmail()); ?></li>
            <li><?= Translation::of('dangerousSubstance') ?> : <?= $event->getDangerousSubstance() == "yes" ? Translation::of('yes') : Translation::of('no') ?></li>
            <li><?= Translation::of('date') ?> : <?= $event->getStart()->format('d/m/Y'); ?></li>
            <li>Heure de début : <?= $event->getStart()->format('H:i'); ?></li>
            <li>Heure de fin : <?= $event->getEnd()->format('H:i'); ?></li>
            <li>
                Description : <br>
                <?= h($event->getDescription()); ?>
            </li>
        </ul>
        <div>
            <a class="btn btn-primary" href="/views/calendar/edit.php?id=<?= $event->getId();?>"><?= Translation::of('modifyAppointementTitle') ?></a>
            <a class="btn btn-primary" href="/views/calendar/delete.php?id=<?= $event->getId();?>"><?= Translation::of('deleteAppointementTitle') ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>