<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use App\Services\TwichService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
class HomeController extends Controller
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
    public function index()
    {
        $twoElements1 = [];
        $twoElements2 = [];
        $twoElements3 = [];
        $twoElements4 = [];
        $active = false;
        $times = [];
        // if(session()->exists('support_to_user')){
        //     dump(session('support_to_user'));
        // }
        if(session()->exists('user')){
            $user = session('user');
            
            $userModel = $this->userService->userExistsActive($user['display_name'].'@gmail.com',$user['id']);
            $currentStreams = $this->scheduleService->getStreamByUser($userModel);
            
            if(count($currentStreams) > 0){
                $times = $this->scheduleService->getTimes($currentStreams,$userModel);
            }
            // @dd($active);
            Log::debug('model------------------------');
            Log::debug(json_encode($userModel));
            if($userModel->status){
               
                session(['status' => $userModel->status]);
            }
            else{
                session(['status' => 0]);
            }

            
        }
        $users = $this->userService->getUsersTop();
        if($users){
            $users = $users->toArray();
        
            $twoElements1 = array_slice($users, 0, 3);
            
            // Get two elements starting from index 3
            $twoElements2 = array_slice($users, 3,3);
             // Get two elements starting from index 3
             $twoElements3 = array_slice($users, 6, 3);
              // Get two elements starting from index 3
            $twoElements4 = array_slice($users, 9,1);
        }
       
        $top = 1;
        $top_two = 4;
        $top_three = 7;
        $top_four = 10;

        return view('home',['users' => $users,'twoElements1' => $twoElements1,'twoElements2' => $twoElements2,
        'twoElements3' => $twoElements3,'twoElements4' => $twoElements4,'top' => $top,'top_two' => $top_two,'top_three' => $top_three,'top_four' => $top_four]);
        
        
    }

  
}
