<?php
require '../../functions.php';

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
    'company_name' => $user->getCompanyName(),
    'name' => $user->getName(),
    'firstname' => $user->getFirstname(),
    'phone' => $user->getPhone(),
    'email' => $user->getEmail(),
    'lang' => $user->getIdLang(),
    'is_admin' => $user->getIdRole(),
];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $users->hydrate($user, $datas);
        $users->update($user);
        header('Location: /views/user/user.php?id=' . $user->getId() . '&modification=1');
        exit();
    }
}

if($user->getIdRole() != 1 ){
    render('header', ['title' => Translation::of('modifyAdminTitle')]);
}else{
    render('header', ['title' => Translation::of('modifyCustomerTitle')]);
}

?>
    <div class="container">
        <?php if($user->getIdRole() != 1 ){ ?>
            <h1><?= Translation::of('modifyAdminTitle') ?></h1>
        <?php }else{ ?>
            <h1><?= Translation::of('modifyCustomerTitle') ?></h1>
        <?php } ?>
        <form action="" method="post" class="form">
            <?php render('user/form', ['datas' => $datas, 'errors' => $errors]); ?>
            <div class="form-group mt-3">
                <?php if($user->getIdRole() != 1 ){ ?>
                    <button class="btn btn-primary"><?= Translation::of('modifyAdminTitle') ?></button>
                <?php }else{ ?>
                    <button class="btn btn-primary"><?= Translation::of('modifyCustomerTitle') ?></button>
                <?php } ?>
            </div>
        </form>
        <?php if($user->getIdRole() != 1 ){ ?>
            <a class="btn btn-primary mt-3" href="/views/user/adminsList.php"><?= Translation::of('adminsListReturn') ?></a>
        <?php }else{ ?>
            <a class="btn btn-primary mt-3" href="/views/user/customersList.php"><?= Translation::of('customersListReturn') ?></a>
        <?php } ?>
    </div>

<?php require '../footer.php'; ?>