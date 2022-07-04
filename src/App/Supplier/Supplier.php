<?php

namespace Supplier;

class Supplier
{

    private $id;
    private $name;
    private $reserved_14h;
    private $comment;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReserver14h(): string
    {
        return $this->reserved_14h;
    }

    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    /* Setters */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setReserved14h(string $reserved_14h)
    {
        $this->reserved_14h = $reserved_14h;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
}
