<?php
require '../../functions.php';

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
        $events = new \Calendar\Events(get_pdo());
        $event = $events->hydrate(new \Calendar\Event(), $datas);
        $events->create($event);
        header('Location: /?creation=1');
        exit();
    }
}

render('header', ['title' => $_createAppointementTitle]);
?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>
    <h1><?= $_createAppointementTitle ?></h1>
    <form action="" method="post" class="form">
        <?php render('calendar/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary"><?= $_createAppointementTitle ?></button>
        </div>
    </form>
</div>

<?php require '../footer.php'; ?>