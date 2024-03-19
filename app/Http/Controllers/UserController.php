<?php

namespace App\Http\Controllers;

use App\Services\BillingService;
use App\Services\TwichService;
use App\Services\UserService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public $code;
    public $code_test;
    public $url_twitch;
    public $url;
    public $url_test;
    public $client_id;
    public $force_verify;
    public $complete_url;
    public $test_url;
    public $user;
    private $twichService;
    private $userService;

    public function __construct(TwichService $twichService, UserService $userService)
    {
        $this->twichService = $twichService;
        $this->userService = $userService;
    }

    public function login()
    {
        $urlToken = $this->twichService->login();
        return redirect($urlToken);
    }

    public function getToken(Request $request)
    {
        $this->twichService->getToken($request);
        $user = $this->twichService->getUser();
        $exist = $this->userService->userExists($user['display_name'].'@gmail.com',$user['id']);
        if($exist == false){
            $this->userService->create($user);
        }
        
        
        return redirect('/profile');
    }
    public function logout()
    {
        session()->forget('user');
        session()->forget('user-log');
        session()->forget('status');
        return redirect('/');
    }
    public function update(){
        if(session()->has('user')){
            $session_user = session('user');
            $user = $this->userService->getByIdandTwichId($session_user,$session_user->twich_id);
            
        }
        
    }

    
    
}
