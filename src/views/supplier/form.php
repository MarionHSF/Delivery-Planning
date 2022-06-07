<div class="row">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="name">Nom du fournisseur</label>
            <input id="name" type="text" required class="form-control" name="name" value="<?= isset($datas['name']) ? h($datas['name']) : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
                <small class="form-text text-muted"><?= $errors['name']; ?></small>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="comment">Commentaires</label>
            <input id="comment" type="textaera" class="form-control" name="comment" value="<?= isset($datas['comment']) ? h($datas['comment']) : ''; ?>">
            <?php if (isset($errors['comment'])) : ?>
                <small class="form-text text-muted"><?= $errors['comment']; ?></small>
            <?php endif ?>
        </div>
    </div>
</div>

