<?php

namespace Event;

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
    private $comment;
    private $start;
    private $end;
    private $reception_validation;
    private $reception_date;
    private $reception_line;
    private $storage_validation;
    private $upload_files;


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

    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    public function getStart(): \DateTime
    {
        return new \DateTime($this->start);
    }

    public function getEnd(): \DateTime
    {
        return new \DateTime($this->end);
    }

    public function getReceptionValidation(): string
    {
        return $this->reception_validation;
    }

    public function getReceptionDate(): \DateTime
    {
        return new \DateTime($this->reception_date);
    }

    public function getReceptionLine(): string
    {
        return $this->reception_line;
    }

    public function getStorageValidation(): string
    {
        return $this->storage_validation;
    }

    public function getUploadFiles(): array
    {
        return $this->upload_files ?? [];
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


    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }

    public function setStart(string $start)
    {
        $this->start = $start;
    }

    public function setEnd(string $end)
    {
        $this->end = $end;
    }

    public function setReceptionValidation(string $reception_validation)
    {
        $this->reception_validation = $reception_validation;
    }

    public function setReceptionDate(string $reception_date)
    {
        $this->reception_date = $reception_date;
    }

    public function setReceptionLine(string $reception_line)
    {
        $this->reception_line = $reception_line;
    }

    public function setStorageValidation(string $storage_validation)
    {
        $this->storage_validation = $storage_validation;
    }

    public function setUploadFiles(array $upload_files)
    {
        $this->upload_files = $upload_files;
    }
}
