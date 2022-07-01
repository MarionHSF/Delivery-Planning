<?php
require 'functions.php';

use Translation\Translation;

render('header', ['title' => Translation::of('privacyPolicy')]);

?>

<div class="container">
    <div class="mt-5">
        <h1 class="text-center mb-5 text-uppercase"><?= Translation::of('contactUs') ?></h1>
        <p class="mt-5"><?= Translation::of('byEmail') ?> : <a href="mailto:test@test.fr">test@test.fr</a></p> <?php //TODO ?>
        <p class="mt-5"><?= Translation::of('byPhone') ?> : XX-XX-XX-XX-XX</p><?php //TODO ?>
    </div>
</div>

<?php require 'views/footer.php'; ?>


