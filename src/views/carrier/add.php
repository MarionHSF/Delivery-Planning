<?php
require '../../functions.php';

use Translation\Translation;
if (isset($_SESSION['lang'])){
    Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');
    Translation::forceLanguage($_SESSION['lang']);
}

$datas = [];
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Carrier\CarrierValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $carriers = new \Carrier\Carriers(get_pdo());
        $carrier = $carriers->hydrate(new \Carrier\Carrier(), $datas);
        $carriers->create($carrier);
        header('Location: /views/carrier/list.php?creation=1');
        exit();
    }
}

render('header', ['title' => Translation::of('createCarrierTitle')]);

?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <h1><?= Translation::of('createCarrierTitle') ?></h1>
    <form action="" method="post" class="form">
        <?php render('carrier/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary"><?= Translation::of('createCarrierTitle') ?></button>
        </div>
    </form>
    <a class="btn btn-primary mt-3" href="/views/carrier/list.php"><?= Translation::of('carriersListReturn') ?></a>
</div>

<?php require '../footer.php'; ?>