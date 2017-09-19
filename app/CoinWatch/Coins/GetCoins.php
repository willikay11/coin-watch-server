<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 5:20 PM
 */

namespace App\CoinWatch\Coins;

use App\Coin;
use App\CoinPrice;
use App\CoinWatch\ApiController\SortFilterPaginate;
use Illuminate\Contracts\Support\Responsable;

class GetCoins implements Responsable
{
    use SortFilterPaginate;
    /**
     * @var CoinRepository
     */
    protected $coinRepository;
    /**
     * ExampleObject constructor.
     * @param CoinRepository $coinRepository
     */
    public function __construct(CoinRepository $coinRepository)
    {
        $this->coinRepository = $coinRepository;
    }


    public function getAllCoins()
    {
        return $this->sortFilterPaginate(new Coin(), [], function ($coin) {
            $lastPrice = $coin->coinPrice->last();
            return [
                'name' => $coin->name,
                'price_usd' => number_format($lastPrice->price_usd, 2),
                'volume_24h' => number_format($lastPrice->volume_24h, 4),
            ];
        }, null, null);

//        $coins = $this->coinRepository->getAllCoins();
//
//        $coinData = $coins->map(function($coin)
//        {
//            $lastPrice = $coin->coinPrice->last();
//
//            return [
//                'name' => $coin->name,
//                'price_usd' => number_format($lastPrice->price_usd, 2),
//                'volume_24h' => number_format($lastPrice->volume_24h, 4),
//            ];
//        });
//
//        return $coinData;
    }

    /**
     * @param null $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function toResponse($request = null)
    {
        return response(
            $this->getAllCoins()
        );
    }
}