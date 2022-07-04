<?php
use Translation\Translation;
?>

<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name"><?= Translation::of('supplierName2') ?></label>
            <input id="name" type="text" required class="form-control" name="name" value="<?= isset($datas['name']) ? h($datas['name']) : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['name']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group mt-3">
        <label for="reserved_14h"><?= Translation::of('reserved') ?> <?= Translation::of('reserved14hSmall') ?></label>
        <input id="reserved_14h" type="checkbox" <?= isset($datas['reserved_14h']) && ($datas['reserved_14h'] == 'yes') ? 'checked' : ''; ?> name="reserved_14h">
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="comment"><?= Translation::of('comment') ?></label>
            <input id="comment" type="textaera" class="form-control" name="comment" value="<?= isset($datas['comment']) ? h($datas['comment']) : ''; ?>">
            <?php if (isset($errors['comment'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['comment']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>

