<?php
require '../../functions.php';

use Translation\Translation;

reconnectFromCookie();
isNotConnected();
onlyAdminRights();

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();
$events = new Event\Events($pdo);
$month = new Event\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$start = $month->getStartingDay();
$start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
$weeks = $month->getWeeks();
$end = (clone $start)->modify('+' . (6 + 7 * ($weeks - 1)) . ' days');
$events = $events->getEventsBetweenByDay($start, $end);
$carriers =  new \Carrier\Carriers($pdo);

require '../../views/header.php';
?>
    <?php if(isset($_GET['creation'])): ?>
        <div class="container d-print-none">
            <div class="alert alert-success">
                <?= Translation::of('createAppointement') ?>
                </br>
                <?= Translation::of('warningModifyAppointement') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if(isset($_GET['suppression'])): ?>
        <div class="container d-print-none">
            <div class="alert alert-success">
                <?= Translation::of('deleteAppointement') ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if($_SESSION['auth']->getIdRole() == 4){ ?>
        <div class="d-flex flex-column p-3 border border-dark d-print-none mb-5 w-auto mx-3">
            <h1><?= Translation::of('gestion') ?> : </h1>
            <div>
                <a class="text-decoration-none calendar_link text-dark" href="/views/supplier/list.php"><?= Translation::of('suppliersList') ?>&emsp;/&emsp;</a>
                <a class="text-decoration-none calendar_link text-dark" href="/views/carrier/list.php"><?=Translation::of('carriersList') ?></a>
            </div>
            <div>
                <a class="text-decoration-none calendar_link text-dark" href="/views/user/adminsList.php"><?=Translation::of('adminsList') ?>&emsp;/&emsp;</a>
                <a class="text-decoration-none calendar_link text-dark" href="/views/user/customersList.php"><?=Translation::of('customersList') ?></a>
            </div>
        </div>
    <?php } ?>
    <div class="calendar mx-3">
        <div class="d-flex flex-row align-items-center mx-sm-3">
            <h1><?= $month->toString(); ?></h1>
            <div class="d-print-none mx-5">
                <a href="/views/user/adminDashboard.php?month=<?= $month->previousMonth()->month;?>&year=<?= $month->previousMonth()->year;?>" class="btn btn-primary">&lt</a>
                <a href="/views/user/adminDashboard.php?month=<?= $month->nextMonth()->month;?>&year=<?= $month->nextMonth()->year;?>" class="btn btn-primary">&gt</a>
            </div>
        </div>
        <table class="calendar_table_global mb-3 d-print-none">
            <tbody>
                <?php for ($i = 0; $i < $weeks; $i++):
                    $weekNumber = (clone $start)->format('W') + $i;
                ?>
                <tr>
                    <?php foreach($month->getDays() as $k => $day):
                        $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
                        $isToday = date('Y-m-d') === $date->format('Y-m-d');
                        $isWeekEnd = ($date->format('l') === "Saturday") || ($date->format('l') === "Sunday");
                        ?>
                        <td class="text-center <?= $month->withInMonth($date) ? '' : 'calendar_overmonth'; ?> <?= $isToday ? 'is_today' : ''; ?> <?= $isWeekEnd ? 'is_weekend' : ''; ?>">
                            <?php if($i === 0){ ?>
                                <div class="calendar_weekday"><?= $day; ?></div>
                            <?php } ?>
                           <p class="calendar_day" onclick=displayWeek(<?= $weekNumber?>);><?= $date->format('d') ?></p>
                        </td>
                    <?php endforeach; ?>
                </tr>
                 <?php endfor; ?>
            </tbody>
        </table>
        <a href="/views/event/add.php" class="calendar_button d-print-none" onmouseover=displayTextAddButton() onmouseout=undisplayTextAddButton()>+</a>
        <p id="textAddButton" style="display: none"> <?= Translation::of('createAppointementTitle') ?></p>
        <div class="d-flex justify-content-end mx-5">
            <a class="text-decoration-none d-print-none printButton" href="javascript:window.print()">&#x1F5A8;</a>
        </div>
        <table class="calendar_table mt-3">
            <tbody id="weekDiv">
                <?php for ($i = 0; $i < $weeks; $i++): ?>
                    <?php $weekNumber = (clone $start)->format('W') + $i; ?>
                    <tr id="week<?= $weekNumber?>" <?= ($weekNumber == date('W')) ? '' : 'style="display:none"'; ?>>
                        <?php foreach($month->getDays() as $k => $day):
                            $date = (clone $start)->modify("+" . ($k + $i * 7) . " days");
                            $eventsForDay = $events[$date->format('Y-m-d')] ?? [];
                            $isToday = date('Y-m-d') === $date->format('Y-m-d');
                            $isWeekEnd = ($date->format('l') === "Saturday") || ($date->format('l') === "Sunday");
                            ?>
                            <td class="<?= $month->withInMonth($date) ? '' : 'calendar_overmonth'; ?> <?= $isToday ? 'is_today' : ''; ?> <?= $isWeekEnd ? 'is_weekend' : ''; ?>">
                                <div class="calendar_weekday"><?= $day; ?></div>
                                <a class="calendar_day" href="/views/event/day.php?date=<?= $date->format('Y-m-d'); ?>"><?= $date->format('d') ?></a>
                                <?php foreach ($eventsForDay as $event): ?>
                                    <div class="calendar_event">
                                        <?php $carrier = $carriers->find($event['id_carrier'])->getName(); ?>
                                        <?= (new DateTime($event['start']))->format('H:i') ?> - <?= (new DateTime($event['end']))->format('H:i') ?> - <a href="/views/event/event.php?id=<?= $event['id'];?>"><?= $carrier ?></a>
                                    </div>
                                <?php endforeach ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
<?php require '../../views/footer.php'; ?>

