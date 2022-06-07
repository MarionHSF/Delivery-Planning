<?php
namespace Calendar;

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
        return $this->pdo->query("SELECT * FROM `event` WHERE `start` BETWEEN '{$start->format('Y-m-d 00:00:00')}' AND '{$end->format('Y-m-d 23:59:59')}' ORDER BY start ASC")->fetchAll();
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
     * Return list of supplier ids of event
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function findIdsSuppliers (int $id): array {
        $statement = $this->pdo->query("SELECT `id_supplier` FROM `event_supplier` WHERE `id_event` = $id");
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
        $event->setName($datas['name']);
        $event->setDescription($datas['description']);
        $event->setStart(\DateTime::createFromFormat('Y-m-d H:i',$datas['date'] . ' ' . $datas['start'])->format('Y-m-d H:i:s'));
        $event->setEnd(\DateTime::createFromFormat('Y-m-d H:i',$datas['date'] . ' ' . $datas['end'])->format('Y-m-d H:i:s'));
        return $event;
    }

    /**
     * Insert new event in database
     * @param \Event $event
     * @return bool
     */
    public function create(\Calendar\Event $event): void{
        $statement = $this->pdo->prepare('INSERT INTO `event` (`entry_date`, `id_carrier`, `order`, `phone`, `email`, `dangerous_substance`, `name`, `description`, `start`, `end`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $statement->execute([
            $event->getEntryDate()->format('Y-m-d H:i:s'),
            $event->getIdCarrier(),
            $event->getOrder(),
            $event->getPhone(),
            $event->getEmail(),
            $event->getDangerousSubstance(),
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s')
        ]);
        $id_event = $this->pdo->lastInsertId();
        $statement2 = $this->pdo->prepare('INSERT INTO `event_supplier` (`id_event`, `id_supplier`) VALUES (?, ?)');
        foreach ($event->getIdsSuppliers() as $id_supplier){
            $statement2->execute([
                $id_event,
                $id_supplier
            ]);
        }
    }

    /**
     * Update event in database
     * @param Event $event
     * @return bool
     */
    public function update(\Calendar\Event $event): void{
        $statement = $this->pdo->prepare('UPDATE `event` SET `id_carrier` = ?, `order` = ?, `phone` = ?, `email` = ?, `dangerous_substance` = ?, `name` = ?, `description` = ?, `start` = ?, `end` = ? WHERE `id` = ?');
        $statement->execute([
            $event->getIdCarrier(),
            $event->getOrder(),
            $event->getPhone(),
            $event->getEmail(),
            $event->getDangerousSubstance(),
            $event->getName(),
            $event->getDescription(),
            $event->getStart()->format('Y-m-d H:i:s'),
            $event->getEnd()->format('Y-m-d H:i:s'),
            $event->getId()
        ]);
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
        $statement2 = $this->pdo->prepare('DELETE from `event` WHERE `id` = ?');
        $statement2->execute([
            $event->getId()
        ]);
    }
}