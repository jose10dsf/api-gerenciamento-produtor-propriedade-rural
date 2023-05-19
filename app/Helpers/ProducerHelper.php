<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class ProducerHelper
 * @package App\Helpers
 */
class ProducerHelper
{

    /**
     * @param array $producer
     * @param array $property
     * @return array $data
     */
    public static function formatData($producer, $property): array {
        $data = array();
        if(array_key_exists('id', $producer)) $data["producer_id"] = $producer["id"];
        if(array_key_exists('name', $producer)) $data["producer_name"] = $producer["name"];
        if(array_key_exists('cpf', $producer)) $data["cpf"] = $producer["cpf"];
        if(array_key_exists('id', $property)) $data["property_id"] = $property["id"];
        if(array_key_exists('name', $property)) $data["property_name"] = $property["name"];
        if(array_key_exists('rural_cadastre', $property)) $data["rural_cadastre"] = $property["rural_cadastre"];
        return $data;
    }

}