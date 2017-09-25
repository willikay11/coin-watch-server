<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/20/17
 * Time: 8:23 AM
 */

namespace App\CoinWatch\Synchronization;

use App\Synchronization;

class SynchronizationRepository
{

    /**
     * @param $timestamp
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function store($timestamp)
    {
        return Synchronization::create([
            'time' => $timestamp
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static[]
     */
    public function getLastSynchronizationTime()
    {
        return Synchronization::latest()->first();
    }
}