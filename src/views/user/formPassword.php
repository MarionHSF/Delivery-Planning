<?php
use Translation\Translation;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="password"><?= Translation::of('password') ?> <small>(<?= Translation::of('passwordSmall') ?>)</small></label>
            <input id="password" type="password" required class="form-control" name="password" minlength="8">
            <?php if (isset($errors['password'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['password']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="password_verif"><?= Translation::of('password_verif') ?></label>
            <input id="password_verif" type="password" required class="form-control" name="password_verif" minlength="8" onpaste="return false;" >
            <?php if (isset($errors['password_verif'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['password_verif']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>