<?php

namespace Carrier;

class Carrier
{

    private $id;
    private $name;
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

    public function getComment(): string
    {
        return $this->comment ?? '';
    }

    /* Setters */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
}
