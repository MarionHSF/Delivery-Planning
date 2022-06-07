<?php
require '../../functions.php';

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

render('header', ['title' => 'Ajouter un transporteur']);
?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            Merci de corriger vos erreurs
        </div>
    <?php endif; ?>
    <h1>Ajouter un transporteur</h1>
    <form action="" method="post" class="form">
        <?php render('carrier/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <button class="btn btn-primary">Ajouter le transporteur</button>
        </div>
    </form>
    <a class="btn btn-primary mt-3" href="/views/carrier/list.php">Retour à la liste des transporteurs</a>
</div>

<?php require '../footer.php'; ?>