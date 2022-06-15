<?php
namespace App;

use Translation\Translation;

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
    public function validate(mixed $field, string $method, ...$parameters): bool{
        if(!isset($this->datas[$field])){
            $this->errors[$field] = Translation::of('emptyField');
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
        if(gettype($this->datas[$field]) == "array"){
            if(empty($this->datas[$field])){
                $this->errors[$field] = Translation::of('emptyField');
                return false;
            }else{
                if(mb_strlen($this->datas[$field][0]) < $length){
                    $this->errors[$field] = Translation::of('emptyField');
                    return false;
                }else{
                    return true;
                }
            }
        }else{
            if (mb_strlen($this->datas[$field]) < $length) {
                $this->errors[$field] = Translation::of('emptyField');
                return false;
            }else{
                return true;
            }
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
            $this->errors[$field] = Translation::of('errorDate');
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
            $this->errors[$field] = Translation::of('errorTime');;
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
                $this->errors[$startField] = Translation::of('errorStartTime');
                return false;
            }
            return true;
        }
        $this->errors[$startField] = Translation::of('errorTime');
        return false;
    }

    /**
     * check if phone number is valid
     * @param string $field
     * @return bool
     */
    public function phone(string $field): bool{
        if(preg_match('/^[0-9]{10}+$/',$this->datas[$field]) == 0){
            $this->errors[$field] = Translation::of('errorPhone');
            return false;
        };
        return true;
    }

    /**
     * check if email is valid
     * @param string $field
     * @return bool
     */
    public function email(string $field): bool{
        if(!filter_var($this->datas[$field], FILTER_VALIDATE_EMAIL)){
            $this->errors[$field] = Translation::of('errorEmail');
            return false;
        };
        return true;
    }

    /**
     * checks if the second email entry matches the first one
     * @param string $field
     * @return bool
     * @throws \Exception
     */
    public function emailVerif(string $field): bool{
        if ($this->email($field) && $this->email('email_verif')){
            if($this->datas[$field] != $this->datas['email_verif']){
                $this->errors[$field] = Translation::of('errorEmailVerif');
                return false;
            };
            return true;
        }
        $this->errors[$field] = Translation::of('errorEmail');
        return false;
    }

    public function emailUniq(string $field): bool{
        if($this->emailVerif($field)){
            $pdo = new \PDO\PDO();
            $pdo = $pdo->get_pdo();
            $users = new \User\Users($pdo);
            $emailsList = $users->getUsersEmail();
            if(!empty($emailsList)){
                foreach ($emailsList as $email){
                    $emailsList2[] = $email['email'];
                }
                if(in_array($this->datas[$field], $emailsList2)){
                    $this->errors[$field] = Translation::of('errorEmailUniq');
                    return false;
                }
                return true;
            }
            return true;
        }
        return false;
    }

    /**
     * check if password is valid
     * @param string $field
     * @return bool
     */
    public function password(string $field): bool{
        if($this->minLength($field, 8)){
            return true;
        };
        $this->errors[$field] = Translation::of('passwordSmall');
        return false;
    }

    /**
     * checks if the second password entry matches the first one
     * @param string $field
     * @return bool
     * @throws \Exception
     */
    public function passwordVerif(string $field): bool{
        if ($this->password($field) && $this->password('password_verif')){
            if($this->datas[$field] != $this->datas['password_verif']){
                $this->errors[$field] = Translation::of('errorPasswordVerif');
                return false;
            };
            return true;
        }
        $this->errors[$field] = Translation::of('passwordSmall');
        return false;
    }
}

