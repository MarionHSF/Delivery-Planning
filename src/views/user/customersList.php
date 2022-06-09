<?php
require '../../functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$customers = new User\Users($pdo);
$customers = $customers->getCustomersUsers();
render('header', ['title' => Translation::of('customersList')]);
?>

    <div class="container">
        <div class="adminsList">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('createCustomer') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['supression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('deleteCustomer') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1><?= Translation::of('customersList') ?></h1>
            </div>

            <table class="admins_table">
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['company_name']; ?></td>
                        <td><?= $customer['name']; ?></td>
                        <td><?= $customer['firstname']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/user/user.php?id=<?= $customer['id'];?>"> <?= Translation::of('see') ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/user/add.php?customer=1"><?=Translation::of('createCustomerTitle')  ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>