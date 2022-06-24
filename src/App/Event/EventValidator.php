<?php
namespace Event;

use App\Validator;

class EventValidator extends Validator{

    /**
     * validates data of add and edit function
     * @param array $datas
     * @return array|bool
     */
    public function validates(array $datas) {
        parent::validates($datas);
        $this->validate('id_carrier', 'minLength', 1);
        $this->validate('ids_suppliers', 'minLength', 1);
        $this->validate('order', 'minLength', 1);
        $this->validate('phone', 'phone', );
        $this->validate('email', 'email', );
        $this->validate('date', 'date', );
        $this->validate('start', 'beforeTime','end' );
        return $this->errors;
    }

    /**
     * validates data of reception validation function
     * @param array $datas
     * @return array|bool
     */
    public function validatesReceptionValidation(array $datas) {
        parent::validates($datas);
        $this->validate('date', 'date', );
        $this->validate('start', 'time');
        $this->validate('reception_line', 'minLength', 1);
        return $this->errors;
    }

}