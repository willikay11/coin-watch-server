<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 10/12/17
 * Time: 11:15 AM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class MyCoin extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'my_coins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'coin_id',
    ];

}