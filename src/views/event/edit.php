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

// Impossible to modify an appointement if there is not a 24h delay
$date = new \DateTime(date('Y-m-d H:i:s'));
$limitDate = $event->getStart()->modify('-24 hours');
if($_SESSION['auth']->getIdRole() == 1 && $date > $limitDate){
    header('Location: /views/event/event.php?id='.$event->getId().'&limitDate=1');
    exit();
}

//Impossible to modify the appointment on D+1 if 2pm is passed on D day (only super admin), restriction applied on saturday and sunday
$dateLimit = date('Y-m-d');
$day = (new \DateTime(date('Y-m-d')))->format('l');
if($day === "Saturday"){
    $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
}elseif ($day === "Sunday"){
    $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d');
}
if($_SESSION['auth']->getIdRole() != 4) {
    $actualDateTime = date('Y-m-d H:i');
    $limitDateTime = date('Y-m-d 14:00');
    if ($actualDateTime < $limitDateTime) {
        $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d');
        $day = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('l');
        if ($day === "Saturday") {
            $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+3 days')->format('Y-m-d');
        } elseif ($day === "Sunday") {
            $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
        }
    } else {
        $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
        $day = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('l');
        if ($day === "Saturday") {
            $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+4 days')->format('Y-m-d');
        } elseif ($day === "Sunday") {
            $dateLimit = (new \DateTime(date('Y-m-d')))->modify('+3 days')->format('Y-m-d');
        }
    }
}

$datas = [
        'id_carrier' => $carrier[0]['id'],
        'ids_suppliers' => $ids_suppliers,
        'order' => $event->getOrder(),
        'pallet_format' => $event->getPalletFormat(),
        'pallet_number' => $event->getPalletNumber(),
        'floor_meter' => $event->getFloorMeter(),
        'phone' => $event->getPhone(),
        'email' => $event->getEmail(),
        'dangerous_substance' => $event->getDangerousSubstance(),
        'date' => $event->getStart()->format('Y-m-d'),
        'start' => $event->getStart()->format('H:i'),
        'end' => $event->getEnd()->format('H:i'),
        'comment' => $event->getComment(),
        'uploadFiles' => $files,
        'dateLimit' => $dateLimit
];

$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
$floorMeterMax = $floorsMeterMax->find()->getFloorMeter();
$days = new \Day\Days($pdo);
try{
    $day = $days->find(new \DateTime($dateLimit));
    $dayFloorMeter = $day->getFloorMeter();
    if($dayFloorMeter >= $floorMeterMax){
        $limitFloorMeterReached = 'yes';
    }else{
        $limitFloorMeterReached = 'no';
    }
}catch (\Exception){
    $limitFloorMeterReached = 'no';
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $datas['dateLimit'] = $dateLimit;
    $datas['uploadFiles2'] = $files;
    $validator = new Event\EventValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
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
        <?php render('event/form', ['datas' => $datas, 'errors' => $errors, 'limitFloorMeterReached' => $limitFloorMeterReached]); ?>
        <div class="form-group mt-3">
            <button id="submitForm" class="btn btn-primary"><?= Translation::of('modifyAppointementTitle') ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>