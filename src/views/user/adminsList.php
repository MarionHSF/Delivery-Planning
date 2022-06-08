<?php
require '../../functions.php';

use Translation\Translation;

$pdo = get_pdo();
$admins = new User\Users($pdo);
$admins = $admins->getAdminUsers();
render('header', ['title' => Translation::of('adminsList')]);
?>

    <div class="container">
        <div class="adminsList">
            <?php if(isset($_GET['creation'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('createAdmin') ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['supression'])): ?>
                <div class="container">
                    <div class="alert alert-success">
                        <?= Translation::of('deleteAdmin') ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
                <h1><?= Translation::of('adminsList') ?></h1>
            </div>

            <table class="admins_table">
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?= $admin['name']; ?></td>
                        <td><?= $admin['firstname']; ?></td>
                        <td><a class="btn btn-primary mx-3" href="/views/carrier/carrier.php?id=<?= $admin['id'];?>"> <?= Translation::of('see') ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <div>
            <a class="btn btn-primary mt-3" href="/views/user/add.php"><?=Translation::of('createAdminTitle')  ?></a>
        </div>
    </div>

<?php require '../footer.php'; ?>