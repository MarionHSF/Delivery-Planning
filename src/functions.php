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

/* Escape html content in inputs */
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

/* Server smtp configuration */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
function initSmtp($mail){
    $mail->SMTPDebug = SMTP::DEBUG_CONNECTION;                      //Enable verbose debug output
    $mail->isSMTP();                                                //Send using SMTP
    $mail->Host       = 'mailserver';                               //Set the SMTP server to send through
    $mail->SMTPAuth   = false;                                      //Enable SMTP authentication
    $mail->Username   = '';                                         //SMTP username
    $mail->Password   = '';                                         //SMTP password
    //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;              //Enable implicit TLS encryption
    $mail->Port       = 1025;
    $mail->CharSet    = 'UTF-8';
}

/* Automatic redirection if not connect */
function isNotConnected(){
    if(!isset($_SESSION['auth'])){
        header('Location: /login.php?connexionOff=1');
        exit();
    }
}

/* Automatic reconnexion from cookie */
function reconnectFromCookie(){
    $pdo = new PDO\PDO();
    $pdo = $pdo->get_pdo();
    if(isset($_COOKIE['remember']) && !isset($_SESSION['auth'])){
        $parts = explode('//', $_COOKIE['remember']);
        $id_user = $parts[0];
        $users = new \User\Users($pdo);
        $user = $users->find($id_user);
        if($user){
            $expected = $id_user . '//' . $user->getRememberToken() . sha1($id_user . 'HenrySchein');
            if($expected == $_COOKIE['remember']){
                $_SESSION['auth'] = $user;
                setcookie('remember', $_COOKIE['remember'], time() + 60 * 60 * 24 * 7, '/'); // cookie sur 7 jours
                //header('Location: /views/calendar/add.php');
            }else{
                setcookie('remember', NULL, -1, '/');
            }
        }else{
            setcookie('remember', NULL, -1, '/');
            header('Location: /login.php');
        }
    }
}

/* Rights only for super admin */
function onlySuperAdminRights(){
    if($_SESSION['auth']->getIdRole() != 4){
        e404();
        exit();
    }
}

/* Rights only for connected user & super admin */
function onlyConnectedUserAndSuperAdminRights(){
    if($_GET['id'] != $_SESSION['auth']->getId() && $_SESSION['auth']->getIdRole() != 4){
        e404();
        exit;
    }
}

/* Rights only for admin */
function onlyAdminRights(){
    if($_SESSION['auth']->getIdRole() == 1){
        e404();
        exit;
    }
}

/* Rights only for validation */
function receptionValidationRights(){
    if($_SESSION['auth']->getIdRole() == 1 || $_SESSION['auth']->getIdRole() == 3){
        e404();
        exit;
    }
}

/* Upload Files */
function uploadFiles($errors, $datas){
    $files = array();

    foreach ($_FILES['uploadFiles'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files))
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
    foreach ($files as $file) {
        $handle = new \Verot\Upload\Upload($file, $_SESSION['lang']);
        if ($handle->uploaded) {
            $handle->process('../../uploadFiles/');
            if ($handle->processed) {
                $datas['uploadFiles'][] = $handle->file_dst_name_body.'.'.$handle->file_dst_name_ext;
            } else {
                $errors['errorUploadFiles'] = $handle->error;
                return $errors;
            }
        } else {
            if(strpos($_SERVER['HTTP_REFERER'], 'add')){
                $errors['errorUploadFiles'] = $handle->error;
                return $errors;
            }
        }
    }
    return $datas;
}
