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
use App\CoinPriceChange;
use App\CoinWatch\Synchronization\SynchronizationRepository;
use App\Synchronization;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Carbon\Carbon;
use App\CoinWatch\Coins\CoinRepository;
use Illuminate\Database\Eloquent\Collection;

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

        $syncRepository = new SynchronizationRepository();

        $syncTime = Carbon::now();

        $sync = $syncRepository->store($syncTime);

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
                    'synchronization_id' => $sync->id,
                    'price_usd' => $coin_price['Price_usd'],
                    'price_cny' => $coin_price['Price_cny'],
                    'price_eur' => $coin_price['Price_eur'],
                    'price_gbp' => $coin_price['Price_gbp'],
                    'price_rur' => $coin_price['Price_rur'],
                    'volume_24h' => $coin_price['Volume_24h'],
                    'timestamp' => Carbon::createFromTimestamp($coin_price['Timestamp']),
                ];

                $coinPrice = $coinPriceRepository->createCoinPrices($coinPriceData);

                $changes = self::getPriceChange($coin->id, $coinPrice);

                $coinPriceChangeData = [
                    'coin_price_id' => $coinPrice->id,
                    'price_change' => $changes['price_change'],
                    'change' => $changes['change'],
                    'percentage_change' => $changes['percentage_change']
                ];

                CoinPriceChange::create($coinPriceChangeData);
            }

        }

        \Log::info("Pulled information as at ".Carbon::now());
    }

    /**
     * @param $coinId
     * @param $coin_price
     * @return array
     */
    public static function getPriceChange($coinId, $coinPrice)
    {
        $twentyFourHourLow = CoinPrice::where('id', '!=', $coinPrice->id)->where('coin_id', $coinId)->where('timestamp', '>=', Carbon::now()->subDay(1))->orderBy('price_usd')->first();

        if(is_null($twentyFourHourLow))
        {
            $changeInPrice = 0;

            return [
                'price_change' => $changeInPrice,
                'change' => self::checkStatus($changeInPrice),
                'percentage_change' => 0
            ];

        }

        $changeInPrice = $twentyFourHourLow->price_usd - $coinPrice->price_usd;

        return [
            'price_change' => $changeInPrice,
            'change' => self::checkStatus($changeInPrice),
            'percentage_change' => ($changeInPrice/$twentyFourHourLow->price_usd) * 100
        ];
    }

    /**
     * @param $changeInPrice
     * @return string
     */
    public static function checkStatus($changeInPrice)
    {
        if($changeInPrice > 0)
        {
            return 'positive';
        }
        elseif($changeInPrice < 0)
        {
            return 'negative';
        }
        else{
            return 'no change';
        }
    }
}