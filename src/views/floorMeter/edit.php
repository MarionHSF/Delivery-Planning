<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$floorsMeterMax = new \FloorMeterMax\FloorsMeterMax($pdo);
$errors = [];
try{
    $floorMeterMax = $floorsMeterMax->find();
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}
$datas = [
    'floor_meter_max' => $floorMeterMax->getFloorMeter()
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new FloorMeterMax\FloorMeterMaxValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $floorsMeterMax->hydrate($floorMeterMax, $datas);
        $floorsMeterMax->update($floorMeterMax);
        header('Location: /views/floorMeter/floorMeter.php?modification=1');
        exit();
    }
}

render('header', ['title' => Translation::of('modifyFloorMeterMaxTitle')]);
?>
    <div class="container">
        <?php if(isset($_GET['errorDB'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorDB') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= Translation::of('modifyFloorMeterMaxTitle');; ?></h1>
        <form action="" method="post" class="form mt-5">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <label for="floor_meter_max"><?= Translation::of('numberMax') ?></label>
                        <input id="floor_meter_max" type="number" required class="form-control" name="floor_meter_max" value="<?= isset($datas['floor_meter_max']) ? h($datas['floor_meter_max']) : ''; ?>">
                        <?php if (isset($errors['floor_meter_max'])) : ?>
                            <p><small class="form-text text-danger"><?= $errors['floor_meter_max']; ?></small></p>
                        <?php endif ?>
                    </div>
                </div>
            </div>
            <div class="form-group mt-5">
                <button class="btn btn-primary"><?= Translation::of('modifyFloorMeterMaxTitle') ?></button>
            </div>
        </form>
        <a class="btn btn-primary mt-3" href="/views/floorMeter/floorMeter.php"><?= Translation::of('return') ?></a>
    </div>

<?php require '../footer.php'; ?>