<?php

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 8:46 AM
 */

namespace App\Console\Commands\CoinPrices;

use Illuminate\Console\Command;
use App\CoinWatch\CoinPrices\Coins;

class CoinPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin_prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the Coin Prices';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Coins::getCoins();
    }

}