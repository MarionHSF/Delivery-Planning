<?php
use Translation\Translation;
?>

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
