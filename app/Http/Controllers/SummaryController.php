<?php

namespace App\Http\Controllers;

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
            
            $userModel = $this->userService->userExistsActive($user['display_name'].'@gmail.com',$user['id']);
            
            
            $currentStreams = $this->scheduleService->getStreamByUser($userModel);
            
            if(count($currentStreams) > 0){
                $times = $this->scheduleService->getTimes($currentStreams,$userModel);
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

            return view('summary',["user"=>$userModel,'times' => json_encode($times),'ref' => $ref]);
        }else{
            return redirect('/');
        }
        
    }
}
