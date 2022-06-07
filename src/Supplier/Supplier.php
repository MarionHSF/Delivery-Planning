<?php

namespace Supplier;

class Supplier
{

    private $id;
    private $name;
    private $comment;

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

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
}
