<?php
require '../../functions.php';

use Translation\Translation;

$pdo = get_pdo();
$carriers = new \Carrier\Carriers($pdo);
$errors = [];
try{
    $carrier = $carriers->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}
$datas = [
    'name' => $carrier->getName(),
    'comment' => $carrier->getComment()
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Carrier\CarrierValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $carriers->hydrate($carrier, $datas);
        $carriers->update($carrier);
        header('Location: /views/carrier/carrier.php?id=' . $carrier->getId() . '&modification=1');
        exit();
    }
}

render('header', ['title' => $carrier->getName()]);
?>
    <div class="container">
        <h1><?= h($carrier->getName()); ?></h1>
        <form action="" method="post" class="form">
            <?php render('carrier/form', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('modifyCarrierTitle') ?></button>
            </div>
        </form>
        <a  class="btn btn-primary mt-3" href="/views/carrier/list.php"><?= Translation::of('carriersListReturn') ?></a>
    </div>

<?php require '../footer.php'; ?>