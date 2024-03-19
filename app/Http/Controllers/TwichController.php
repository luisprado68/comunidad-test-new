<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\StreamSupportService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwichController extends Controller
{
    private $userService;
    private $scheduleService;
    private $streamSupportService;

    private $twichService;
    private $scoreService;
    public $showAgendas = false;
    public $schedules_by_user_new;
    public $week;
    public $day;
    public $user_model;
    public $agenda;
    public $user;
   

    public function __construct(TwichService $twichService, UserService $userService,
    ScheduleService $scheduleService, ScoreService $scoreService,StreamSupportService $streamSupportService )
    {

        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
        $this->twichService = $twichService;
        $this->scoreService = $scoreService;
        $this->streamSupportService = $streamSupportService;
    }




   

   
}
