<?php

namespace Day;

class Day
{

    private $id;
    private $day_date;
    private $floor_meter;
    private $validation_date;
    private $validation;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getDayDate(): \DateTime
    {
        return new \DateTime($this->day_date);
    }

    public function getFloorMeter(): float
    {
        return $this->floor_meter ?? 0.00;
    }

    public function getValidationDate(): \DateTime
    {
        return new \DateTime($this->validation_date);
    }

    public function getValidation(): string
    {
        return $this->validation;
    }

    /* Setters */
    public function setDayDate(string $day_date)
    {
        $this->day_date = $day_date;
    }

    public function setFloorMeter(float $floor_meter)
    {
        $this->floor_meter = $floor_meter;
    }

    public function setValidationDate(string $validation_date)
    {
        $this->validation_date = $validation_date;
    }

    public function setValidation(string $validation)
    {
        $this->validation = $validation;
    }
}
