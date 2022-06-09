<?php
require '../../functions.php';

use Translation\Translation;

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
        header('Location: /?creation=1');
        exit();
    }
}

render('header', ['title' => Translation::of('createAppointementTitle')]);

?>

<div class="container">
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