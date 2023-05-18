<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelHelper
 * @package App\Helpers
 */
class ModelHelper
{


    /**
     * @param string
     * @return ?Model
     */
    public static function defineModel(string $namespace): ?Model
    {
        $model = null;

        if (class_exists($namespace)) {
            $model = new $namespace();
        }

        return $model;
    }

}