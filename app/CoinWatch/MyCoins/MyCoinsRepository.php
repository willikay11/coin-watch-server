<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 10/12/17
 * Time: 11:17 AM
 */

namespace App\CoinWatch\MyCoins;

use App\MyCoin;

class MyCoinsRepository
{

    public function getMyCoinById($id)
    {
        return MyCoin::find($id);
    }

    public function saveMyCoin($input)
    {
        return MyCoin::create($input);
    }

    public function getMyCoinByUserId($userId)
    {
        return MyCoin::where('user_id', $userId)->get();
    }
}