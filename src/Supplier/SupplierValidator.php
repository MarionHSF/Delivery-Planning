<?php
namespace Supplier;

use App\Validator;

class SupplierValidator extends Validator{

    /**
     * @param array $data
     * @return array|bool
     */
    public function validates(array $data) {
        parent::validates($data);
        $this->validate('name', 'minLength', 3);
        return $this->errors;
    }

}