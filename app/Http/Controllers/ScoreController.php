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

    
  
}
