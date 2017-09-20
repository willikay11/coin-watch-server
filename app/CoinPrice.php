<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 8:38 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class CoinPrice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coin_prices';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'coin_id',
        'synchronization_id',
        'name',
        'price_usd',
        'price_cny' ,
        'price_eur',
        'price_gbp',
        'price_rur',
        'timestamp',
        'volume_24h'
    ];

    /**
     * Fields that should not be mass assigned
     * @var array
     */
    protected $guarded = array('id', 'created_at', 'updated_at');

    /**
     * Relationship between coin and a coin price
     */
    public function coin()
    {
        return $this->belongsTo(Coin::class);
    }
}