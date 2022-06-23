<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new \Calendar\Events($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $event = $events->find($_GET['id']);
    $carrier = $events->findCarrier($_GET['id']);
    $suppliers = $events->findSuppliers($_GET['id']);
    $files = $events->findUploadFiles($_GET['id']);
    dd($files);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => Translation::of('appointementDetails')]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifyAppointement') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['limitDate'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('limitDate') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['reception'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('receptionValidationText') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['storage'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('storageValidationText') ?>
                </div>
            </div>
        <?php endif; ?>
       <h1><?= Translation::of('appointementDetails') ?> : </h1>
        <ul>
            <li><?= Translation::of('entryDate') ?> : <?= $event->getEntryDate()->format('d/m/Y'); ?></li>
            <li><?= Translation::of('carrier') ?> : <?= $carrier['0']['name'] ?></li>
            <li><?= Translation::of('supplier') ?> : <?php
                foreach ($suppliers as $supplier) {
                    $suppliersName[] = $supplier['name'];
                }
                echo implode(', ',$suppliersName); ?>
            </li>
            <li><?= Translation::of('orderNumber') ?> : <?= h($event->getOrder()); ?></li>
            <li><?= Translation::of('attachments') ?> : </br><?php
                foreach ($files as $file) { ?>
                    <a href="/uploadFiles/<?= $file['name'] ?>" target="_blank"><?= $file['name'] ?></a>
                    </br>
                <?php } ?>
            </li>




            <li><?= Translation::of('phoneNumber') ?> : <?= h($event->getPhone()); ?></li>
            <li><?= Translation::of('email') ?> : <?= h($event->getEmail()); ?></li>
            <li><?= Translation::of('dangerousSubstance') ?> : <?= $event->getDangerousSubstance() == "yes" ? Translation::of('yes') : Translation::of('no') ?></li>
            <li><?= Translation::of('date') ?> : <?= $event->getStart()->format('d/m/Y'); ?></li>
            <li>Heure de début : <?= $event->getStart()->format('H:i'); ?></li>
            <li>Heure de fin : <?= $event->getEnd()->format('H:i'); ?></li>
            <?php if($event->getComment()){ ?>
                <li>
                    <?= Translation::of('comment') ?> : <br>
                    <?= h($event->getComment()); ?>
                </li>
            <?php } ?>
            <?php if($_SESSION['auth']->getIdRole() != 1){ ?>
                <div class="border border-dark mt-2 py-3">
                    <li class="mx-5"><?= Translation::of('receptionValidation') ?> : <?= Translation::of($event->getReceptionValidation()) ?></li>
                    <?php if($event->getReceptionValidation() == "yes"){ ?>
                        <li class="mx-5">Date / <?= Translation::of('receptionTime') ?> : <?= $event->getReceptionDate()->format('d/m/Y H:i'); ?></li>
                        <li class="mx-5"><?= Translation::of('receptionLine') ?> : <?= $event->getReceptionLine(); ?></li>
                        <li class="mx-5"><?= Translation::of('storageValidation') ?> : <?= Translation::of($event->getStorageValidation()) ?></li>
                    <?php } ?>
                </div>
            <?php } ?>
        </ul>
        <div>
            <a class="btn btn-primary" href="/views/calendar/edit.php?id=<?= $event->getId();?>"><?= Translation::of('modifyAppointementTitle') ?></a>
            <a class="btn btn-primary" href="/views/calendar/delete.php?id=<?= $event->getId();?>"><?= Translation::of('deleteAppointementTitle') ?></a>
        </div>
        <?php if($_SESSION['auth']->getIdRole() == 2 || $_SESSION['auth']->getIdRole() == 4){ ?>
            <?php if($event->getReceptionValidation() == 'no'){ ?>
                <div> <a class="btn btn-primary mt-3" href="/views/calendar/receptionValidation.php?id=<?= $event->getId() ?>"><?= Translation::of('receptionValidation') ?></a></div>
            <?php }?>
            <?php if($event->getReceptionValidation() == 'yes' && $event->getStorageValidation() == 'no'){ ?>
                <div> <a class="btn btn-primary mt-3" href="/views/calendar/storageValidation.php?id=<?= $event->getId() ?>"><?= Translation::of('storageValidation') ?></a></div>
            <?php }?>
        <?php }?>
        <div>
            <?php if($_SESSION['auth']->getIdRole() == 1){ ?>
                <div> <a class="btn btn-primary mt-3" href="/views/user/userDashboard.php?id=<?= $_SESSION['auth']->getId() ?>"><?= Translation::of('return') ?></a></div>
            <?php }else{ ?>
                    <?php if(strpos($_SERVER['HTTP_REFERER'], 'userDashboard')){ ?>
                        <div> <a class="btn btn-primary mt-3" href="<?= $_SERVER['HTTP_REFERER'] ?>"><?= Translation::of('return') ?></a></div>
                    <?php }else{?>
                        <div> <a class="btn btn-primary mt-3" href="/views/user/adminDashboard.php"><?= Translation::of('return') ?></a></div>
                    <?php }?>
            <?php }?>
        </div>
    </div>

<?php require '../footer.php'; ?>