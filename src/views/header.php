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
    <script src="/js/calendar.js" type="text/javascript"></script>
</head>

<body>

    <nav class="navbar navbar-dark bg-primary mb-3 px-3">
        <a href="/index.php" class="navbar-brand"><img src='/images/logo-Henry-Schein.webp' class='logo'/></a> <?php //TODO redirection à changer si connecté ou non ?>
        <div class="d-flex flex-column align-items-center">
            <div id="flag">
                <a href="/views/lang/fr.php"><img src='/images/french_flag.jpg' class='flag'/></a>
                <a href="/views/lang/en.php"><img src='/images/english_flag.jpg' class='flag'/></a>
            </div>
            <?php if(!empty($_SESSION['auth'])):?>
            <div class="mt-3">
                <a class="toggler text-dark text-decoration-none" href="#" id="toggler"><?= Translation::of('account') ?></a>
                <div id="toggler-content" class="toggler-content p-2 bg-info" style="display: none;">
                    <a class="text-dark text-decoration-none" href="/views/user/userDashboard.php">* <?= Translation::of('appointement') ?></a>
                    <a class="text-dark text-decoration-none" href="/views/user/user.php?id=<?= $_SESSION['auth']->getId();?>">* <?= Translation::of('personalInfos') ?></a>
                </div>
                <a class="text-dark text-decoration-none" href="/views/user/logout.php"><?= Translation::of('logout') ?></a>
            </div>
            <?php endif; ?>
        </div>
    </nav>

