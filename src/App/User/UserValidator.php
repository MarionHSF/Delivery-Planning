<?php
namespace User;

use App\Validator;

class UserValidator extends Validator{

    /**
     * validates data of add function
     * @param array $datas
     * @return array|bool
     */
    public function validatesAdd(array $datas) {
        parent::validates($datas);
        $this->validate('company_name', 'minLength', 1);
        $this->validate('name', 'minLength', 1);
        $this->validate('firstname', 'minLength', 1);
        $this->validate('phone', 'phone', );
        $this->validate('email', 'emailUniq');
        $this->validate('password', 'passwordVerif');
        $this->validate('id_lang', 'minLength', 1);
        $this->validate('id_role', 'minLength', 1);
        return $this->errors;
    }

    /**
     * validates data of edit function within email and password
     * @param array $datas
     * @return array|bool
     */
    public function validatesEdit(array $datas) {
        parent::validates($datas);
        $this->validate('company_name', 'minLength', 1);
        $this->validate('name', 'minLength', 1);
        $this->validate('firstname', 'minLength', 1);
        $this->validate('phone', 'phone', );
        $this->validate('id_lang', 'minLength', 1);
        $this->validate('id_role', 'minLength', 1);
        return $this->errors;
    }

    /**
     * validates data of edit email function
     * @param array $datas
     * @return array|bool
     */
    public function validatesEmail(array $datas) {
        parent::validates($datas);
        $this->validate('email', 'emailUniq');
        return $this->errors;
    }

    /**
     * validates data of edit password function
     * @param array $datas
     * @return array|bool
     */
    public function validatesPassword(array $datas) {
        parent::validates($datas);
        $this->validate('password', 'passwordVerif');
        return $this->errors;
    }

    /**
     * validates data of account connexion
     * @param array $datas
     * @return array|bool
     */
    public function validatesConnexion(array $datas) {
        parent::validates($datas);
        $this->validate('email', 'email');
        $this->validate('password', 'minLength', 1);
        return $this->errors;
    }

    /**
     * validates data of edit password function
     * @param array $datas
     * @return array|bool
     */
    public function validatesForgetPassword(array $datas) {
        parent::validates($datas);
        $this->validate('email', 'email');
        return $this->errors;
    }

}