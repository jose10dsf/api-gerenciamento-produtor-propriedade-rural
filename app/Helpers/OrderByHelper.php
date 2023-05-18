<?php

declare(strict_types=1);

namespace App\Helpers;

use InvalidArgumentException;

/**
 * Class OrderByHelper
 * @package App\Helpers
 */
class OrderByHelper
{
    public static function treatOrderBy(string $orderBy, array $orderByFields = []): array
    {
        $orderByArray = [];

        foreach (explode(',', $orderBy) as $value) {
            $value = trim($value);

            if (!preg_match("/^(-)?[A-Za-z0-9_]+$/", $value)) {
                throw new InvalidArgumentException('O parâmetro "order_by" não está em um formato válido.');
            }
            if (strstr($value, '-')) $field = substr($value, 1);  
            else $field = $value;
            //$field = strstr($value, '-');
            if(!in_array($field, $orderByFields, true)) {
                throw new InvalidArgumentException("Não é possível ordenar o resultado da pesquisa pelo campo ${field}, passado como parâmetro na QueryString.");
            }
            $orderByArray[$value] = 'ASC';

            if (strstr($value, '-')) {
                $orderByArray[$value] = 'DESC';
            }
        }

        return $orderByArray;
    }
}