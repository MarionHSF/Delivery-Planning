<?php
namespace Supplier;

use App\Validator;

class SupplierValidator extends Validator{

    /**
     * @param array $datas
     * @return array|bool
     */
    public function validates(array $datas) {
        parent::validates($datas);
        $this->validate('name', 'minLength', 1);
        return $this->errors;
    }

}