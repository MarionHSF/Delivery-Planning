<?php
require '../../functions.php';

$pdo = get_pdo();
$carriers = new \Carrier\Carriers($pdo);
if(!isset($_GET['id'])){
    e404();
}
try{
    $carrier = $carriers->find($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $carrier->getName()]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    Le transporteur a bien été modifié.
                </div>
            </div>
        <?php endif; ?>
        <h1><?= h($carrier->getName()); ?></h1>
        <p>Commentaires : <?= h($carrier->getComment()); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/carrier/edit.php?id=<?= $carrier->getId();?>">Modifier le transporteur</a>
            <a class="btn btn-primary mt-3" href="/views/carrier/delete.php?id=<?= $carrier->getId();?>">Supprimer le transporteur</a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/carrier/list.php">Retour à la liste des transporteurs</a></div>
    </div>

<?php require '../footer.php'; ?>