<?php
require '../../functions.php';

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$users = new \User\Users($pdo);
$email = $_GET['email'];
$user = $users->findByEmail($email,'event');
$phone = $user->getPhone();

echo $phone;