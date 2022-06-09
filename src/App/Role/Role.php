<?php

namespace Role;

class Role
{

    private $id;
    private $name;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /* Setters */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}
