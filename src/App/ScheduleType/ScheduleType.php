<?php

namespace ScheduleType;

class ScheduleType
{

    private $id;
    private $type;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /* Setters */
    public function setType(string $type)
    {
        $this->type = $type;
    }
}
