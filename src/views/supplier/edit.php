<?php
require '../../functions.php';

reconnectFromCookie();
isNotConnected();
onlySuperAdminRights();

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$suppliers = new \Supplier\Suppliers($pdo);
$errors = [];
try{
    $supplier = $suppliers->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}
$datas = [
    'name' => $supplier->getName(),
    'comment' => $supplier->getComment()
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Supplier\SupplierValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $suppliers->hydrate($supplier, $datas);
        $suppliers->update($supplier);
        header('Location: /views/supplier/supplier.php?id=' . $supplier->getId() . '&modification=1');
        exit();
    }
}

render('header', ['title' => Translation::of('modifySupplierTitle')]);
?>
    <div class="container">
        <?php if(isset($_GET['errorDB'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorDB') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= Translation::of('modifySupplierTitle');; ?></h1>
        <form action="" method="post" class="form">
            <?php render('supplier/form', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('modifySupplierTitle') ?></button>
            </div>
        </form>
        <a class="btn btn-primary mt-3" href="/views/supplier/supplier.php?id=<?= $supplier->getId();?>"><?= Translation::of('return') ?></a>
    </div>

<?php require '../footer.php'; ?>