<?php

namespace Schedule;

class Schedule
{

    private $id;
    private $type_id;
    private $start;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getTypeId(): int
    {
        return $this->type_id;
    }

    public function getStart(): \DateTime
    {
        return new \DateTime($this->start);
    }

    /* Setters */
    public function setTypeId(int $type_id)
    {
        $this->type_id = $type_id;
    }

    public function setStart(string $start)
    {
        $this->start = $start;
    }
}
