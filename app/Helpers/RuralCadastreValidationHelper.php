<?php 

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class RuralCadastreValidationHelper
 * @package App\Helpers
 */
class RuralCadastreValidationHelper
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isValidate($attribute, $value);
    }

    protected function isValidate($attribute, $value)
    {
        $c = preg_replace('/\D/', '', $value);
        if (strlen($c) != 11 || !preg_match("/^[0-9]{2}9[0-9]{8}$/", $c)) {
            return false;
        }
        return true;
    }
}