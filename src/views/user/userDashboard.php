<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyConnectedUserAndSuperAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$users = new User\Users($pdo);
$users_events_ids = $users->findEvents($_GET['id']);
if($users_events_ids){
    foreach ($users_events_ids as $users_events_id){
        $users_events_ids_list[] = $users_events_id['id_event'];
    }
    $users_events_ids_list = implode(',', $users_events_ids_list);
    $events = new Calendar\Events($pdo);
    $upcoming_events = $events->getEventsBetweenTime(new \DateTime(date('Y-m-d H:i:s')), new \DateTime(date('Y-m-d H:i:s', strtotime('+365 days'))), "ASC");
    $past_events = $events->getEventsBetweenTime(new \DateTime(date('Y-m-d H:i:s', strtotime('-365 days'))), new \DateTime(date('Y-m-d H:i:s')), "DESC");

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
        <?php if(isset($_GET['supression'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('deleteAppointement') ?>
                </div>
            </div>
        <?php endif; ?>
        <h1 class="mb-5"><?= Translation::of('appointement') ?></h1>
        <h2 id="upcoming-appointement-toogler" class="mb-3 px-3"><u><?= Translation::of('upcomingAppointement') ?></u></h2>
        <div id="upcoming-appointement-toogler-content" class="mb-5 px-5">
            <?php if($users_events_ids){ ?>
                <?php if($upcoming_events){ ?>
                    <?php foreach ($upcoming_events as $upcoming_event){?>
                        <?php if(strpos($users_events_ids_list, $upcoming_event['id']) !== false){
                            $event = $events->find($upcoming_event['id'])?>
                            <a href="/views/calendar/event.php?id=<?= $upcoming_event['id'];?>"><?= $event->getStart()->format('d/m/Y H:i'); ?></a></br>
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
            <?php if($users_events_ids){ ?>
                <?php if($past_events){ ?>
                    <?php foreach ($past_events as $past_event){ ?>
                        <?php if(strpos($users_events_ids_list, $past_event['id']) !== false){
                            $event = $events->find($past_event['id'])?>
                            <a href="/views/calendar/event.php?id=<?= $past_event['id'];?>"><?= $event->getStart()->format('d/m/Y H:i'); ?></a></br>
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