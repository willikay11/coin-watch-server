<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 8:52 AM
 */
namespace App\CoinWatch\CoinPrices;

use App\Coin;
use App\CoinPrice;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\CoinWatch\Coins\CoinRepository;

class Coins
{

    /**
     * Get coins from World Coin Index API
     */
    public static function getCoins()
    {
        $client = new Client();

        $response = $client->get('https://www.worldcoinindex.com/apiservice/json?key=eTQZWlGLQq29fv0LAbj2TjB2C');

        $results = json_decode($response->getBody(), true);

        $coinRepository = new CoinRepository();

        $coinPriceRepository = new CoinPriceRepository();

        foreach ($results as $result)
        {
            foreach ($result as $coin_price)
            {
                $coin = $coinRepository->getCoinByName($coin_price['Name']);

                if(is_null($coin))
                {
                    $coinData = [
                        'name' => $coin_price['Name'],
                        'ticker' => $coin_price['Label']
                    ];

                    $coin = $coinRepository->createCoin($coinData);
                }

                $coinPriceData = [
                    'coin_id' => $coin->id,
                    'price_usd' => $coin_price['Price_usd'],
                    'price_cny' => $coin_price['Price_cny'],
                    'price_eur' => $coin_price['Price_eur'],
                    'price_gbp' => $coin_price['Price_gbp'],
                    'price_rur' => $coin_price['Price_rur'],
                    'volume_24h' => $coin_price['Volume_24h'],
                    'timestamp' => Carbon::createFromTimestamp($coin_price['Timestamp']),
                ];

                $coinPriceRepository->createCoinPrices($coinPriceData);
            }

        }

        \Log::info("Pulled information as at ".Carbon::now());
    }
}