<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
receptionValidationRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$datas = [
    'date' => date('Y-m-d'),
];
$validator = new \App\Validator($datas);
if(!$validator->validate('reception_date', 'date')){
    $datas['date'] =  date('Y-m-d');
};
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Event\EventValidator();
    $errors = $validator->validatesReceptionValidation($datas);
    if (empty($errors)){
        $events = new \Event\Events($pdo);
        $event = $events->find($_GET['id']);
        $events->validationReception($event, $datas);
        header('Location: /views/event/event.php?id='.$_GET['id'].'&reception=1');
        exit();
    }
}

render('header', ['title' => Translation::of('receptionValidation')]);

?>

    <div class="container">
        <?php if(!empty($errors)) : ?>
            <div class="alert alert-danger">
                <?= Translation::of('errorsMessage') ?>
            </div>
        <?php endif; ?>
        <h1><?= Translation::of('receptionValidation') ?></h1>
        <form action="" method="post" class="form">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group  mt-3">
                        <label for="date"><?= Translation::of('date') ?></label>
                        <input id="date" type="date" required class="form-control" name="date" value="<?= isset($datas['date']) ? h($datas['date']) : ''; ?>">
                        <?php if (isset($errors['date'])) : ?>
                            <p><small class="form-text text-danger"><?= $errors['date']; ?></small></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group mt-3">
                        <label for="start"><?= Translation::of('receptionTime') ?></label>
                        <input id="start" type="time" required class="form-control" name="start" value="<?= isset($datas['start']) ? h($datas['start']) : ''; ?>">
                        <?php if (isset($errors['start'])) : ?>
                            <p><small class="form-text text-danger"><?= $errors['start']; ?></small></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="form-group mt-3">
                <label for="reception_line"><?= Translation::of('receptionLine') ?></label>
                <textarea name="reception_line" id="reception_line" class="form-control" required><?= isset($datas['reception_line']) ? h($datas['reception_line']) : ''; ?></textarea>
                <?php if (isset($errors['reception_line'])) : ?>
                    <p><small class="form-text text-danger"><?= $errors['reception_line']; ?></small></p>
                <?php endif ?>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('receptionValidation') ?></button>
            </div>
        </form>
    </div>

<?php require '../footer.php'; ?>