<?php
require 'functions.php';

use Translation\Translation;

render('header', ['title' => Translation::of('privacyPolicy')]);

?>

<div class="container">
    <div class="d-flex justify-content-between my-5">
        <p><?= Translation::of('update') ?>
            <?php if($_SESSION['lang'] == "fr_FR"){
                echo '22/05/18';
            }else{
                echo '05/22/18';
            } ?>
        </p>
        <p><?= Translation::of('applicationDate') ?>
            <?php if($_SESSION['lang'] == "fr_FR"){
                echo '22/05/18';
            }else{
                echo '05/22/18';
            } ?>
        </p>
    </div>

    <h1 class="text-center mb-5 text-uppercase"><?= Translation::of('policyTitle') ?></h1>

    <ul class="mb-5">
        <li style="list-style:decimal;padding-left:10px"><a href="#1"><?= Translation::of('title1') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#2"><?= Translation::of('title2') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#3"><?= Translation::of('title3') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#4"><?= Translation::of('title4') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#5"><?= Translation::of('title5') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#6"><?= Translation::of('title6') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#7"><?= Translation::of('title7') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#8"><?= Translation::of('title8') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#9"><?= Translation::of('title9') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#10"><?= Translation::of('title10') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#11"><?= Translation::of('title11') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#12"><?= Translation::of('title12') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#13"><?= Translation::of('title13') ?></a></li>
        <li style="list-style:decimal;padding-left:10px"><a href="#14"><?= Translation::of('title14') ?></a></li>
    </ul>

    <h2 id="1" class="text-uppercase">1.&emsp;<?= Translation::of('title1') ?></h2>
    <p><?= Translation::of('paragraph1') ?></p>

    <h2 id="2" class="text-uppercase">2.&emsp;<?= Translation::of('title2') ?></h2>
    <p><?= Translation::of('paragraph2') ?></p>

    <h2 id="3" class="text-uppercase">3.&emsp;<?= Translation::of('title3') ?></h2>
    <p><?= Translation::of('paragraph3') ?></p>
    <ul><?= Translation::of('list3') ?></ul>

    <h2 id="4" class="text-uppercase">4.&emsp;<?= Translation::of('title4') ?></h2>
    <p><?= Translation::of('paragraph4') ?></p>

    <h2 id="5" class="text-uppercase">5.&emsp;<?= Translation::of('title5') ?></h2>
    <p><?= Translation::of('paragraph5') ?></p>
    <ul><?= Translation::of('list5') ?></ul>

    <h2 id="6" class="text-uppercase">6.&emsp;<?= Translation::of('title6') ?></h2>
    <p><strong><?= Translation::of('paragraph6') ?></strong></p>
    <ul><?= Translation::of('list6') ?></ul>
    <p><?= Translation::of('paragraph6bis') ?></p>

    <h2 id="7" class="text-uppercase">7.&emsp;<?= Translation::of('title7') ?></h2>
    <p><?= Translation::of('paragraph7') ?></p>

    <h2 id="8" class="text-uppercase">8.&emsp;<?= Translation::of('title8') ?></h2>
    <p><strong><?= Translation::of('paragraph8') ?></strong></p>

    <h2 id="9" class="text-uppercase">9.&emsp;<?= Translation::of('title9') ?></h2>
    <p><?= Translation::of('paragraph9') ?></p>

    <h2 id="10" class="text-uppercase">10.&emsp;<?= Translation::of('title10') ?></h2>
    <p><?= Translation::of('paragraph10') ?></p>

    <h2 id="11" class="text-uppercase">11.&emsp;<?= Translation::of('title11') ?></h2>
    <p><?= Translation::of('paragraph11') ?></p>
    <div class="px-5"><?= Translation::of('div11') ?></div>
    </br>
    <div class="px-5"><?= Translation::of('div11bis') ?></div>
    </br>
    <div class="px-5"><?= Translation::of('div11ter') ?></div>
    </br>
    <div class="px-5"><?= Translation::of('div11quater') ?></div>
    </br>
    <div class="px-5"><strong><?= Translation::of('div11quinquies') ?></strong></div>
    </br>
    <div class="px-5"><?= Translation::of('div11sexies') ?> :
        </br></br>
        <ul><?= Translation::of('list11') ?></ul>
    </div>

    <h2 id="12" class="text-uppercase">12.&emsp;<?= Translation::of('title12') ?></h2>
    <p><?= Translation::of('paragraph12') ?></p>

    <h2 id="13" class="text-uppercase">13.&emsp;<?= Translation::of('title3') ?></h2>
    <p><?= Translation::of('paragraph13') ?></p>

    <h2 id="14" class="text-uppercase">14.&emsp;<?= Translation::of('title14') ?></h2>
    <p><?= Translation::of('paragraph14') ?></p>
</div>

<?php require 'views/footer.php'; ?>


