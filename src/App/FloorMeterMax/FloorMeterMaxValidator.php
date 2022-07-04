<?php
namespace FloorMeterMax;

use App\Validator;

class FloorMeterMaxValidator extends Validator{

    /**
     * @param array $datas
     * @return array|bool
     */
    public function validates(array $datas) {
        parent::validates($datas);
        $this->validate('floor_meter_max', 'minLength', 1);
        $this->validate('floor_meter_max', 'int');
        return $this->errors;
    }

}