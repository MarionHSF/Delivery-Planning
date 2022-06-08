<?php
require 'functions.php';

use Translation\Translation;

$pdo = get_pdo();
$events = new Calendar\Events($pdo);
$month = new Calendar\Month($_GET['month'] ?? null, $_GET['year'] ?? null);
$start = $month->getStartingDay();
$start = $start->format('N') === '1' ? $start : $month->getStartingDay()->modify('last monday');
$weeks = $month->getWeeks();
$end = (clone $start)->modify('+' . (6 + 7 * ($weeks - 1)) . ' days');
$events = $events->getEventsBetweenByDay($start, $end);

require 'views/header.php';
?>
<div class="calendar">

    <div class="d-flex flex-row align-items-center justify-content-between mx-sm-3">
        <h1><?= $month->toString(); ?></h1>
        <?php if(isset($_GET['creation'])): ?>
            <div class="container">
                <div class="alert alert-success">
                   <?= Translation::of('createAppointement') ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if(isset($_GET['modification'])): ?>
            <div class="container">
                <div class="alert alert-success">
                    <?= Translation::of('modifyAppointement') ?>
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
        <div>
            <a href="/index.php?month=<?= $month->previousMonth()->month;?>&year=<?= $month->previousMonth()->year;?>" class="btn btn-primary">&lt</a>
            <a href="/index.php?month=<?= $month->nextMonth()->month;?>&year=<?= $month->nextMonth()->year;?>" class="btn btn-primary">&gt</a>
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
                        <?= (new DateTime($event['start']))->format('H:i') ?> - <a href="/views/calendar/event.php?id=<?= $event['id'];?>"><?= $event['name']; ?></a>
                    </div>
                <?php endforeach ?>
            </td>
            <?php endforeach; ?>
        </tr>
        <?php endfor; ?>
    </table>
    <a href="/views/calendar/add.php" class="calendar_button">+</a>
    <a href="/views/supplier/list.php"><?= Translation::of('suppliersList') ?></a>
    <a href="/views/carrier/list.php"><?=Translation::of('carriersList') ?></a>
    <a href="/views/user/adminsList.php"><?=Translation::of('adminsList') ?></a>
    <a href="/views/user/customersList.php"><?=Translation::of('customersList') ?></a>
</div>

<?php require 'views/footer.php'; ?>