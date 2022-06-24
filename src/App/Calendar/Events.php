<?php
namespace Calendar;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Translation\Translation;

class Events {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return events between two dates indexed
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function getEventsBetween(\DateTime $start, \DateTime $end): array{
        return $this->pdo->query("SELECT * FROM `event` WHERE `start` BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ORDER BY `start` ASC")->fetchAll();
    }

    /**
     * Return events between two dates indexed by day
     * @param \DateTime $start
     * @param \DateTime $end
     * @return array
     */
    public function getEventsBetweenByDay(\DateTime $start, \DateTime $end): array{
        $events = $this->getEventsBetween($start, $end);
        $days = [];
        foreach($events as $event) {
            $date = explode(' ', $event['start'])[0];
            if (!isset($days[$date])) {
                $days[$date] = [$event];
            } else {
                $days[$date][] = $event;
            }
        }
        return $days;
    }

    /**
     * Return event
     * @param int $id
     * @return array
     */
    public function find (int $id): \Calendar\Event {
        $statement = $this->pdo->query("SELECT * FROM `event` WHERE id = $id LIMIT 1");
        $statement->setFetchMode(\PDO::FETCH_CLASS, \Calendar\Event::class);
        $result = $statement->fetch();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return user of event
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findUser (int $id): array {
        $statement = $this->pdo->query("SELECT * FROM `user` LEFT JOIN (`user_event`, `event`) ON (`user`.`id` = `user_event`.`id_user` AND `user_event`.`id_event` = `event`.`id`) WHERE `event`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return carrier of event
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findCarrier (int $id): array {
        $statement = $this->pdo->query("SELECT `carrier`.`id`, `carrier`.`name` FROM `carrier` LEFT JOIN (`event`) ON (`carrier`.`id` = `event`.`id_carrier`) WHERE `event`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return list of supplier of event
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findSuppliers (int $id): array {
        $statement = $this->pdo->query("SELECT `supplier`.`id`, `supplier`.`name` FROM `supplier` LEFT JOIN (`event_supplier`, `event`) ON (`supplier`.`id` = `event_supplier`.`id_supplier` AND `event_supplier`.`id_event` = `event`.`id`) WHERE `event`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
        throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Return list of upload files of event
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findUploadFiles (int $id): array {
        $statement = $this->pdo->query("SELECT `file`.`id`, `file`.`name` FROM `file` LEFT JOIN (`event_file`, `event`) ON (`file`.`id` = `event_file`.`id_file` AND `event_file`.`id_event` = `event`.`id`) WHERE `event`.`id` = $id");
        $result = $statement->fetchAll();
        if ($result === false) {
            throw new \Exception('Aucun résultat n\'a été trouvé');
        }
        return $result;
    }

    /**
     * Modify datas before insertion in database (creation or update)
     * @param Event $event
     * @param array $datas
     * @return Event
     */
    public function hydrate(Event $event, array $datas){
        $event->setEntryDate(date('Y-m-d H:i:s'));
        $event->setIdCarrier($datas['id_carrier']);
        $event->setIdsSuppliers($datas['ids_suppliers']);
        $event->setOrder(implode(', ',$datas['order']));
        $event->setPhone($datas['phone']);
        $event->setEmail($datas['email']);
        isset($datas['dangerous_substance']) ? $event->setDangerousSubstance('yes') : $event->setDangerousSubstance('no') ;
        $event->setComment($datas['comment']);
        $event->setStart(\DateTime::createFromFormat('Y-m-d H:i',$datas['date'] . ' ' . $datas['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(\DateTime::createFromFormat('Y-m-d H:i',$datas['date'] . ' ' . $datas['end'])->format('Y-m-d H:i:s'));
        $event->setReceptionValidation('no');
        $event->setStorageValidation('no');
        if(isset($datas['uploadFiles'])){
            $event->setUploadFiles($datas['uploadFiles']);
        }
        return $event;
    }

    /**
     * Insert new event in database
     * @param \Event $event
     * @return bool
     */
    public function create(\Calendar\Event $event): void{
        //Add event in event table
        $statement = $this->pdo->prepare('INSERT INTO `event` (`entry_date`, `id_carrier`, `order`, `phone`, `email`, `dangerous_substance`, `comment`, `start`, `end`, `reception_validation`, `storage_validation`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([
            $event->getEntryDate()->format('Y-m-d H:i:s'),
            $event->getIdCarrier(),
            $event->getOrder(),
            $event->getPhone(),
            $event->getEmail(),
            $event->getDangerousSubstance(),
            $event->getComment(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->getReceptionValidation(),
            $event->getStorageValidation()
        ]);
        //Add event in event_supplier table
        $id_event = $this->pdo->lastInsertId();
        $statement2 = $this->pdo->prepare('INSERT INTO `event_supplier` (`id_event`, `id_supplier`) VALUES (?, ?)');
        foreach ($event->getIdsSuppliers() as $id_supplier){
            $statement2->execute([
                $id_event,
                $id_supplier
            ]);
        }
        //Add event in user_event table
        $statement3 = $this->pdo->prepare('INSERT INTO `user_event` (`id_user`, `id_event`) VALUES (?, ?)');
        if($_SESSION['auth']->getIdRole() == 1){
            $id_user = $_SESSION['auth']->getID();
        }else{
            $users = new \User\Users($this->pdo);
            $user = $users->findByEmail($event->getEmail(),'event');
            $id_user = $user->getId();
        }
        $statement3->execute([
            $id_user,
            $id_event
        ]);
        //Add upload files in file table
        $files = new \File\Files($this->pdo);
        $uploadFiles = $event->getUploadFiles();
        if(isset($uploadFiles)){
            foreach ($uploadFiles as $uploadFile){
                $file = $files->hydrate(new \File\File(), $uploadFile);
                $files->create($file);
                //Add upload files in event_file table
                $id_file = $this->pdo->lastInsertId();
                $statement4 = $this->pdo->prepare('INSERT INTO `event_file` (`id_event`, `id_file`) VALUES (?, ?)');
                $statement4->execute([
                    $id_event,
                    $id_file
                ]);
            }
        }
        // Send user confirmation mail
        try {
            //Server settings
            $mail = new PHPMailer(true);
            initSmtp($mail);

            //Recipients
            $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
            $mail->addAddress($event->getEmail(),);

            //Content
            $mail->isHTML(true);
            $mail->Subject = Translation::of('mailAppointementSubjet');
            if($_SESSION['lang'] == 'en_GB'){
                $date = $event->getStart()->format('m/d/Y H:i');
            }else{
                $date = $event->getStart()->format('d/m/Y H:i');
            }
            $mail->Body    = Translation::of('mailCreateAppointementText').' '.$date.'.</br></br>'.Translation::of('warningModifyAppointement').'</br></br>'.Translation::of('mailAppointementFooter');

            $mail->send();
        } catch (Exception $e) {
            echo $mail->ErrorInfo;
        }
        // Send admin warning dangerous substance mail
        if($event->getDangerousSubstance() == "yes"){
            try {
                //Server settings
                $mail = new PHPMailer(true);
                initSmtp($mail);

                //Recipients
                $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
                $mail->addAddress('test@test.com'); // TODO modifier email admin

                //Content
                $mail->isHTML(true);
                $mail->Subject = Translation::of('dangerousSubstance');
                if($_SESSION['lang'] == 'en_GB'){
                    $date = $event->getStart()->format('m/d/Y H:i');
                }else{
                    $date = $event->getStart()->format('d/m/Y H:i');
                }
                $mail->Body    = Translation::of('dangerousSubstanceMail').' '.$date.'.';

                $mail->send();
            } catch (Exception $e) {
                echo $mail->ErrorInfo;
            }
        }
    }

    /**
     * Update event in database
     * @param Event $event
     * @return bool
     */
    public function update(\Calendar\Event $event): void{
        //Update event in event table
        $statement = $this->pdo->prepare('UPDATE `event` SET `id_carrier` = ?, `order` = ?, `phone` = ?, `email` = ?, `dangerous_substance` = ?, `comment` = ?, `start` = ?, `end` = ? WHERE `id` = ?');
        $statement->execute([
            $event->getIdCarrier(),
            $event->getOrder(),
            $event->getPhone(),
            $event->getEmail(),
            $event->getDangerousSubstance(),
            $event->getComment(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->getId()
        ]);
        //Delete and re add event in event_supplier table
        $statement2 = $this->pdo->prepare('DELETE from `event_supplier` WHERE `id_event` = ?');
        $statement2->execute([
            $event->getId()
        ]);
        $statement3 = $this->pdo->prepare('INSERT INTO `event_supplier` (`id_event`, `id_supplier`) VALUES (?, ?)');
        foreach ($event->getIdsSuppliers() as $id_supplier){
            $statement3->execute([
                $event->getId(),
                $id_supplier
            ]);
        }
        //Delete and re add event in user_event table
        $statement4 = $this->pdo->prepare('DELETE from `user_event` WHERE `id_event` = ?');
        $statement4->execute([
            $event->getId()
        ]);
        $statement5 = $this->pdo->prepare('INSERT INTO `user_event` (`id_user`, `id_event`) VALUES (?, ?)');
        if($_SESSION['auth']->getIdRole() == 1){
            $id_user = $_SESSION['auth']->getID();
        }else{
            $users = new \User\Users($this->pdo);
            $user = $users->findByEmail($event->getEmail(),'event');
            $id_user = $user->getId();
        }
        $statement5->execute([
            $id_user,
            $event->getId()
        ]);
        //Add upload files in file table
        $files = new \File\Files($this->pdo);
        $uploadFiles = $event->getUploadFiles();
        if(isset($uploadFiles)){
            foreach ($uploadFiles as $uploadFile){
                $file = $files->hydrate(new \File\File(), $uploadFile);
                $files->create($file);
                //Add upload files in event_file table
                $id_file = $this->pdo->lastInsertId();
                $statement6 = $this->pdo->prepare('INSERT INTO `event_file` (`id_event`, `id_file`) VALUES (?, ?)');
                $statement6->execute([
                    $event->getId(),
                    $id_file
                ]);
            }
        }
        // Send user confirmation mail
        try {
            //Server settings
            $mail = new PHPMailer(true);
            initSmtp($mail);

            //Recipients
            $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
            $mail->addAddress($event->getEmail(),);

            //Content
            $mail->isHTML(true);
            $mail->Subject = Translation::of('mailAppointementSubjet');
            if($_SESSION['lang'] == 'en_GB'){
                $date = $event->getStart()->format('m/d/Y H:i');
            }else{
                $date = $event->getStart()->format('d/m/Y H:i');
            }
            $mail->Body    = Translation::of('mailModifyAppointementText').' '.$date.'.</br></br>'.Translation::of('mailAppointementFooter');

            $mail->send();
        } catch (Exception $e) {
            echo $mail->ErrorInfo;
        }
    }

    /**
     *  Delete event in database
     * @param Event $event
     * @return bool
     */
    public function delete(\Calendar\Event $event): void{
                $statement = $this->pdo->prepare('DELETE from `event_supplier` WHERE `id_event` = ?');
        $statement->execute([
            $event->getId()
        ]);
        $statement2 = $this->pdo->prepare('DELETE from `user_event` WHERE `id_event` = ?');
        $statement2->execute([
            $event->getId()
        ]);
        $statement3 = $this->pdo->prepare('DELETE from `event_file` WHERE `id_event` = ?');
        $statement3->execute([
            $event->getId()
        ]);
        $files = $this->findUploadFiles($event->getId());
        foreach ($files as $file){
            $files = new \File\Files($this->pdo);
            $file = $files->find($file['id']);
            $files->delete($file);
        }
        $statement4 = $this->pdo->prepare('DELETE from `event` WHERE `id` = ?');
        $statement4->execute([
            $event->getId()
        ]);
        try { // Send user confirmation mail
            //Server settings
            $mail = new PHPMailer(true);
            initSmtp($mail);

            //Recipients
            $mail->setFrom('test@test.com', 'Henry Schein'); // TODO modifier email admin
            $mail->addAddress($event->getEmail(),);

            //Content
            $mail->isHTML(true);
            $mail->Subject = Translation::of('mailAppointementSubjet');
            if($_SESSION['lang'] == 'en_GB'){
                $date = $event->getStart()->format('m/d/Y H:i');
            }else{
                $date = $event->getStart()->format('d/m/Y H:i');
            }
            $mail->Body    = Translation::of('mailDeleteAppointementText').' '.$date.'.</br></br>'.Translation::of('mailAppointementFooter');

            $mail->send();
        } catch (Exception $e) {
            echo $mail->ErrorInfo;
        }
    }

    /**
     * Insert reception validation in database
     * @param Event $event
     * @param array $datas
     * @return void
     */
    public function validationReception(\Calendar\Event $event, array $datas): void{
        $event->setReceptionValidation('yes');
        $event->setReceptionDate(\DateTime::createFromFormat('Y-m-d H:i',$datas['date'] . ' ' . $datas['start'])->format('Y-m-d H:i:s'));
        $event->setReceptionLine($datas['reception_line']);
        $statement = $this->pdo->prepare('UPDATE `event` SET `reception_validation` = ?, `reception_date` = ?, `reception_line` = ? WHERE `id` = ?');
        $statement->execute([
            $event->getReceptionValidation(),
            $event->getReceptionDate()->format('Y-m-d H:i:s'),
            $event->getReceptionLine(),
            $event->getId()
        ]);
    }

    /**
     * Insert reception validation in database
     * @param Event $event
     * @param array $datas
     * @return void
     */
    public function validationStorage(\Calendar\Event $event): void{
        $event->setStorageValidation('yes');
        $statement = $this->pdo->prepare('UPDATE `event` SET `storage_validation` = ? WHERE `id` = ?');
        $statement->execute([
            $event->getStorageValidation(),
            $event->getId()
        ]);
    }
}