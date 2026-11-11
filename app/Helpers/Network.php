<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Network
{
    public static function checkAccessibility($address)
    {
        if (empty($address)) {
            return false;
        }

        try {
            if (filter_var($address, FILTER_VALIDATE_URL)) {
                $response = Http::timeout(3)
                    ->withoutVerifying()
                    ->get($address);

                return $response->successful();
            }

            if (filter_var($address, FILTER_VALIDATE_IP)) {
                $connection = @fsockopen(
                    $address,
                    80,
                    $errno,
                    $errstr,
                    3
                );

                if ($connection) {
                    fclose($connection);
                    return true;
                }
            }

            return false;

        } catch (\Throwable $e) {
            return false;
        }
    }
}
