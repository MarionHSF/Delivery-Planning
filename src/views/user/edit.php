<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndSuperAdminRights();

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
    'id' => $user->getId(),
    'company_name' => $user->getCompanyName(),
    'name' => $user->getName(),
    'firstname' => $user->getFirstname(),
    'phone' => $user->getPhone(),
    'email' => $user->getEmail(),
    'id_lang' => $user->getIdLang(),
    'id_role' => $user->getIdRole(),
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validatesEdit($datas);
    if (empty($errors)){
        $users->hydrate($user, $datas);
        $users->update($user);
        header('Location: /views/user/user.php?id=' . $user->getId() . '&modification=1');
        exit();
    }
}

render('header', ['title' => Translation::of('modifyUserTitle')]);

?>
    <div class="container">
        <h1><?= Translation::of('modifyUserTitle') ?></h1>
        <form action="" method="post" class="form">
            <?php render('user/form', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <button class="btn btn-primary"><?= Translation::of('modifyUserTitle') ?></button>
            </div>
        </form>
        <a class="btn btn-primary mt-3" href="/views/user/user.php?id=<?= $user->getId();?>"><?= Translation::of('return') ?></a>
    </div>

<?php require '../footer.php'; ?>