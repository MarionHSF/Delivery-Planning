<?php
namespace Schedule;

class Schedules {

    private $pdo;

    /**
     * @param \PDo $pdo
     */
    public function __construct(\PDo $pdo){
        $this->pdo = $pdo;
    }

    /**
     * Return schedules list
     * @return array
     */
    public function getSchedules(): array{
        return $this->pdo->query("SELECT * FROM `schedule` ORDER BY `start` ASC")->fetchAll();
    }
}