<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 5:20 PM
 */

namespace App\CoinWatch\Coins;

use Illuminate\Contracts\Support\Responsable;

class GetCoins implements Responsable
{
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

    /**
     * @return \Illuminate\Support\Collection|static
     */
    public function getAllCoins()
    {
        $coins = $this->coinRepository->getAllCoins();

        $coinData = $coins->map(function($coin)
        {
            $lastPrice = $coin->coinPrice->last();

            return [
                'name' => $coin->name,
                'price_usd' => $lastPrice->price_usd,
                'volume_24h' => number_format($lastPrice->volume_24h, 4),
            ];
        });

        return $coinData->sortByDesc('price_usd');
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