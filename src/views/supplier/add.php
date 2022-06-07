<?php
require '../../functions.php';

$datas = [];
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new Supplier\SupplierValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $suppliers = new \Supplier\Suppliers(get_pdo());
        $supplier = $suppliers->hydrate(new \Supplier\Supplier(), $datas);
        $suppliers->create($supplier);
        header('Location: /views/supplier/list.php?creation=1');
        exit();
    }
}

render('header', ['title' => 'Ajouter un fournisseur']);
?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>
    <h1>Ajouter un fournisseur</h1>
    <form action="" method="post" class="form">
        <?php render('supplier/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary">Ajouter le fournisseur</button>
        </div>
    </form>
    <a class="btn btn-primary mt-3" href="/views/supplier/list.php">Retour à la liste des fournisseurs</a>
</div>

<?php require '../footer.php'; ?>