<?php

namespace User;

class User
{

    private $id;
    private $company_name;
    private $name;
    private $firstname;
    private $phone;
    private $email;
    private $password;
    private $id_lang;
    private $id_role;

    /* Getters */
    public function getId(): int
    {
        return $this->id;
    }

    public function getCompanyName(): string
    {
        return $this->company_name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIdLang(): int
    {
        return $this->id_lang;
    }

    public function getIdRole(): int
    {
        return $this->id_role;
    }

    /* Setters */
    public function setCompanyName(string $company_name)
    {
        $this->company_name = $company_name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;
    }

    public function setPhone(string $phone)
    {
        $this->phone = $phone;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    public function setIdLang(int $id_lang)
    {
        $this->id_lang = $id_lang;
    }

    public function setIdRole(int $id_role)
    {
        $this->id_role = $id_role;
    }
}

