<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\Coin;
use App\CoinPrice;
use App\CoinWatch\ApiController\SortFilterPaginate;
use App\CoinWatch\Coins\CoinRepository;
use App\CoinWatch\MyCoins\MyCoinsRepository;
use App\CoinWatch\User\UserRepository;
use App\Synchronization;
use Illuminate\Http\Request;

class CoinController extends Controller
{

    use SortFilterPaginate;

    /**
     * @var CoinRepository
     */
    protected $coinRepository;
    protected $myCoinsRepository;
    protected $userRepository;

    /**
     * CoinController constructor.
     * @param CoinRepository $coinRepository
     */
    public function __construct(CoinRepository $coinRepository,
                                MyCoinsRepository $myCoinsRepository,
                                UserRepository $userRepository)
    {
        $this->coinRepository = $coinRepository;
        $this->myCoinsRepository = $myCoinsRepository;
        $this->userRepository = $userRepository;
    }

    /*
     * Get all coins for select view
     */
    public function getAllCoinsForSelect()
    {
        $coins = $this->coinRepository->getAllCoins();

        $coins = $coins->map(function($coin)
        {
           return[
               'id' => $coin->id,
               'name' => $coin->name
           ];
        });

        $response = [
            'success' => true,
            'data' => $coins
        ];

        return self::toResponse(null, $response);
    }

    /*
     * Store selected coins
     */
    public function storeSelectedCoins(Request $request)
    {
        $selectedCoins = $request->get('coins');

        $uid = $request->get('uid');

        $user = $this->userRepository->getUserByUid($uid);
        
        foreach ($selectedCoins as $selectedCoin)
        {
            $input = [
                'user_id' => $user->id,
                'coin_id' => $selectedCoin
            ];

            $this->myCoinsRepository->saveMyCoin($input);
        }

        $response =[
            'success' => true,
        ];

        return self::toResponse(null, $response);
    }

    /*
     * Get a users Coins
     */
    public function getMyCoins($uid)
    {
        $user = $this->userRepository->getUserByUid($uid);

        $myCoins = $this->myCoinsRepository->getMyCoinByUserId($user->id)->pluck('coin_id');

        $sync = Synchronization::latest()->first();

        $filter = [
            'column' => 'synchronization_id',
            'sign' => '=',
            'value' => $sync->id
        ];

        return $this->sortFilterPaginate(new CoinPrice(), [$filter], function ($coinPrice) {
            return [
                'id' => $coinPrice->coin->id,
                'name' => $coinPrice->coin->name,
                'ticker' => explode('/', $coinPrice->coin->ticker)[0],
                'price_usd' => number_format($coinPrice->price_usd, 2),
                'volume_24h' => number_format($coinPrice->volume_24h, 4),
                'price_change' => number_format($coinPrice->newCoinPriceChange->price_change, 2),
                'percentage_change' => number_format($coinPrice->newCoinPriceChange->percentage_change, 2),
                'change' => $coinPrice->newCoinPriceChange->change,
            ];
        }, function ($coinPrice) use ($myCoins)
        {
            return $coinPrice->whereIN('coin_id', $myCoins);

        }, null, 'price_usd');
    }

    /*
     * Search for a coin
     */
    public function search($query = null)
    {
        if(is_null($query))
        {
            $coins = $this->coinRepository->getAllCoins();
        }
        else
        {
            $coins = Coin::search($query)->get();
        }

        $coins = $coins->map(function($coin)
        {
            return[
                'id' => $coin->id,
                'name' => $coin->name
            ];
        });

        $response = [
            'success' => true,
            'data' => $coins
        ];

        return self::toResponse(null, $response);

    }
    /**
     * @param null $request
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     */
    public function toResponse($request = null, $data)
    {
        return response($data);
    }

}