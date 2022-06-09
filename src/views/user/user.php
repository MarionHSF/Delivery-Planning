<?php
require '../../functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$langs = new \Lang\Langs($pdo);
$roles = new \Role\Roles($pdo);

if(!isset($_GET['id'])){
    e404();
}
try{
    $user = $users->find($_GET['id']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $user->getCompanyName()]);
?>

    <div class="container">
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?php if($user->getIdRole() != 1){ ?>
                        <?= Translation::of('modifyAdmin') ?>
                    <?php }else{ ?>
                        <?= Translation::of('modifyCustomer') ?>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorUser') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= h($user->getCompanyName()); ?></h1>
        <p><?= Translation::of('name') ?> : <?= h($user->getName()); ?></p>
        <p><?= Translation::of('firstname') ?> : <?= h($user->getFirstName()); ?></p>
        <p><?= Translation::of('phoneNumber') ?> : <?= h($user->getPhone()); ?></p>
        <p><?= Translation::of('email') ?> : <?= h($user->getEmail()); ?></p>
        <p><?= Translation::of('lang') ?> : <?= h(Translation::of($langs->find($user->getIdLang())->getName())); ?></p>
        <p><?= Translation::of('userRole') ?> : <?= h(Translation::of($roles->find($user->getIdRole())->getName())); ?></p>
        <div>
            <?php if($user->getIdRole() != 1){ ?>
                <a class="btn btn-primary mt-3" href="/views/user/edit.php?id=<?= $user->getId();?>"><?= Translation::of('modifyAdminTitle') ?></a>
                <a class="btn btn-primary mt-3" href="/views/user/delete.php?id=<?= $user->getId();?>"><?= Translation::of('deleteAdminTitle') ?></a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/user/adminsList.php"><?= Translation::of('adminsListReturn') ?></a></div>
            <?php }else{ ?>
                <a class="btn btn-primary mt-3" href="/views/user/edit.php?id=<?= $user->getId();?>"><?= Translation::of('modifyCustomerTitle') ?></a>
                <a class="btn btn-primary mt-3" href="/views/user/delete.php?id=<?= $user->getId();?>"><?= Translation::of('deleteCustomerTitle') ?></a>
        </div>
        <div> <a class="btn btn-primary mt-3" href="/views/user/customersList.php"><?= Translation::of('customersListReturn') ?></a></div>
            <?php }?>
    </div>

<?php require '../footer.php'; ?>