<?php
use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$langs = new Lang\Langs($pdo);
$langs = $langs->getLangs();
$roles = new Role\Roles($pdo);
$roles = $roles->getRoles();
?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="company_name"><?= Translation::of('company_name') ?></label>
            <input id="company_name" type="text" required class="form-control" name="company_name" value="<?= isset($datas['company_name']) ? h($datas['company_name']) : ''; ?>">
            <?php if (isset($errors['company_name'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['company_name']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="name"><?= Translation::of('name') ?></label>
            <input id="name" type="text" required class="form-control" name="name" value="<?= isset($datas['name']) ? h($datas['name']) : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['name']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="firstname"><?= Translation::of('firstname') ?></label>
            <input id="firstname" type="text" required class="form-control" name="firstname" value="<?= isset($datas['firstname']) ? h($datas['firstname']) : ''; ?>">
            <?php if (isset($errors['firstname'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['firstname']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group  mt-3">
            <label for="phone"><?= Translation::of('phoneNumber') ?></label>
            <input id="phone" type="tel" required class="form-control" name="phone" pattern="[0-9]{10}" value="<?= isset($datas['phone']) ? h($datas['phone']) : ''; ?>">
            <?php if (isset($errors['phone'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['phone']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="email"><?= Translation::of('email') ?></label>
            <input id="email" type="email" required class="form-control" name="email" value="<?= isset($datas['email']) ? h($datas['email']) : ''; ?>">
            <?php if (isset($errors['email'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['email']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="email_verif"><?= Translation::of('email_verif') ?></label>
            <input id="email_verif" type="email" required class="form-control" name="email_verif" onpaste="return false;">
            <?php if (isset($errors['email_verif'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['email_verif']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="password"><?= Translation::of('password') ?> <small>(<?= Translation::of('passwordSmall') ?>)</small></label>
            <input id="password" type="password" required class="form-control" name="password" minlength="6">
            <?php if (isset($errors['password'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['password']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="password_verif"><?= Translation::of('password_verif') ?></label>
            <input id="password_verif" type="password" required class="form-control" name="password_verif" minlength="6" onpaste="return false;" >
            <?php if (isset($errors['password_verif'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['password_verif']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="id_lang"><?= Translation::of('lang') ?></label>
            <select name="id_lang" id="id_lang" class="mx-3" required>
                <option value="<?= !empty($datas['id_lang']) ? h($datas['id_lang']) : ''; ?>"><?= !empty($datas['id_lang']) ? Translation::of($langs[$datas['id_lang']]['name']) : '--'.Translation::of('selectLang').'--'; ?></option>
                <?php foreach ($langs as $lang): ?>
                    <option value="<?= $lang['id']; ?>"><?= Translation::of($lang['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_lang'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['id_lang']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <p><?= Translation::of('userRole') ?></p>
    <div class="d-flex flex-column">
        <?php foreach ($roles as $role): ?>
            <div>
                <input id="id_role_<?= $role['id'] ?>" type="radio" name="id_role" value="<?= $role['id'] ?>"
                    <?php if(isset($datas['id_role']) && ($datas['id_role'] == $role['id'])){
                        echo 'checked';
                    }elseif (!isset($datas['id_role']) && $role['id'] == "2"){
                        echo 'checked';
                    } ?>
                <label for="id_role_<?= $role['id'] ?>"> <?= Translation::of($role['name']) ?></label>
            </div>
        <?php endforeach; ?>
    </div>
</div>














