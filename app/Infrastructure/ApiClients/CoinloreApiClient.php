<?php

namespace App\Infrastructure\ApiClients;

class CoinloreApiClient
{
    public function getCoinloreData(string $coinId): ?string
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinlore.net/api/ticker/?id=' . $coinId,
            CURLOPT_RETURNTRANSFER => true,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if ($response) {
            return $response;
        }

        return null;
    }
}
