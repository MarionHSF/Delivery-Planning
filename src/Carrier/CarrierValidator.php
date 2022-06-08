<?php
namespace Carrier;

use App\Validator;

class CarrierValidator extends Validator{

    /**
     * @param array $data
     * @return array|bool
     */
    public function validates(array $data) {
        parent::validates($data);
        $this->validate('name', 'minLength', 1);
        return $this->errors;
    }

}