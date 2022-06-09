<?php
namespace Calendar;

use App\Validator;

class EventValidator extends Validator{

    /**
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
        $this->validate('name', 'minLength', 1);
        $this->validate('date', 'date', );
        $this->validate('start', 'beforeTime','end' );
        return $this->errors;
    }

}