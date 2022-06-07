<?php
namespace App;

class Validator {

    private $datas;
    protected $errors = [];

    public function __construct(array $datas = [])
    {
        $this->datas = $datas;
    }

    /**
     * @param array $datas
     * @return array|bool
     */
    public function validates(array $datas) {
        $this->errors = [];
        $this->datas = $datas;
        return $this->errors;
    }

    /**
     * Check if field exists
     * @param string $field
     * @param string $method
     * @param ...$parameters
     * @return bool
     */
    public function validate(string $field, string $method, ...$parameters): bool{
        if(!isset($this->datas[$field])){
            $this->errors[$field] = "le champs $field n'est pas rempli";
            return false;
        }else{
            return call_user_func([$this, $method], $field, ...$parameters);
        }
    }

    /**
     * Check length of text
     * @param string $field
     * @param int $length
     * @return bool
     */
    public function minLength(string $field, int $length): bool{
        if (mb_strlen($field) < $length) {
            $this->errors[$field] = "Le champs doit avoir plus de $length caractères";
            return false;
        }
        return true;
    }

    /**
     * check if it is a date format, createFromFormat return date if function is successful, else returns false
     * @param string $fiels
     * @return bool
     */
    public function date (string $field): bool{
        if(\DateTime::createFromFormat('Y-m-d', $this->datas[$field]) === false) {
            $this->errors[$field] = "La date n'est pas valide";
            return false;
        };
        return true;
    }

    /**
     * check if time is valid, createFromFormat return date if function is successful, else returns false
     * @param string $fiels
     * @return bool
     */
    public function time (string $field): bool{
        if(\DateTime::createFromFormat('H:i', $this->datas[$field]) === false) {
            $this->errors[$field] = "Le temps n'est pas valide";
            return false;
        };
        return true;
    }

    /**
     * check if time is valid, createFromFormat return date if function is successful, else returns false
     * @param string $fiels
     * @return bool
     */
    public function beforeTime (string $startField, string $endField): bool{
        if ($this->time($startField) && $this->time($endField)){
            $start = \DateTime::createFromFormat('H:i', $this->datas[$startField]);
            $end = \DateTime::createFromFormat('H:i', $this->datas[$endField]);
            if($start->getTimestamp() > $end->getTimestamp()){
                $this->errors[$startField] = "L'heure de début doit être supérieure à l'heure de fin";
                return false;
            }
            return true;
        }
        $this->errors[$startField] = "Le temps n'est pas valide";
        return false;
    }

    /**
     * * check if phone number is valid
     * @param string $field
     * @return bool
     */
    public function phone(string $field): bool{
        if(preg_match('/^[0-9]{10}+$/',$this->datas[$field]) == 0){
            $this->errors[$field] = "Le numéro de téléphone n'est pas valide";
            return false;
        };
        return true;
    }

    /**
     * * check if email is valid
     * @param string $field
     * @return bool
     */
    public function email(string $field): bool{
        if(!filter_var($this->datas[$field], FILTER_VALIDATE_EMAIL)){
            $this->errors[$field] = "L'email n'est pas valide";
            return false;
        };
        return true;
    }
}

