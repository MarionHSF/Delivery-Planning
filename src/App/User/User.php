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
    private $confirmation_token;
    private $confirmed_at;
    private $reset_token;
    private $reset_at;
    private $remember_token;


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

    public function getConfirmationToken(): string
    {
        return $this->confirmation_token;
    }

    public function getConfirmAt(): string
    {
        return $this->confirmed_at;
    }

    public function getResetToken(): string
    {
        return $this->reset_token;
    }

    public function getResetAt(): string
    {
        return $this->reset_at;
    }

    public function getRememberToken(): string
    {
        return $this->remember_token;
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

    public function setConfirmationToken(string $confirmation_token)
    {
        $this->confirmation_token = $confirmation_token;
    }

    public function setConfirmAt(string $confirmed_at)
    {
        $this->confirmed_at = $confirmed_at;
    }

    public function setResetToken(string $reset_token)
    {
        $this->reset_token = $reset_token;
    }

    public function setResetAt(string $reset_at)
    {
        $this->reset_at = $reset_at;
    }

    public function setRememberToken(string $remember_token)
    {
        $this->remember_token = $remember_token;
    }



}

