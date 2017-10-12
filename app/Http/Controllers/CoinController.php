<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 9/18/17
 * Time: 4:35 PM
 */

namespace App\Http\Controllers;


use App\CoinWatch\Coins\CoinRepository;
use App\CoinWatch\MyCoins\MyCoinsRepository;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CoinController extends Controller
{

    /**
     * @var CoinRepository
     */
    protected $coinRepository;
    protected $myCoinsRepository;

    /**
     * CoinController constructor.
     * @param CoinRepository $coinRepository
     */
    public function __construct(CoinRepository $coinRepository, MyCoinsRepository $myCoinsRepository)
    {
        $this->coinRepository = $coinRepository;
        $this->myCoinsRepository = $myCoinsRepository;
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

    public function storeSelectedCoins(Request $request)
    {
        $selectedCoins = $request->get('coins');

        $userId = $request->get('userId');

        foreach ($selectedCoins as $selectedCoin)
        {
            $input = [
                'user_id' => $userId,
                'coin_id' => $selectedCoin
            ];

            $this->myCoinsRepository->saveMyCoin($input);
        }

        $response =[
            'success' => true,
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