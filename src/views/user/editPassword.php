<?php
require '../../functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$datas = [];
$errors = [];
try{
    $user = $users->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validatesPassword($datas);
    if (empty($errors)){
        $users->updatePassword($user, $datas);
        if($_GET['resetPassword']){
            header('Location: /login.php?modificationPassword=1');
        }else{
            header('Location: /views/user/user.php?id=' . $user->getId() . '&modificationPassword=1');
        }
        exit();
    }
}

render('header', ['title' => Translation::of('modifyPasswordTitle')]);

?>
    <div class="container">
        <?php if(isset($_GET['errorDB'])): ?>
            <div class="container">
                <div class="alert alert-danger">
                    <?= Translation::of('errorDB') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1><?= Translation::of('modifyPasswordTitle') ?></h1>
        <form action="" method="post" class="form">
            <?php render('user/formPassword', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('modifyPasswordTitle') ?></button>
            </div>
        </form>
        <a class="btn btn-primary mt-3" href="/views/user/edit.php?id=<?= $user->getId();?>"><?= Translation::of('return') ?></a>
    </div>

<?php require '../footer.php'; ?>