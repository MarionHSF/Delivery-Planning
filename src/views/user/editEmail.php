<?php
require '../../functions.php';

if(!isset($_SESSION['auth'])){
    header('Location: /login.php?connexionOff=1');
    exit();
}

reconnectFromCookie();

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new \User\Users($pdo);
$errors = [];
try{
    $user = $users->find($_GET['id'] ?? null);
} catch (\Exception $e){
    e404();
} catch (\Error $e){
    e404();
}
$datas = [
    'email' => $user->getEmail(),
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validatesEmail($datas);
    if (empty($errors)){
        $users->updateEmail($user, $datas);
        header('Location: /views/user/user.php?id=' . $user->getId() . '&modificationEmail=1');
        exit();
    }
}

render('header', ['title' => Translation::of('modifyEmailTitle')]);

?>
    <div class="container">
        <h1><?= Translation::of('modifyEmailTitle') ?></h1>
        <form action="" method="post" class="form">
            <?php render('user/formEmail', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('modifyEmailTitle') ?></button>
            </div>
        </form>
        <a class="btn btn-primary mt-3" href="/views/user/user.php?id=<?= $user->getId();?>"><?= Translation::of('userReturn') ?></a>
    </div>

<?php require '../footer.php'; ?>