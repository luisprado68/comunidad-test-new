<?php

namespace App\Http\Controllers;

use App\Models\StreamSupport;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\StreamSupportService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    private $twichService;
    private $userService;
    private $scheduleService;
    private $scoreService;
    private $streamSupportService;

    public function __construct(TwichService $twichService, UserService $userService,
    ScheduleService $scheduleService,ScoreService $scoreService,StreamSupportService $streamSupportService)
    {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
        $this->scoreService = $scoreService;
        $this->streamSupportService = $streamSupportService;
    }
    public function index(){
        $times = [];
        $user_array = [];
        $userModel = null;
        $streamSupports = [];

        if(session()->exists('user')){
            $user = session('user');
            
            $userModel = $this->userService->userExistsActive($user['display_name'].'@gmail.com',$user['id']);
           
            $streamSupports = $this->streamSupportService->getStreamSupportsByUserId($userModel->id);
            
            // dump($streamSupports);
            
            foreach ($streamSupports as $key => $score) {
                $user_found = $this->userService->getById($score->user_id);

                
                
                $support = json_decode($score->supported);
                $updated = new Carbon($score->updated_at);
                $updated->locale('es');
                $updated->tz = $user_found->time_zone;
                
                // $updated->format('d-m-Y H:i')
                array_push($user_array,['minutes' => $score->minutes,'channel' => $support->name,'time' => $updated->format('i'),
                'date' =>  trans('user.create.' .strtolower($updated->format('l'))).' '.$updated->format('d').' '.trans('user.create.' .strtolower($updated->format('F'))).' '.$updated->format('H:i')]);
            }
            // dd($user_array);
            if($userModel->status){
               
                session(['status' => $userModel->status]);
            }
            else{
                session(['status' => 0]);
            }
            return view('history',['user' => $userModel,'times' => json_encode($times),'scores' => $user_array]); 
        }
        else{
            return redirect('/');
        }
        
    }
}
