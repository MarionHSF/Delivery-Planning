<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new Calendar\Events($pdo);
$month = new Calendar\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$start = $month->getStartingDay();
$start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
$weeks = $month->getWeeks();
$end = (clone $start)->modify('+' . (6 + 7 * ($weeks - 1)) . ' days');
$events = $events->getEventsBetweenByDay($start, $end);
$carriers =  new \Carrier\Carriers($pdo);

require '../../views/header.php';
?>
    <?php if(isset($_GET['creation'])): ?>
        <div class="container">
            <div class="alert alert-success">
                <?= Translation::of('createAppointement') ?>
                </br>
                <?= Translation::of('warningModifyAppointement') ?>
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
    <div class="container d-flex flex-column gestion p-3 border border-dark">
        <?php if($_SESSION['auth']->getIdRole() == 4){ ?>
            <h1><?= Translation::of('gestion') ?> : </h1>
            <div>
                <a class="text-decoration-none calendar_link" href="/views/supplier/list.php"><?= Translation::of('suppliersList') ?>&emsp;/&emsp;</a>
                <a class="text-decoration-none calendar_link" href="/views/carrier/list.php"><?=Translation::of('carriersList') ?></a>
            </div>
            <div>
                <a class="text-decoration-none calendar_link" href="/views/user/adminsList.php"><?=Translation::of('adminsList') ?>&emsp;/&emsp;</a>
                <a class="text-decoration-none calendar_link" href="/views/user/customersList.php"><?=Translation::of('customersList') ?></a>
            </div>
        <?php } ?>
    </div>
    <div class="calendar">
        <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
            <h1><?= $month->toString(); ?></h1>
            <div>
                <a href="/views/user/adminDashboard.php?month=<?= $month->previousMonth()->month;?>&year=<?= $month->previousMonth()->year;?>" class="btn btn-primary">&lt</a>
                <a href="/views/user/adminDashboard.php?month=<?= $month->nextMonth()->month;?>&year=<?= $month->nextMonth()->year;?>" class="btn btn-primary">&gt</a>
            </div>
        </div>

        <table class="calendar_table calendar_table_<?= $weeks; ?>weeks">
            <?php for ($i = 0; $i < $weeks; $i++): ?>
                <tr>
                    <?php foreach($month->getDays() as $k => $day):

                        $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
                        $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
                        $isToday = date('Y-m-d') === $date->format('Y-m-d');
                        ?>
                        <td class="<?= $month->withInMonth($date) ? '' : 'calendar_overmonth'; ?> <?= $isToday ? 'is_today' : ''; ?> " >
                            <?php if($i === 0): ?>
                                <div class="calendar_weekday"><?= $day; ?></div>
                            <?php endif; ?>
                            <a class="calendar_day" href="/views/calendar/add.php?date=<?= $date->format('Y-m-d'); ?>"><?= $date->format('d') ?></a>
                            <?php foreach ($eventsForDay as $event): ?>
                                <div class="calendar_event">
                                    <?php $carrier = $carriers->find($event['id_carrier'])->getName(); ?>
                                    <?= (new DateTime($event['start']))->format('H:i') ?> - <?= (new DateTime($event['end']))->format('H:i') ?> - <a href="/views/calendar/event.php?id=<?= $event['id'];?>"><?= $carrier ?></a>
                                </div>
                            <?php endforeach ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <a href="/views/calendar/add.php" class="calendar_button">+</a>
    </div>

<?php require '../../views/footer.php'; ?>

