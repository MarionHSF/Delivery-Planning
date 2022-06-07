<?php
if(!isset($_SESSION['lang'])){
    require($_SERVER['DOCUMENT_ROOT'].'/lang/fr.php');
}else{
    if($_SESSION['lang'] == "french"){
        require($_SERVER['DOCUMENT_ROOT'].'/lang/fr.php');
    }elseif ($_SESSION['lang'] == "english"){
        require($_SERVER['DOCUMENT_ROOT'].'/lang/en.php');
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= isset($title) ? h($title) : $_title ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/general.css">
    <link rel="stylesheet" href="/css/calendar.css">
    <script src="/js/calendar.js" type="text/javascript"></script>
</head>

<body>

    <nav class="navbar navbar-dark bg-primary mb-3">
        <a href="/index.php" class="navbar-brand"><?= $_title ?></a>
        <div id="flag">
            <a href="/views/lang/fr.php"><img src='/images/french_flag.jpg' class='flag'/></a>
            <a href="/views/lang/en.php"><img src='/images/english_flag.jpg' class='flag'/></a>
        </div>
    </nav>



