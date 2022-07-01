<?php

namespace FloorMeterMax;

class FloorMeterMax
{

    private $id;
    private $floor_meter;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getFloorMeter(): int
    {
        return $this->floor_meter;
    }

    /* Setters */
    public function setFloorMeter(int $floor_meter)
    {
        $this->floor_meter = $floor_meter;
    }
}
