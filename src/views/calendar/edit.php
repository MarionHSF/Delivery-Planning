<?php
require '../../functions.php';

use Translation\Translation;

$pdo = get_pdo();
$events = new \Calendar\Events($pdo);
$errors = [];
try{
    $event = $events->find($_GET['id'] ?? null);
    $ids_suppliers = $events->findIdsSuppliers($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}
$datas = [
        'id_carrier' => $event->getIdCarrier(),
        'ids_suppliers' => $ids_suppliers,
        'order' => $event->getOrder(),
        'phone' => $event->getPhone(),
        'email' => $event->getEmail(),
        'dangerous_substance' => $event->getDangerousSubstance(),
        'name' => $event->getName(),
        'date' => $event->getStart()->format('Y-m-d'),
        'start' => $event->getStart()->format('H:i'),
        'end' => $event->getEnd()->format('H:i'),
        'description' => $event->getDescription()
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Calendar\EventValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $events->hydrate($event, $datas);
        $events->update($event);
        header('Location: /?modification=1');
        exit();
    }
}

render('header', ['title' => $event->getName()]);
?>
<div class="container">
    <h1><?= h($event->getName()); ?></h1>
    <form action="" method="post" class="form">
        <?php render('calendar/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary"><?= Translation::of('modifyAppointementTitle') ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>