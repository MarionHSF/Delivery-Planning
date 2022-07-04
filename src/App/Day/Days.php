<?php
namespace Day;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Translation\Translation;

class Days {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return day
     * @param DateTime $date
     * @return array
     */
    public function find (\DateTime $date): \Day\Day {
        $statement = $this->pdo->query("SELECT * FROM `day` WHERE `day_date` = '{$date->format('Y-m-d 00:00:00')}' LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Day\Day::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Day $day
     * @param array $datas
     * @return Day
     */
    public function hydrate(Day $day, array $datas){
        if(isset($datas['day_date'])){
            $day->setDayDate($datas['day_date']->format('Y-m-d'));
        }
        if(isset($datas['floor_meter'])) {
            if ($day->getFloorMeter()) {
                if (isset($datas['floor_meter_old'])) {
                    $day->setFloorMeter($day->getFloorMeter() + $datas['floor_meter'] - $datas['floor_meter_old']);
                } else {
                    $day->setFloorMeter($day->getFloorMeter() + $datas['floor_meter']);
                }
            } else {
                $day->setFloorMeter($datas['floor_meter']);
            }
        }
        if(isset($datas['validation_date'])){
            $day->setValidationDate(date('Y-m-d H:i:s'));
        }
        $day->setValidation($datas['validation']);
        return $day;
    }

    /**
     * Insert new day in database
     * @param \Day $day
     * @return bool
     */
    public function create(\Day\Day $day): void{
        $statement = $this->pdo->prepare('INSERT INTO `day` (`day_date`, `floor_meter`, `validation`) VALUES (?,?,?)');
        $statement->execute([
            $day->getDayDate()->format('Y-m-d'),
            $day->getFloorMeter(),
            $day->getValidation()
        ]);
    }

    /**
     * Modify day in database
     * @param \Day $day
     * @return bool
     */
    public function update(\Day\Day $day): void{
        $statement = $this->pdo->prepare('UPDATE `day` SET `floor_meter` = ? WHERE `day_date` = ?');
        $statement->execute([
            $day->getFloorMeter(),
            $day->getDayDate()->format('Y-m-d'),
        ]);
    }

    /**
     * Validate day in database
     * @param \Day $day
     * @return bool
     */
    public function validate(\Day\Day $day): void{
        try{
            $this->pdo->beginTransaction();
            $statement = $this->pdo->prepare('UPDATE `day` SET `validation_date` = ?, `validation` = ? WHERE `day_date` = ?');
            $statement->execute([
                $day->getValidationDate()->format('Y-m-d H:i:s'),
                $day->getValidation(),
                $day->getDayDate()->format('Y-m-d'),
            ]);
            // Send user delivery not honored mail
            $events = new \Event\Events($this->pdo);
            $events2 = $events->getEventsBetween($day->getDayDate(), $day->getDayDate());
            foreach ($events2 as $event){
                $event = $events->find($event['id']);
                if($event->getReceptionValidation() === "no"){
                    try {
                        //Server settings
                        $mail = new PHPMailer(true);
                        initSmtp($mail);

                        //Recipients
                        $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
                        $mail->addAddress($event->getEmail());

                        //Content
                        $mail->isHTML(true);
                        $mail->Subject = Translation::of('undelivery');
                        if($_SESSION['lang'] == 'en_GB'){
                            $date = $event->getStart()->format('m/d/Y H:i');
                        }else{
                            $date = $event->getStart()->format('d/m/Y H:i');
                        }
                        $mail->Body    = Translation::of('undeliveryText').' '.$date.'.</br></br>'.Translation::of('mailAppointementFooter');

                        $mail->send();
                    } catch (Exception $e) {
                        echo $mail->ErrorInfo;
                    }
                }
            }
            $this->pdo->commit();
        }catch(\PDOException $e){
            header('Location: /views/day/day.php?date='.$day->getDayDate()->format('Y-m-d').'&errorDB=1');
        }
    }
}