<?php
require '../../functions.php';

$pdo = get_pdo();
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

render('header', ['title' => $supplier->getName()]);
?>
    <div class="container">
        <h1><?= h($supplier->getName()); ?></h1>
        <form action="" method="post" class="form">
            <?php render('supplier/form', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary">Modifier le fournisseur</button>
            </div>
        </form>
        <a  class="btn btn-primary mt-3" href="/views/supplier/list.php">Retour à la liste des fournisseurs</a>
    </div>

<?php require '../footer.php'; ?>