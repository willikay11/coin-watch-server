<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 9:50 AM
 */
namespace App\CoinWatch\Coins;

use App\Coin;

class CoinRepository
{

    /**
     * Get coin by name
     * @param $name
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getCoinByName($name)
    {
        return Coin::where('name', $name)->first();
    }

    /**
     * Create coins
     * @param array $input
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function createCoin(array $input)
    {
        return Coin::create([$input]);
    }
}