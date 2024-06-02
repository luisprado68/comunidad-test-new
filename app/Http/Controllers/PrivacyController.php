<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    private $userService;
    public function __construct(UserService $userService)
    {

        $this->userService = $userService;
    }
    public function index(){
        $active = false;
        if(session()->exists('user')){
            $user = session('user');

            if(array_key_exists('stream',$user)){
                $userModel = $this->userService->userExistsActive($user['email'],$user['id'],$user['stream']);
            }else{
                $userModel = $this->userService->userExistsActive($user['email'],$user['id']);
            }

            if($userModel->status){

                session(['status' =>$userModel->status]);
            }
            else{
                session(['status' => 0]);
            }
            return view('privacy');
        }


    }
}
