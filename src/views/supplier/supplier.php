<?php
require '../../functions.php';

$pdo = get_pdo();
$suppliers = new \Supplier\Suppliers($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $supplier = $suppliers->find($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $supplier->getName()]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    Le fournisseur a bien été modifié.
                </div>
            </div>
        <?php endif; ?>
        <h1><?= h($supplier->getName()); ?></h1>
        <p>Commentaires : <?= h($supplier->getComment()); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/supplier/edit.php?id=<?= $supplier->getId();?>">Modifier le fournisseur</a>
            <a class="btn btn-primary mt-3" href="/views/supplier/delete.php?id=<?= $supplier->getId();?>">Supprimer le fournisseur</a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/supplier/list.php">Retour à la liste des fournisseurs</a></div>
    </div>

<?php require '../footer.php'; ?>