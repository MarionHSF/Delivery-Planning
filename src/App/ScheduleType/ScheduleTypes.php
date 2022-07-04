<?php
namespace ScheduleType;

class ScheduleTypes {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return schedules type list
     * @return array
     */
    public function getScheduleTypes(): array{
        return $this->pdo->query("SELECT * FROM `schedule_type` ")->fetchAll();
    }
}