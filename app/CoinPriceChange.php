<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/25/17
 * Time: 10:32 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CoinPriceChange extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coin_price_changes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coin_price_id',
        'price_change',
        'change',
        'percentage_change'
    ];

    /**
     * Fields that should not be mass assigned
     * @var array
     */
    protected $guarded = array('id', 'created_at', 'updated_at');

    /*
     * Relationship between a coin price and a coin
     */
    public function coinPrice()
    {
        return $this->hasMany(CoinPrice::class, 'coin_id');
    }
}