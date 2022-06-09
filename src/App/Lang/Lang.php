<?php

namespace Lang;

class Lang
{

    private $id;
    private $name;
    private $code;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    /* Setters */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }
}
