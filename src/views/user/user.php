<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndSuperAdminRights();

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


if($_SESSION['auth']->getId() != $user->getId()){
    render('header', ['title' => Translation::of('accountInfo')]);
}else {
    render('header', ['title' => Translation::of('account')]);
}
?>

    <div class="container">
        <?php if(isset($_GET['connexionOff'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('connexionOff') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['validation'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('createAccount') ?>
                </div>
            </div>
        <?php endif; ?>
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
        <?php if(isset($_GET['modificationEmail'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifyEmail') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['modificationPassword'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifyPassword') ?>
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
        <?php if($_SESSION['auth']->getId() != $user->getId()){ ?>
             <h1 class="mb-3"><?= Translation::of('accountInfo') ?></h1>
        <?php }else{?>
             <h1 class="mb-3"><?= Translation::of('account') ?></h1>
        <?php }?>
        <p><?= Translation::of('name') ?> : <?= h($user->getName()); ?></p>
        <p><?= Translation::of('firstname') ?> : <?= h($user->getFirstName()); ?></p>
        <p><?= Translation::of('phoneNumber') ?> : <?= h($user->getPhone()); ?></p>
        <p><?= Translation::of('email') ?> : <?= h($user->getEmail()); ?></p>
        <p><?= Translation::of('lang') ?> : <?= h(Translation::of($langs->find($user->getIdLang())->getName())); ?></p>
        <p><?= Translation::of('userRole') ?> : <?= h(Translation::of($roles->find($user->getIdRole())->getName())); ?></p>
        <div>
            <a class="btn btn-primary mt-3" href="/views/user/edit.php?id=<?= $user->getId();?>"><?= Translation::of('modifyUserTitle') ?></a>
            <?php if($_SESSION['auth']->getIdRole() == 4){ ?>
            <a class="btn btn-primary mt-3" href="/views/user/delete.php?id=<?= $user->getId();?>"><?= Translation::of('deleteUserTitle') ?></a>
        </div>
                <?php if($user->getIdRole() != 1 && $_SESSION['auth']->getId() != $user->getId()){ ?>
                    <div> <a class="btn btn-primary mt-3" href="/views/user/adminsList.php"><?= Translation::of('return') ?></a></div>
                <?php }elseif($user->getIdRole() != 1 && $_SESSION['auth']->getId() == $user->getId()){?>
                    <div> <a class="btn btn-primary mt-3" href="/views/user/adminDashboard.php"><?= Translation::of('return') ?></a></div>
                <?php }else{?>
                    <div> <a class="btn btn-primary mt-3" href="/views/user/customersList.php"><?= Translation::of('return') ?></a></div>
                <?php }?>
            <?php }elseif($user->getId() == $_SESSION['auth']->getId()){ ?>
        </div>
            <div> <a class="btn btn-primary mt-3" href="/views/event/add.php"><?= Translation::of('return') ?></a></div>
            <?php } ?>
    </div>

<?php require '../footer.php'; ?>