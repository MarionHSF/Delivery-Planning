<?php

namespace Calendar;

class Event
{

    private $id;
    private $entry_date;
    private $id_carrier;
    private $ids_suppliers;
    private $order;
    private $phone;
    private $email;
    private $dangerous_substance;
    private $name;
    private $description;
    private $start;
    private $end;


    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getEntryDate(): \DateTime
    {
        return new \DateTime($this->entry_date);
    }

    public function getIdCarrier(): int
    {
        return $this->id_carrier;
    }

    public function getIdsSuppliers(): array
    {
        return $this->ids_suppliers;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getDangerousSubstance(): string
    {
        return $this->dangerous_substance;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStart(): \DateTime
    {
        return new \DateTime($this->start);
    }

    public function getEnd(): \DateTime
    {
        return new \DateTime($this->end);
    }

    /* Setters */
    public function setEntryDate(string $entry_date)
    {
        $this->entry_date = $entry_date;
    }

    public function setIdCarrier(int $id_carrier)
    {
        $this->id_carrier = $id_carrier;
    }

    public function setIdsSuppliers(array $ids_suppliers)
    {
        $this->ids_suppliers = $ids_suppliers;
    }

    public function setOrder(string $order)
    {
        $this->order = $order;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setDangerousSubstance(string $dangerous_substance)
    {
        $this->dangerous_substance = $dangerous_substance;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function setStart(string $start)
    {
        $this->start = $start;
    }

    public function setEnd(string $end)
    {
        $this->end = $end;
    }
}
