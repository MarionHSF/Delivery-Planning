<?php

namespace User;

class user
{

    private $id;
    private $company_name;
    private $name;
    private $firstname;
    private $phone;
    private $email;
    private $password;
    private $lang;
    private $is_admin;

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

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getIsAdmin(): int
    {
        return $this->is_admin;
    }

    /* Setters */
    public function setCompanyName(string $companyname)
    {
        $this->companyname = $companyname;
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

    public function setLang(string $lang)
    {
        $this->lang = $lang;
    }

    public function setIsAdmin(int $is_admin)
    {
        $this->is_admin = $is_admin;
    }
}

