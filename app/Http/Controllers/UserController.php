<?php
/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 10/11/17
 * Time: 9:14 AM
 */

namespace App\Http\Controllers;

use App\CoinWatch\User\UserRepository;
use Illuminate\Http\Request;
use App\CoinWatch\MyCoins\MyCoinsRepository;

class UserController extends Controller
{

    /**
     * @var UserRepository
     */
    protected $userRepository;
    protected $myCoinsRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, MyCoinsRepository $myCoinsRepository)
    {
        $this->userRepository = $userRepository;
        $this->myCoinsRepository = $myCoinsRepository;
    }

    /**
     * @param $email
     * @return $this|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function checkIfUserExists($email, $uid)
    {
        $user = $this->userRepository->getUserByEmail($email);

        if($user)
        {
            $response = [
                'success' => true,
                'data' => $user,
                'selectedCoins' => $user->myCoins()->count()
            ];

            return $this->toResponse(null, $response);
        }
        else {
            $input = [
                'email' => $email,
                'uid' => $uid
            ];

            $user = $this->userRepository->createUser($input);

            if($user)
            {
                $response = [
                    'success' => true,
                    'data' => $user,
                    'selectedCoins' => $user->myCoins()->count()
                ];
                return $this->toResponse(null, $response);
            }

            $response = [
                'success' => false,
            ];

            return $this->toResponse(null, $response);
        }
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