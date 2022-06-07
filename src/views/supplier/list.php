<?php
require '../../functions.php';

$pdo = get_pdo();
$suppliers = new Supplier\Suppliers($pdo);
$suppliers = $suppliers->getSuppliers();
render('header', ['title' => 'Liste des fournisseurs']);
?>

    <div class="container">
        <div class="supplier">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        Le fournisseur a bien été enregistré.
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['supression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        Le fournisseur a bien été supprimé.
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1>Liste des fournisseurs</h1>
            </div>

            <table class="supplier_table">
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= $supplier['name']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/supplier/supplier.php?id=<?= $supplier['id'];?>">Voir</a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/supplier/add.php">Ajouter un fournisseur</a>
        </div>
    </div>

<?php require '../footer.php'; ?>