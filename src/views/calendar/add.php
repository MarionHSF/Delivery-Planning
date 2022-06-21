<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$datas = [
        'date' => $_GET['date'] ?? date('Y-m-d'),
];
$validator = new \App\Validator($datas);
if(!$validator->validate('date', 'date')){
   $datas['date'] =  date('Y-m-d');
};
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Calendar\EventValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $events = new \Calendar\Events($pdo);
        $event = $events->hydrate(new \Calendar\Event(), $datas);
        $events->create($event);
        if($_SESSION['auth']->getIdRole() == 1){
            header('Location: /views/user/userDashboard.php?id='.$_SESSION['auth']->getId().'&creation=1');
        }else{
            header('Location: /views/user/adminDashboard.php?creation=1');
        }
        exit();
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
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <h1><?= Translation::of('createAppointementTitle') ?></h1>
    <form action="" method="post" class="form">
        <?php render('calendar/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary"><?= Translation::of('createAppointementTitle') ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>