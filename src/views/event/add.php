<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

//Impossible to add an appointment on D+1 if 2pm is passed on D day (only super admin), restriction applied on saturday and sunday
$date = date('Y-m-d');
$day = (new \DateTime(date('Y-m-d')))->format('l');
if($day === "Saturday"){
    $date = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
}elseif ($day === "Sunday"){
    $date = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d');
}
if($_SESSION['auth']->getIdRole() != 4) {
    $actualDateTime = date('Y-m-d H:i');
    $limitDateTime = date('Y-m-d 14:00');
    if($actualDateTime < $limitDateTime){
        $date = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d');
        $day = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('l');
        if($day === "Saturday"){
            $date = (new \DateTime(date('Y-m-d')))->modify('+3 days')->format('Y-m-d');
        }elseif ($day === "Sunday"){
            $date = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
        }
    }else{
        $date = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('Y-m-d');
        $day = (new \DateTime(date('Y-m-d')))->modify('+2 days')->format('l');
        if($day === "Saturday"){
            $date = (new \DateTime(date('Y-m-d')))->modify('+4 days')->format('Y-m-d');
        }elseif ($day === "Sunday"){
            $date = (new \DateTime(date('Y-m-d')))->modify('+3 days')->format('Y-m-d');
        }
    }
}

$datas = [
        'date' => $date,
];

$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
$floorMeterMax = $floorsMeterMax->find()->getFloorMeter();
$days = new \Day\Days($pdo);
try{
    $day = $days->find(new \DateTime($date));
    $dayFloorMeter = $day->getFloorMeter();
    if($dayFloorMeter >= $floorMeterMax){
        $limitFloorMeterReached = 'yes';
    }else{
        $limitFloorMeterReached = 'no';
    }
}catch (\Exception){
    $limitFloorMeterReached = 'no';
}

$validator = new \App\Validator($datas);
if(!$validator->validate('date', 'date')){
    $datas['date'] = (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d');
};
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
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
            $events = new \Event\Events($pdo);
            $event = $events->hydrate(new \Event\Event(), $datas);
            try{
                $events->create($event);
                if($_SESSION['auth']->getIdRole() == 1){
                    header('Location: /views/user/userDashboard.php?id='.$_SESSION['auth']->getId().'&creation=1');
                }else{
                    header('Location: /views/user/adminDashboard.php?creation=1');
                }
                exit();
            }catch (\Exception $e){
                $errors['errorFindByEmail'] = $e->getMessage();
            }
        }
    }
}

render('header', ['title' => Translation::of('createAppointementTitle')]);

?>

<div class="container">
    <?php if(isset($_GET['connexion'])): ?>
        <div class="container">
            <div class="alert alert-success">
                <?= Translation::of('connexionText') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['creation'])): ?>
        <div class="container">
            <div class="alert alert-success">
                <?= Translation::of('createAppointement') ?>
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
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <h1><?= Translation::of('createAppointementTitle') ?></h1>
    <form action="" method="post" class="form" enctype="multipart/form-data">
        <?php render('event/form', ['datas' => $datas, 'errors' => $errors, 'limitFloorMeterReached' => $limitFloorMeterReached]); ?>
        <div class="form-group mt-3">
            <button id="submitForm" class="btn btn-primary"><?= Translation::of('createAppointementTitle') ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>