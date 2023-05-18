<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Http\Response;

/**
 * Class DefaultResponseHelper
 * @package App\Helpers
 */
class DefaultResponseHelper
{
    /**
     * @param array $data
     * @param int $statusCode
     * @return array
     */
    public static function success(array $data, int $statusCode = Response::HTTP_OK): array
    {
        return [
            'status_code' => $statusCode,
            'data' => $data
        ];
    }

    /**
     * @param string $errorMessage
     * @param int $statuCode
     * @param mixed $errorDescription
     * @return array
     */
    public static function error(string $errorMessage, int $statuCode = Response::HTTP_BAD_REQUEST, $errorDescription = null): array
    {
        return [
            'status_code' => $statuCode,
            'error' => true,
            'error_message' => $errorMessage,
            'error_description' => $errorDescription
        ];
    }
}