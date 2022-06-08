<?php
use Translation\Translation;

$pdo = get_pdo();
$carriers = new Carrier\Carriers($pdo);
$carriers = $carriers->getCarriers();
$suppliers = new Supplier\Suppliers($pdo);
$suppliers = $suppliers->getSuppliers();
?>

<div class="row mt-5 d-flex align-items-center">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="id_carrier"><?= Translation::of('carrierName') ?></label>
            <select name="id_carrier" id="id_carrier" class="mx-3" required>
                <option value="<?= !empty($datas['id_carrier']) ? h($datas['id_carrier']) : ''; ?>"><?= !empty($datas['id_carrier']) ? $carriers[$datas['id_carrier']-1]['name'] : '--'.Translation::of('selectCarrier').'--'; ?></option>
                <?php foreach ($carriers as $carrier): ?>
                    <option value="<?= $carrier['id']; ?>"><?= $carrier['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_carrier'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['id_carrier']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group d-flex align-items-center">
            <label class="mr-3" for="ids_suppliers"><?= Translation::of('supplierName') ?></label>
            <select name="ids_suppliers[]" id="ids_suppliers" class="mx-3" required multiple size="4">
                <option value="" disabled>--<?=Translation::of('selectSupplier') ?>--</option>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id']; ?>"
                        <?php if(isset($datas['ids_suppliers'])){
                            foreach ($datas['ids_suppliers'] as $id_supplier){
                                if(isset($id_supplier['id_supplier']) && $id_supplier['id_supplier'] == $supplier['id']){
                                    echo 'selected';
                                }else{
                                    if($id_supplier == $supplier['id']){
                                       echo 'selected';
                                    }
                                }
                            }
                        } ?>
                    ><?= $supplier['name']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (isset($errors['ids_suppliers'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['ids_suppliers']; ?></small></p>
        <?php endif ?>
    </div>
</div>
<div class="form-group mt-3">
    <div id="divOrder">
        <label for="order"><?= Translation::of('orderNumber') ?> <?=Translation::of('orderNumberSmall') ?></label>
            <?php if (!isset($datas['order'])) { ?>
                <div class="col-sm-3 mb-2 d-flex" id="divOrder1">
                    <input id="inputOrder1" type="text" required class="form-control" name="order[]">
                </div>
            <?php }else{
                $i = 0;
                if(gettype($datas['order']) == 'string'){
                    foreach (explode(', ', $datas['order']) as $order){
                        $i++; ?>
                        <div class="col-sm-3 mb-2 d-flex" id="divOrder<?= $i ?>">
                            <input id="inputOrder<?= $i ?>" type="text" <?= ($i == 1) ? 'required' : ''; ?> class="form-control" name="order[]" value="<?= $order ?>">
                            <?php if($i != 1){ ?>
                                <a id="buttonOrder<?= $i ?>" class="btn btn-primary form remove" onclick=removeOrderInput(<?= $i ?>);>-</a>
                            <?php   } ?>
                        </div>
                    <?php }
                } elseif (gettype($datas['order']) == 'array'){
                    foreach ($datas['order'] as $order){
                        $i++; ?>
                        <div class="col-sm-3 mb-2 d-flex" id="divOrder<?= $i ?>">
                            <input id="inputOrder<?= $i ?>" type="text" <?= ($i == 1) ? 'required' : ''; ?> class="form-control" name="order[]" value="<?= $order ?>">
                            <?php if($i != 1){ ?>
                                <a id="buttonOrder<?= $i ?>" class="btn btn-primary form remove" onclick=removeOrderInput(<?= $i ?>);>-</a>
                            <?php   } ?>
                        </div>
                    <?php }
                }
            } ?>
        <?php if (isset($errors['order'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['order']; ?></small></p>
        <?php endif ?>
    </div>

    <div>
        <a id="buttonOrder" class="btn btn-primary form" onclick=addOrderInput();>+</a>
    </div>
</div>
<div class="form-group mt-3"> <?php // TODO ?>
    <label class="text-danger">Palettes / mètres linéaires (à faire en attente infos)</label>
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
    <div class="col-sm-6">
        <div class="form-group  mt-3">
            <label for="email"><?= Translation::of('email') ?></label>
            <input id="email" type="email" required class="form-control" name="email" value="<?= isset($datas['email']) ? h($datas['email']) : ''; ?>">
            <?php if (isset($errors['email'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['email']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <label for="dangerous_substance"><?= Translation::of('dangerousSubstance') ?> <?= Translation::of('dangerousSubstanceSmall') ?></label>
    <input id="dangerous_substance" type="checkbox" <?= isset($datas['dangerous_substance']) && ($datas['dangerous_substance'] == 'yes') ? 'checked' : ''; ?> name="dangerous_substance">
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group  mt-3">
            <label for="name">Titre</label>
            <input id="name" type="text" required class="form-control" name="name" value="<?= isset($datas['name']) ? h($datas['name']) : ''; ?>">
            <?php if (isset($errors['name'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['name']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group  mt-3">
            <label for="date"><?= Translation::of('date') ?></label>
            <input id="date" type="date" required class="form-control" name="date" value="<?= isset($datas['date']) ? h($datas['date']) : ''; ?>">
            <?php if (isset($errors['date'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['date']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="start">Heure de début</label>
            <input id="start" type="time" required class="form-control" name="start" value="<?= isset($datas['start']) ? h($datas['start']) : ''; ?>">
            <?php if (isset($errors['start'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['start']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            <label for="end">Heure de fin</label>
            <input id="end" type="time" required class="form-control" name="end" value="<?= isset($datas['end']) ? h($datas['end']) : ''; ?>">
            <?php if (isset($errors['end'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['end']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <label for="description">Description</label>
    <textarea name="description" id="description" required class="form-control"><?= isset($datas['description']) ? h($datas['description']) : ''; ?></textarea>
</div>
