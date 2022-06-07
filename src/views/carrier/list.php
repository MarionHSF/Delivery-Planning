<?php
require '../../functions.php';

$pdo = get_pdo();
$carriers = new Carrier\Carriers($pdo);
$carriers = $carriers->getCarriers();
render('header', ['title' => 'Liste des transporteurs']);
?>

    <div class="container">
        <div class="carrier">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        Le transporteur a bien été enregistré.
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['supression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        Le transporteur a bien été supprimé.
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1>Liste des transporteurs</h1>
            </div>

            <table class="carrier_table">
                <?php foreach ($carriers as $carrier): ?>
                    <tr>
                        <td><?= $carrier['name']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/carrier/carrier.php?id=<?= $carrier['id'];?>">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/carrier/add.php">Ajouter un transporteur</a>
        </div>
    </div>

<?php require '../footer.php'; ?>