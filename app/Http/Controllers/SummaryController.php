<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Services\ScheduleService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\Dump;

class SummaryController extends Controller
{
    private $userService;
    private $scheduleService;
    public function __construct(UserService $userService,ScheduleService $scheduleService)
    {

        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
    }
    public function index(){

        $active = false;
        $times = [];
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

            return view('summary',["user"=>$userModel,'ref' => $ref]);
        }else{
            return redirect('/');
        }

    }

    public function summaryByUserId($id){

        $active = false;
        $times = [];
        $ref = [];
        // dd(session('user'));
//        if(session()->exists('user')){
//            $user = session('user');
//            dd($id);
            $userModel = $this->userService->getById($id);

            if(isset($userModel->supportScores) && count($userModel->supportScores)){
                foreach ($userModel->supportScores as $key => $supportScore) {
                    $stream = json_decode($supportScore->user);
                    array_push($ref,$stream->channel);
                }
            }


            if($userModel->status){

                session(['status' =>$userModel->status]);
            }
            else{
                session(['status' => 0]);
            }

            return view('summary',["user"=>$userModel,'ref' => $ref,'user_watched' => true]);
//        }else{
//            return redirect('/');
//        }

    }
}
