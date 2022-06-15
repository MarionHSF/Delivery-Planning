<?php

require '../../functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$datas = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validatesForgetPassword($datas);
    if (empty($errors)){
        $users = new \User\Users($pdo);
        try{
            $user = $users->forgotPassword($datas);
            header('Location: /login.php?forgottenPassword=1');
            exit();
        }catch (\Exception $e){
            $errors['errorForgottenPassword'] = $e->getMessage();
        }
    }
}

render('header', ['title' => Translation::of('forgottenPassword')]);
?>

<div class="container mt-5">
    <div class="login d-flex flex-column align-items-center">
        <h1><?= Translation::of('forgottenPassword') ?></h1>
        <?php if (isset($errors['errorForgottenPassword'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['errorForgottenPassword']; ?></small></p>
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
                <button class="btn btn-primary"><?= Translation::of('continue') ?></button>
            </div>
        </form>
    </div>
</div>

<?php require '../footer.php'; ?>
