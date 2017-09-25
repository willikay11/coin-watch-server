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
use App\Synchronization;
use Illuminate\Contracts\Support\Responsable;

class GetCoins implements Responsable
{
    /**
     * Use sort filter paginate
     */
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

    /**
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getAllCoins()
    {
        $sync = Synchronization::latest()->first();

        $filter = [
            'column' => 'synchronization_id',
            'sign' => '=',
            'value' => $sync->id
        ];

        return $this->sortFilterPaginate(new CoinPrice(), [$filter], function ($coinPrice) {
            return [
                'name' => $coinPrice->coin->name,
                'ticker' => explode('/', $coinPrice->coin->ticker)[0],
                'price_usd' => number_format($coinPrice->price_usd, 2),
                'volume_24h' => number_format($coinPrice->volume_24h, 4),
                'price_change' => number_format($coinPrice->newCoinPriceChange->price_change, 2),
                'percentage_change' => $coinPrice->newCoinPriceChange->percentage_change,
                'change' => $coinPrice->newCoinPriceChange->change,
            ];
        }, null, null, 'price_usd');
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