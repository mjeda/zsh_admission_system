<?php

namespace App\Support;

use App\Exceptions\ExternalServiceUnavailableException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

/**
 * Wrapper ya kuita API za NECTA / NACTE.
 *
 * Mfumo ukiwa haupatikani (connection error, timeout, 5xx) inarusha
 * ExternalServiceUnavailableException ambayo inaonyesha page ya
 * errors.service-unavailable. Majibu ya kawaida (hata 4xx) yanarudishwa
 * kama yalivyo ili controller iamue.
 */
class ExternalApi
{
    public const NECTA = 'NECTA';
    public const NACTE = 'NACTE';

    public static function call(string $service, string $action, callable $request): Response
    {
        try {
            $response = $request();
        } catch (ConnectionException $e) {
            throw new ExternalServiceUnavailableException($service, $action, $e);
        } catch (RequestException $e) {
            // Http::retry() inarusha RequestException baada ya majaribio kuisha
            if ($e->response->serverError()) {
                throw new ExternalServiceUnavailableException($service, $action, $e);
            }
            return $e->response;
        }

        if ($response->serverError()) {
            throw new ExternalServiceUnavailableException($service, $action);
        }

        return $response;
    }
}
