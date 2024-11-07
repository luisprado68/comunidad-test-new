<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
class ScoreController extends Controller
{
    public $profile_image_url;
    public $response;
    private $twichService;
    private $userService;
    private $scheduleService;
    public function __construct(TwichService $twichService, UserService $userService,ScheduleService $scheduleService)
    {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
    }


    public function getPointSupport($user_name){
        $user = $this->userService->getByChannel($user_name);
        // dump(json_encode($user));
        session(['support_to_user_id' => $user->id] );
        return redirect('/');
        // dump($user);
    }

    public function getCoins(){
        $ref = [];
        // dd(session('user'));
        if(session()->exists('user')){
            $user = session('user');
            if(array_key_exists('platform_id',$user)){

                if(array_key_exists('email',$user)){
                    $userModel = $this->userService->userExistsActive($user['email'],$user['id'],$user['platform_id']);
                }else{
                    $userModel = $this->userService->userExistsActive($user['display_name'].'@gmail.com',$user['id'],$user['platform_id']);
                }
            }else{
                $userModel = $this->userService->userExistsActive($user['email'],$user['id']);
            }

            foreach ($userModel->supportScores as $key => $supportScore) {
                $stream = json_decode($supportScore->user);
                array_push($ref,$stream->channel);
            }

            if($userModel->status){

                session(['status' =>$userModel->status]);
            }
            else{
                session(['status' => 0]);
            }

            return view('getCoins',["user"=>$userModel,'ref' => $ref]);
        }else{
            return redirect('/');
        }
    }



}
