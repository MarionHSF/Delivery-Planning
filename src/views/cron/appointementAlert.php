<?php

require '../../functions.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Translation\Translation;

$pdo = new PDO\PDO();
$pdo = $pdo->get_pdo();

$date = new \DateTime(date('Y-m-d H:i:s'));
$start = $date->modify('+1 days');

$events = new Event\Events($pdo);
$events2 = $events->getEventsBetween($start, $start);

if(!empty($events2)){
    foreach ($events2 as $event){
        try {
            $event = $events->find($event['id']);
            //Server settings
            $mail = new PHPMailer(true);
            initSmtp($mail);

            //Recipients
            $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
            $mail->addAddress($event->getEmail(),);

            //Content
            $mail->isHTML(true);
            $mail->Subject = Translation::of('mailReminderAppointementSubjet');
            if($_SESSION['lang'] == 'en_GB'){
                $date = $event->getStart()->format('m/d/Y H:i');
            }else{
                $date = $event->getStart()->format('d/m/Y H:i');
            }
            $mail->Body    = Translation::of('mailReminderAppointementText').' '.$date.'.</br></br>'.Translation::of('warningModifyAppointement').'</br></br>'.Translation::of('mailAppointementFooter');

            $mail->send();
        } catch (Exception $e) {
            echo $mail->ErrorInfo;
        }
    }
}



