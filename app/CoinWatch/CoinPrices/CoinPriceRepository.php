<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 9:54 AM
 */

namespace App\CoinWatch\CoinPrices;


use App\CoinPrice;

class CoinPriceRepository
{

    /**
     * Create Coin Prices
     * @param array $input
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function createCoinPrices(array $input)
    {
        return CoinPrice::create($input);
    }
}