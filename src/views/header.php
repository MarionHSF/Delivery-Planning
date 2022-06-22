<?php

use Translation\Translation;
Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');
if (isset($_SESSION['lang'])){
    Translation::forceLanguage($_SESSION['lang']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? h($title) : Translation::of('title') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/general.css">
    <link rel="stylesheet" href="/css/calendar.css">
</head>

<body>

    <nav class="navbar navbar-dark bg-primary mb-3 px-3">
        <?php if(!isset($_SESSION['auth'])){ ?>
            <a href="/login.php" class="navbar-brand"><img src='/images/logo-Henry-Schein.webp' class='logo'/></a>
        <?php }elseif($_SESSION['auth']->getIdRole() == 1){ ?>
            <a href="/views/calendar/add.php" class="navbar-brand"><img src='/images/logo-Henry-Schein.webp' class='logo'/></a>
        <?php }else{ ?>
            <a href="/views/user/adminDashboard.php" class="navbar-brand"><img src='/images/logo-Henry-Schein.webp' class='logo'/></a>
        <?php } ?>
        <div class="d-flex flex-column align-items-end">
            <div id="flag">
                <a href="/views/lang/fr.php"><img src='/images/french_flag.jpg' class='flag'/></a>
                <a href="/views/lang/en.php"><img src='/images/english_flag.jpg' class='flag'/></a>
            </div>
            <?php if(!empty($_SESSION['auth'])):?>
            <div class="mt-3">
                <p class="text-dark text-decoration-none" href=""><?= Translation::of('hello') ?> <?=$_SESSION['auth']->getFirstName() ?> <?=$_SESSION['auth']->getName() ?>,</p>
                <div class="d-flex justify-content-end">
                    <?php if($_SESSION['auth']->getIdRole() == 1){ ?>
                        <a class="toggler text-dark text-decoration-none" href="#" id="toggler"><?= Translation::of('account') ?>&nbsp;/&nbsp;</a>
                        <div id="toggler-content" class="toggler-content p-2 bg-info" style="display: none;">
                            <a class="text-dark text-decoration-none" href="/views/user/userDashboard.php?id=<?= $_SESSION['auth']->getId();?>">* <?= Translation::of('appointement') ?></a>
                            <a class="text-dark text-decoration-none" href="/views/user/user.php?id=<?= $_SESSION['auth']->getId();?>">* <?= Translation::of('personalInfos') ?></a>
                        </div>
                    <?php }else{ ?>
                        <a class="text-dark text-decoration-none" href="/views/user/user.php?id=<?= $_SESSION['auth']->getId();?>"><?= Translation::of('account') ?>&nbsp;/&nbsp;</a>
                    <?php } ?>
                    <a class="text-dark text-decoration-none" href="/views/user/logout.php"><?= Translation::of('logout') ?></a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>

