<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/20/17
 * Time: 8:21 AM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Synchronization extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'synchronizations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time',
    ];

    /**
     * Fields that should not be mass assigned
     * @var array
     */
    protected $guarded = array('id', 'created_at', 'updated_at');
}