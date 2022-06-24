<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$files = new \File\Files($pdo);
if(!isset($_GET['fileId'])){
    e404();
}
try{
    $file = $files->find($_GET['fileId']);
} catch (\Exception $e){
    e404();
}
render('header', ['title' => $file->getName()]);
?>

    <div class="container">
        <embed
                src="/uploadFiles/<?= $file->getName() ?>"
                width="100%"
                height="100%"
        >
    </div>

<?php require '../footer.php'; ?>