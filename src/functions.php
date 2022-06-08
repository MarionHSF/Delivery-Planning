<?php
require 'vendor/autoload.php';

/* Storage session */
session_start();

/* Language */
use Translation\Translation;
Translation::setLocalesDir($_SERVER['DOCUMENT_ROOT'].'/locales');
if (isset($_SESSION['lang'])){
    Translation::forceLanguage($_SESSION['lang']);
}

/* Display 404page */
function e404(){
    require '404.php';
    exit();
}

/* Debug function */
function dd(...$vars){
    foreach ($vars as $var){
        echo '<pre>';
        var_export($var);
        echo '</pre>';

    }
}

/* Database connexion */
function get_pdo(): PDO{
    return new PDO('mysql:host=agenda_mysql_1:3306;dbname=agenda','agenda', 'agenda', [
        PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC

    ]);
}

/* escape html content in inputs */
function h(?string $value): string{
    if($value === null){
        return '';
    }
    return htmlentities($value);
}

/* Modify title tab depending event */
function render(string $view, $parameters = []) {
    extract($parameters);
    include "views/{$view}.php";
}
