<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndSuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new User\Users($pdo);
$users_events = $users->findEvents($_GET['id']);
if($users_events){
    foreach ($users_events as $users_event){
        if($users_event['start'] > date('Y-m-d H:i:s')){
            $upcoming_events[] = $users_event;
        }else{
            $past_events[] = $users_event;
        }
    }
    $start = array();
    if(isset($upcoming_events)){
        foreach ($upcoming_events as $key => $event){
            $start[$key] = $event['start'];
        }
        array_multisort($start, SORT_ASC, $upcoming_events);
    }
    if(isset($past_events)){
        foreach ($past_events as $key => $event){
            $start[$key] = $event['start'];
        }
        array_multisort($start, SORT_DESC, $past_events);
    }
}

render('header', ['title' => Translation::of('appointement')]);

?>
    <div class="container">
        <?php if(isset($_GET['creation'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('createAppointement') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['suppression'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('deleteAppointement') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1 class="mb-5"><?= Translation::of('appointement') ?></h1>
        <h2 id="upcoming-appointement-toogler" class="mb-3 px-3"><u><?= Translation::of('upcomingAppointement') ?></u></h2>
        <div id="upcoming-appointement-toogler-content" class="mb-5 px-5">
            <?php if($users_events){ ?>
                <?php if(isset($upcoming_events)){ ?>
                    <?php foreach ($upcoming_events as $upcoming_event){ ?>
                            <?php if($_SESSION['lang'] == 'en_GB'){ ?>
                                <a href="/views/calendar/event.php?id=<?= $upcoming_event['id_event'];?>"><?= (new \DateTime($upcoming_event['start']))->format('m/d/Y H:i') ?></a></br>
                            <?php }else{ ?>
                                <a href="/views/calendar/event.php?id=<?= $upcoming_event['id_event'];?>"><?= (new \DateTime($upcoming_event['start']))->format('d/m/Y H:i') ?></a></br>
                            <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <p><?= Translation::of('emptyUpcomingAppointement') ?></p>
                <?php } ?>
            <?php }else{ ?>
                <p><?= Translation::of('emptyUpcomingAppointement') ?></p>
            <?php } ?>
        </div>
        <h2 id="past-appointement-toogler" class="mb-3 px-3"><u><?= Translation::of('pastAppointement') ?></u></h2>
        <div id="past-appointement-toogler-content" class="px-5" style="display: none">
            <?php if($users_events){ ?>
                <?php if(isset($past_events)){ ?>
                    <?php foreach ($past_events as $past_event){ ?>
                        <?php if($_SESSION['lang'] == 'en_GB'){ ?>
                            <a href="/views/calendar/event.php?id=<?= $past_event['id_event'];?>"><?= (new \DateTime($past_event['start']))->format('m/d/Y H:i') ?></a></br>
                        <?php }else{ ?>
                            <a href="/views/calendar/event.php?id=<?= $past_event['id_event'];?>"><?= (new \DateTime($past_event['start']))->format('d/m/Y H:i') ?></a></br>
                        <?php } ?>
                    <?php } ?>
                <?php }else{ ?>
                    <p><?= Translation::of('emptyPastAppointement') ?></p>
                <?php } ?>
            <?php }else{ ?>
                <p><?= Translation::of('emptyPastAppointement') ?></p>
            <?php } ?>
        </div>
        <div> <a class="btn btn-primary mt-5" href="/views/calendar/add.php"><?= Translation::of('createAppointementTitle') ?></a></div>
        <?php if($_SESSION['auth']->getIdRole() == 1){ ?>
            <div> <a class="btn btn-primary mt-5" href="/views/calendar/add.php"><?= Translation::of('return') ?></a></div>
        <?php }else{ ?>
            <div> <a class="btn btn-primary mt-5" href="/views/user/customersList.php"><?= Translation::of('return') ?></a></div>
        <?php }?>
    </div>

<?php require '../footer.php'; ?>