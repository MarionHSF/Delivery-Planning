<?php
require '../../functions.php';

use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$datas = [];
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $datas = $_POST;
    $validator = new User\UserValidator();
    $errors = $validator->validates($datas);
    if (empty($errors)){
        $users = new \User\Users($pdo);
        $user = $users->hydrate(new \User\User(), $datas);
        $users->create($user);
        if($datas['id_role'] != 1){
            header('Location: /views/user/adminsList.php?creation=1');
        }else{
            header('Location: /views/user/customersList.php?creation=1');
        };
        exit();
    }
}

/*if(strpos($_SERVER['HTTP_REFERER'],"adminsList")){
    render('header', ['title' => Translation::of('createAdminTitle')]);
}else{
    render('header', ['title' => Translation::of('createCustomerTitle')]);
}*/
if(isset($_GET['admin'])){
    render('header', ['title' => Translation::of('createAdminTitle')]);
}else{
    render('header', ['title' => Translation::of('createAdminTitle')]);
}

?>

<div class="container">
    <?php if(!empty($errors)) : ?>
        <div class="alert alert-danger">
            <?= Translation::of('errorsMessage') ?>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['admin'])){ ?>
        <h1><?= Translation::of('createAdminTitle') ?></h1>
    <?php }else{ ?>
        <h1><?= Translation::of('createCustomerTitle') ?></h1>
    <?php } ?>
    <form action="" method="post" class="form">
        <?php render('user/form', ['datas' => $datas, 'errors' => $errors]); ?>
        <div class="form-group mt-3">
            <?php if(isset($_GET['admin'])){ ?>
                <button class="btn btn-primary"><?= Translation::of('createAdminTitle') ?></button>
            <?php }else{ ?>
                <button class="btn btn-primary"><?= Translation::of('createCustomerTitle') ?></button>
            <?php } ?>
        </div>
    </form>
    <?php if(isset($_GET['admin'])){ ?>
        <a class="btn btn-primary mt-3" href="/views/user/AdminsList.php"><?= Translation::of('adminsListReturn') ?></a>
    <?php }else{ ?>
        <a class="btn btn-primary mt-3" href="/views/user/CustomersList.php"><?= Translation::of('customersListReturn') ?></a>
    <?php } ?>
</div>

<?php require '../footer.php'; ?>