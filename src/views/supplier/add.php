<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$datas = [];
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Supplier\SupplierValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $suppliers = new \Supplier\Suppliers($pdo);
        $supplier = $suppliers->hydrate(new \Supplier\Supplier(), $datas);
        $suppliers->create($supplier);
        header('Location: /views/supplier/list.php?creation=1');
        exit();
    }
}

render('header', ['title' => Translation::of('createSupplierTitle')]);

?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
           <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <h1><?= Translation::of('createSupplierTitle') ?></h1>
    <form action="" method="post" class="form">
        <?php render('supplier/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary"><?= Translation::of('createSupplierTitle') ?></button>
        </div>
    </form>
    <a class="btn btn-primary mt-3" href="/views/supplier/list.php"><?= Translation::of('return') ?></a>
</div>

<?php require '../footer.php'; ?>