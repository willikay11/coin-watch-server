<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/25/17
 * Time: 2:20 PM
 */

namespace App\CoinWatch\Coins;


use App\CoinPrice;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;

class GetIndividualCoin implements Responsable
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getCoin()
    {
       $coinPrice = CoinPrice::where('coin_id', $this->id)->where('timestamp', '>=', Carbon::now()->subDay(1));

       return [
           'last' => $coinPrice->orderBy('created_at', 'desc')->first()->price_usd,
           'min' => $coinPrice->min('price_usd'),
           'max' => $coinPrice->max('price_usd'),
       ];

    }

    /**
     * @param null $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function toResponse($request = null)
    {
        return response(
            $this->getCoin()
        );
    }
}