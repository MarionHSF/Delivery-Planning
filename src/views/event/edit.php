<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndAdminExcept2Rights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new \Event\Events($pdo);
$errors = [];
try{
    $event = $events->find($_GET['id'] ?? null);
    $carrier = $events->findCarrier($_GET['id'] ?? null);
    $suppliers = $events->findSuppliers($_GET['id'] ?? null);
    $files = $events->findUploadFiles($_GET['id'] ?? null);
    $ids_suppliers = [];
    foreach ($suppliers as $supplier){
        $ids_suppliers[] = $supplier['id'];
    }
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

$date = new \DateTime(date('Y-m-d H:i:s'));
$limitDate = $event->getStart()->modify('-24 hours');
if($_SESSION['auth']->getIdRole() == 1 && $date > $limitDate){
    header('Location: /views/event/event.php?id='.$event->getId().'&limitDate=1');
    exit();
}

$datas = [
        'id_carrier' => $carrier[0]['id'],
        'ids_suppliers' => $ids_suppliers,
        'order' => $event->getOrder(),
        'phone' => $event->getPhone(),
        'email' => $event->getEmail(),
        'dangerous_substance' => $event->getDangerousSubstance(),
        'date' => $event->getStart()->format('Y-m-d'),
        'start' => $event->getStart()->format('H:i'),
        'end' => $event->getEnd()->format('H:i'),
        'comment' => $event->getComment(),
        'uploadFiles' => $files
];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Event\EventValidator();
    $errors = $validator->validates($datas);
    $uploadResult = uploadFiles($errors, $datas);
    if(key_exists('errorUploadFiles', $uploadResult)){
        $errors['errorUploadFiles'] = $uploadResult['errorUploadFiles'];
    }elseif (key_exists('uploadFiles', $uploadResult)){
        foreach ($uploadResult['uploadFiles'] as $uploadFile){
            $datas['uploadFiles'][] = $uploadFile;
        }
    }
    if (empty($errors)){
        $events->hydrate($event, $datas);
        try{
            $events->update($event);
            header('Location: /views/event/event.php?id='. $event->getId() .'&modification=1');
            exit();
        }catch (\Exception $e){
            $errors['errorFindByEmail'] = $e->getMessage();
        }
    }
}

render('header', ['title' => Translation::of('modifyAppointementTitle')]);
?>
<div class="container">
    <?php if(isset($_GET['errorDB'])): ?>
        <div class="container">
            <div class="alert alert-danger">
                <?= Translation::of('errorDB') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <h1><?= Translation::of('modifyAppointementTitle') ?></h1>
    <form action="" method="post" class="form" enctype="multipart/form-data">
        <?php render('event/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button id="submitForm" class="btn btn-primary"><?= Translation::of('modifyAppointementTitle') ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>