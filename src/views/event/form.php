<?php
use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$carriers = new Carrier\Carriers($pdo);
$carriers = $carriers->getCarriers();
$suppliers = new Supplier\Suppliers($pdo);
$suppliers = $suppliers->getSuppliers();
$users = new User\Users($pdo);
$emailsList = $users->getUsersConfirmedEmail();
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
<div class="row mt-4">
    <div class="col-sm-8">
        <div class="form-group">
            <label for="uploadFiles[]" class="mb-2"><?= Translation::of('attachments') ?> <?= Translation::of('attachmentsText') ?></label>
            </br>
            <?php if(isset($datas['uploadFiles'])){
                $i = 0; ?>
                <div id="uploadFile">
                    <?php foreach ($datas['uploadFiles'] as $uploadFile){?>
                        <div id="uploadFile<?= $uploadFile['id'] ?>">
                            <a href="/views/file/file.php?fileId=<?= $uploadFile['id'] ?>&eventId=<?= $_GET['id'] ?>" target="_blank"><?= $uploadFile['name'] ?></a>
                            <a class="text-decoration-none" onclick=removeUploadFile(<?= $uploadFile['id'] ?>);>&#128465;</a>
                        </div>
                    <?php }?>
                </div>
            <?php }?>
            <?php if(isset($datas['uploadFiles2'])){
                $i = 0; ?>
                <div id="uploadFile">
                    <?php foreach ($datas['uploadFiles2'] as $uploadFile){?>
                        <div id="uploadFile<?= $uploadFile['id'] ?>">
                            <a href="/views/file/file.php?fileId=<?= $uploadFile['id'] ?>&eventId=<?= $_GET['id'] ?>" target="_blank"><?= $uploadFile['name'] ?></a>
                            <a class="text-decoration-none" onclick=removeUploadFile(<?= $uploadFile['id'] ?>);>&#128465;</a>
                        </div>
                    <?php }?>
                </div>
            <?php }?>
            <input type="button" id="loadFileXml" value="<?= Translation::of('browse') ?>" onclick="document.getElementById('uploadFiles').click();" class="mt-2" />
            <input style="display:none" type="file" id="uploadFiles" name="uploadFiles[]" multiple="multiple" accept="image/*,.pdf" class="mt-2" <?php
                                                                                                                                        if(!isset($datas['uploadFiles']) && !isset($datas['uploadFiles2']))  {
                                                                                                                                            echo 'required';
                                                                                                                                        }elseif ((isset($datas['uploadFiles']) && empty($datas['uploadFiles'])) || (isset($datas['uploadFiles2']) && empty($datas['uploadFiles2']))){
                                                                                                                                            echo 'required';
                                                                                                                                        }else{
                                                                                                                                            echo '';
                                                                                                                                        }
                                                                                                                                        ?>/>
            <p id="loading"><?= Translation::of('emptyFile') ?></p>
            <?php if($_SESSION['lang'] == 'fr_FR'){ ?>
                <p id="errorFileFR" class="text-danger"></p>
                <p id="errorDBFR" class="text-danger"></p>
            <?php }?>
            <?php if($_SESSION['lang'] == 'en_GB'){ ?>
                <p id="errorFileEN" class="text-danger"></p>
                <p id="errorDBEN" class="text-danger"></p>
            <?php }?>
            <?php if (isset($errors['errorUploadFiles'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['errorUploadFiles']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="form-group">
        <div>
            <p><?= Translation::of('palletFormat') ?> : </p>
            <input id="standardFormat" type="radio" name="pallet_format" value="standard_format" <?= !isset($datas['pallet_format']) ? "checked" : ($datas['pallet_format'] == "standard_format" ? "checked" : "") ?>>
            <label for="standardFormat"><?= Translation::of('standardFormat') ?></label>
            &emsp;&emsp;
            <input id="otherFormat" type="radio" name="pallet_format" value="other_format" <?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "other_format" ? "checked" : "") ?>>
            <label for="otherFormat"><?= Translation::of('otherFormat') ?></label>
        </div>
        <?php if (isset($errors['pallet_format'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['pallet_format']; ?></small></p>
        <?php endif ?>
    </div>
    <div id="standardFormatDiv" class="mt-3" <?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "standard_format" ? "" : "style='display:none'") ?>>
        <div class="form-group">
            <label for="palletNumberStandardFormat"><?= Translation::of('palletNumber') ?></label>
            <input id="palletNumberStandardFormat" type="number" name="pallet_number" min="1" placeholder="ex : 1" required <?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "standard_format" ? "" : "disabled=''") ?> value="<?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "standard_format" ? $datas['pallet_number'] : "") ?>">
        </div>
        <?php if (isset($errors['pallet_number'])) : ?>
            <p><small class="form-text text-danger"><?= $errors['pallet_number']; ?></small></p>
        <?php endif ?>
    </div>
    <div id="otherFormatDiv" class="mt-3" <?= !isset($datas['pallet_format']) ? "style='display:none'" : ($datas['pallet_format'] == "other_format" ? "" : "style='display:none'") ?>>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="palletNumberOtherFormat"><?= Translation::of('palletNumber') ?></label>
                        <input id="palletNumberOtherFormat" type="number" name="pallet_number" min="1" placeholder="ex : 10" required <?= !isset($datas['pallet_format']) ? "disabled=''" : ($datas['pallet_format'] == "other_format" ? "" : "disabled=''") ?> value="<?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "other_format" ? $datas['pallet_number'] : "") ?>">
                    </div>
                </div>
                <?php if (isset($errors['pallet_number'])) : ?>
                    <p><small class="form-text text-danger"><?= $errors['pallet_number']; ?></small></p>
                <?php endif ?>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <div class="form-group">
                        <label for="floor_meter"><?= Translation::of('floorMeter') ?></label>
                        <input id="floor_meter" type="number" name="floor_meter" min="0.01" step="0.01" placeholder="ex 0.01 ou 0.1 ou 1" required <?= !isset($datas['pallet_format']) ? "disabled=''" : ($datas['pallet_format'] == "other_format" ? "" : "disabled=''") ?> value="<?= !isset($datas['pallet_format']) ? "" : ($datas['pallet_format'] == "other_format" ? $datas['floor_meter'] : "") ?>">
                    </div>
                </div>
                <?php if (isset($errors['floor_meter'])) : ?>
                    <p><small class="form-text text-danger"><?= $errors['floor_meter']; ?></small></p>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div class="row mt-4">
    <div class="col-sm-6">
        <div class="form-group">
            <label for="email"><?= Translation::of('email') ?></label>
            <div>
                <?php if($_SESSION['auth']->getIdRole() == 1){ ?>
                    <input id="email" type="email" required class="form-control" name="email" value="<?= isset($datas['email']) ? h($datas['email']) : $_SESSION['auth']->getEmail(); ?>">
                <?php } else { ?>
                    <select name="email" id="email2" class="mx-3" required>
                        <option value="<?= !empty($datas['email']) ? h($datas['email']) : ''; ?>"><?= !empty($datas['email']) ? h($datas['email']) : '--'.Translation::of('selectEmail').'--'; ?></option>
                        <?php foreach ($emailsList as $email): ?>
                            <option value="<?= $email['email']; ?>"><?= $email['email']; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }?>
                <?php if (isset($errors['email'])) : ?>
                    <p><small class="form-text text-danger"><?= $errors['email']; ?></small></p>
                <?php endif ?>
                <?php if (isset($errors['errorFindByEmail'])) : ?>
                    <p><small class="form-text text-danger"><?= $errors['errorFindByEmail']; ?></small></p>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="phone"><?= Translation::of('phoneNumber') ?></label>
            <?php if($_SESSION['auth']->getIdRole() == 1){ ?>
                <input id="phone" type="tel" required class="form-control" name="phone" pattern="[0-9]{10}" value="<?= isset($datas['phone']) ? h($datas['phone']) : $_SESSION['auth']->getPhone(); ?>">
            <?php } else {?>
                <input id="phone" type="tel" required class="form-control" name="phone" pattern="[0-9]{10}" value="<?= isset($datas['phone']) ? h($datas['phone']) : '' ?>">
            <?php } ?>
            <?php if (isset($errors['phone'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['phone']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="form-group mt-3">
    <label for="dangerous_substance"><?= Translation::of('dangerousSubstance') ?> <?= Translation::of('dangerousSubstanceSmall') ?></label>
    <input id="dangerous_substance" type="checkbox" <?= isset($datas['dangerous_substance']) && ($datas['dangerous_substance'] == 'yes') ? 'checked' : ''; ?> name="dangerous_substance">
</div>
<div class="row mt-3">
    <div class="col-sm-6">
        <div class="form-group ">
            <label for="date"><?= Translation::of('date') ?></label>
            <?php if(strpos($_SERVER['REQUEST_URI'], 'add')){ ?>
                <input id="date" type="date" required class="form-control" name="date" min="<?= isset($datas['date']) ? $datas['date'] : (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d') ?>" value="<?= isset($datas['date']) ? h($datas['date']) : (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d'); ?>">
            <?php }else{ //if edit ?>
                <input id="date" type="date" required class="form-control" name="date" min="<?= isset($datas['dateLimit']) ? $datas['dateLimit'] : (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d') ?>" value="<?= isset($datas['date']) ? h($datas['date']) : (isset($datas['dateLimit']) ? h($datas['dateLimit']) : (new \DateTime(date('Y-m-d')))->modify('+1 days')->format('Y-m-d')); ?>">
            <?php }?>
            <?php if (isset($errors['date'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['date']; ?></small></p>
            <?php endif ?>
            <?php if($_SESSION['lang'] == 'fr_FR'){ ?>
                <p id="errorPickerFR" class="text-danger"></p>
            <?php }?>
            <?php if($_SESSION['lang'] == 'en_GB'){ ?>
                <p id="errorPickerEN" class="text-danger"></p>
            <?php }?>
        </div>
    </div>
</div>
<div class="row mt-3"> <?php //TODO ?>
    <div class="col-sm-6">
        <div class="form-group">
            <label for="start">Heure de début</label>
            <p id="startAlert" class="text-danger" <?= !isset($datas['start']) && $limitFloorMeterReached == "yes" ? "" : "style='display:none'" ?>><?= Translation::of('errorStartLimitFloorMeter') ?></p>
            <input id="start" type="time" required class="form-control" name="start" value="<?= isset($datas['start']) ? h($datas['start']) : ''; ?>" <?= !isset($datas['start']) && $limitFloorMeterReached == "yes" ? "style='display:none'" : "" ?>>
            <?php if (isset($errors['start'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['start']; ?></small></p>
            <?php endif ?>
            <?php if($_SESSION['lang'] == 'fr_FR'){ ?>
                <p id="scheduleFR" class="text-danger"></p>
            <?php }?>
            <?php if($_SESSION['lang'] == 'en_GB'){ ?>
                <p id="scheduleEN" class="text-danger"></p>
            <?php }?>
        </div>
    </div>
    <div class="col-sm-6"> <?php //TODO ?>
        <div class="form-group">
            <label for="end">Heure de fin</label>
            <input id="end" type="time" required class="form-control" name="end" value="<?= isset($datas['end']) ? h($datas['end']) : ''; ?>">
            <?php if (isset($errors['end'])) : ?>
                <p><small class="form-text text-danger"><?= $errors['end']; ?></small></p>
            <?php endif ?>
        </div>
    </div>
</div>
<div class="form-group mt-4">
    <label for="comment"><?= Translation::of('comment') ?> (<?= Translation::of('optional') ?>)</label>
    <textarea name="comment" id="comment" class="form-control"><?= isset($datas['comment']) ? h($datas['comment']) : ''; ?></textarea>
</div>
