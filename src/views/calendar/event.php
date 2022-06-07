<?php
require '../../functions.php';

$pdo = get_pdo();
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
            <li>Date de prise du rendez-vous : <?= $event->getEntryDate()->format('d/m/Y'); ?> à <?= $event->getEntryDate()->format('H:i'); ?></li>
            <li><?= $_carrier ?> : <?= $carrier ?></li>
            <li>Fournisseur(s) : <?php
                                    foreach ($ids_suppliers as $id_supplier) {
                                        $suppliersName[] = $suppliers->find($id_supplier['id_supplier'])->getName();
                                    }
                                    echo implode(', ',$suppliersName);
                                ?></li>
            <li>Numéros de commandes : <?= h($event->getOrder()); ?></li>
            <li>Numéro de téléphone : <?= h($event->getPhone()); ?></li>
            <li>Email : <?= h($event->getEmail()); ?></li>
            <li>Matières dangereuses : <?= h($event->getDangerousSubstance()); ?></li>
            <li>Date : <?= $event->getStart()->format('d/m/Y'); ?></li>
            <li>Heure de début : <?= $event->getStart()->format('H:i'); ?></li>
            <li>Heure de fin : <?= $event->getEnd()->format('H:i'); ?></li>
            <li>
                Description : <br>
                <?= h($event->getDescription()); ?>
            </li>
        </ul>
        <div>
            <a class="btn btn-primary" href="/views/calendar/edit.php?id=<?= $event->getId();?>">Modifier le rendez-vous</a>
            <a class="btn btn-primary" href="/views/calendar/delete.php?id=<?= $event->getId();?>">Supprimer le rendez-vous</a>
        </div>
    </div>

<?php require '../footer.php'; ?>