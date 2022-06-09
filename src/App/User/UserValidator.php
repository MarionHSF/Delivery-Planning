<?php
namespace User;

use App\Validator;

class UserValidator extends Validator{

    /**
     * @param array $datas
     * @return array|bool
     */
    public function validates(array $datas) {
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

}