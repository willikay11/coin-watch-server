<?php

namespace App\CoinWatch\User;

use App\User;

/**
 * Created by PhpStorm.
 * User: mac-intern
 * Date: 10/11/17
 * Time: 9:12 AM
 */
class UserRepository
{

    /*
     * Get User by Id
     */
    public function getUserById($id)
    {
        return User::find($id);
    }

    /*
     * Get User by Email
     */
    public function getUserByEmail($email)
    {
        return  User::where('email', $email)->first();
    }

    /*
     * Save a new user
     */
    public function createUser($input)
    {
        return User::create($input);
    }
}