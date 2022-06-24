<?php
require 'functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$langs = new Lang\Langs($pdo);
$langs = $langs->getLangs();
$datas = [];

reconnectFromCookie();

if(isset($_SESSION['auth'])){
    if($_SESSION['auth']->getIdRole() == 1){
        header('Location: /views/event/add.php');
        exit();
    }else{
        header('Location: /views/user/adminDashboard.php');
        exit();
    }
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validatesConnexion($datas);
    if (empty($errors)){
        $users = new \User\Users($pdo);
        try{
            $user = $users->getConnexion($datas);
            $_SESSION['auth'] = $user;
            $_SESSION['lang'] = $langs[$user->getIdLang()-1]['code'];
            if($user->getIdRole() == 1){
                header('Location: /views/event/add.php?connexion=1');
            }else{
                header('Location: /views/user/adminDashboard.php?connexion=1');
            }
            exit();
        }catch (\Exception $e){
            $errors['errorConnexion'] = $e->getMessage();
        }
    }
}

require 'views/header.php';
?>

<?php if(isset($_GET['connexionOff'])): ?>
    <div class="container">
        <div class="alert alert-danger">
            <?= Translation::of('connexionOff') ?>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($_GET['creation'])): ?>
    <div class="container">
        <div class="alert alert-success">
            <?= Translation::of('confirmationEmail') ?>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($_GET['errorToken'])): ?>
    <div class="container">
        <div class="alert alert-danger">
            <?= Translation::of('errorToken') ?>
        </div>
    </div>
<?php endif; ?>
<?php if(isset($_GET['forgottenPassword'])): ?>
    <div class="container">
        <div class="alert alert-success">
            <?= Translation::of('forgottenPasswordText') ?>
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
<?php if(isset($_GET['logout'])): ?>
    <div class="container">
        <div class="alert alert-success">
            <?= Translation::of('logoutText') ?>
        </div>
    </div>
<?php endif; ?>
<div class="container mt-5">
    <div class="login d-flex flex-column align-items-center">
        <h1><?= Translation::of('accountConnexion') ?></h1>
        <?php if (isset($errors['errorConnexion'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['errorConnexion']; ?></small></p>
        <?php endif ?>
        <form action="" method="post" class="form">
            <div class="form-group mt-3">
                <label for="email"><?= Translation::of('email') ?></label>
                <input id="email" type="email" required class="form-control" name="email" value="<?= isset($datas['email']) ? h($datas['email']) : ''; ?>">
            </div>
            <?php if (isset($errors['email'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['email']; ?></small></p>
            <?php endif ?>
            <div class="form-group mt-3">
                <label for="password"><?= Translation::of('password') ?></label>
                <input id="password" type="password" required class="form-control" name="password">
            </div>
            <?php if (isset($errors['password'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['password']; ?></small></p>
            <?php endif ?>
            <div class="mt-3">
                <a href="/views/user/forget.php"><?= Translation::of('forgottenPassword') ?></a>
            </div>
            <div class="form-group mt-3">
                <input id="remember" type="checkbox"  name="remember">
                <label for="remember"><?= Translation::of('remember') ?></label>
            </div>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('connect') ?></button>
            </div>
        </form>
        <div class="mt-5">
            <p class="form-group mt-5"><?= Translation::of('noAccount') ?></p>
            <a href="/views/user/add.php" class="btn btn-primary"><?= Translation::of('createAccountTitle') ?></a>
        </div>
    </div>
</div>

<?php require 'views/footer.php'; ?>