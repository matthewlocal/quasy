<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\General;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\User;
use App\UserLists;


class ApiController extends Controller
{

    //Basic Version
    public function version()
    {
        $details = [
            'type'       => 'API Version',
            'version'    => '0.0.1',
            'build_date' => '17/08/2020',
            'message'    => 'success',
            'status'     => true,
        ];

        return response()
            ->json($details, 200)
            ->header('Content-Type', 'application/json');
    }

    //Registration
    public function register(Request $request)
    {

        //Validation
        $validator = Validator::make($request->all(), [
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:8',
        ]);

        if ($validator->fails()) {

            $details = [
                'type'      => 'register',
                'api_token' =>  false,
                'message'   =>  'Validation Errors',
                'errors'    =>  $validator->errors(),
                'status'    =>  false
            ];

            return response()
            ->json($details, 404)
            ->header('Content-Type', 'application/json');
       }

        $user = user::Where('email',$request->email)->first();

        //Check if the user exists
        if($user){

            $details = [
                'type'      => 'register',
                'api_token' =>  false,
                'message'   => 'User Exists',
                'status'    =>  false
            ];

            return response()
            ->json($details, 404)
            ->header('Content-Type', 'application/json');

        }

        //build user array
        $userArray = [
            'password'           => Hash::make($request->password),
            'email'              => $request->email,
            'email_verification' => md5(random_int(100000, 999999)),
            'api_token'          => Str::random(60)
        ];

        //Create User
        $user = User::create($userArray);

        //Return json with api token
        if($user){

            $details = [
                'type'          => 'register',
                'api_token'     => $userArray['api_token'],
                'message'       => 'User Created',
                'status'        => true
            ];

        }else{

            $details = [
                'type'      => 'register',
                'api_token' =>  false,
                'message'   => 'User Not Created',
                'status'    =>  false
            ];

        }

        return response()
        ->json($details, 200)
        ->header('Content-Type', 'application/json');
    }


    //Login
    public function login(Request $request)
    {
        //Validation
        $validator = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        //Find user by email
        $user = user::Where('email', $validator['email'])->first();

        //If user exists check password hash matches
        if ($user) {

            if (Hash::check($validator['password'], $user->password)) {

                return response()
                    ->json([
                    'type'      => 'login',
                    'message'   => 'Login Successful',
                    'status'    => true,
                    'api_token' => $user->api_token,
                ], 200);

            }
        }

        return response()
            ->json([
                'type'    => 'login',
                'message' => 'Login Failed',
                'status'  => false,
            ], 200)
            ->header('Content-Type', 'application/json');
    }

    public function getUserList(Request $request){

        $user = $request->user();

        $list = UserLists::whereUserId($user->id)->get();

        if($list->count() >= 1){

            return response()
            ->json([
                'type'    => 'list',
                'message' => 'List found',
                'status'  => true,
                'list' => $list,
            ], 200)
            ->header('Content-Type', 'application/json');
        }

        return response()
        ->json([
            'type'    => 'list',
            'message' => 'No lists found',
            'status'  => false,
        ], 200)
        ->header('Content-Type', 'application/json');
    }

}
