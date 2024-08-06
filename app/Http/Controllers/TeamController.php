<?php

namespace App\Http\Controllers;

use App\Services\BillingService;
use App\Services\RangeService;
use App\Services\RoleService;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\StreamSupportService;
use App\Services\SupportScoreService;
use App\Services\TeamService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;
use Stevebauman\Location\Facades\Location;

class TeamController extends Controller
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
    public $user_model;
    public $team;
    public $route;
    private $twichService;
    private $userService;
    private $scheduleService;
    private $streamSupportService;
    private $rangeService;
    private $scoreService;
    private $rolesService;
    private $teamService;

    public function __construct(
        TwichService $twichService,
        UserService $userService,
        ScheduleService $scheduleService,
        StreamSupportService $streamSupportService,
        RangeService $rangeService,
        ScoreService $scoreService,
        RoleService $rolesService,
        TeamService $teamService
    ) {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
        $this->streamSupportService = $streamSupportService;
        $this->rangeService = $rangeService;
        $this->scoreService = $scoreService;
        $this->rolesService = $rolesService;
        $this->teamService = $teamService;
    }

    public function index()
    {
        // $location = Location::get(request()->ip());
        // dump($location);
        // $this->validateDates($location);


        return view('admin.adminLogin');
    }

    public function validateDates($location){

        $currentDateTime = Carbon::now();
        $otherDateTime = Carbon::parse('2024-03-12T21:00:00');
        if ($location) {
            $currentDateTime->tz = $location->timezone;
            $otherDateTime->tz = $location->timezone;
            dump('si');
        } else {
            dump('no');
            $timezone = "America/Argentina/Buenos_Aires";
            $currentDateTime->tz = $timezone;
            $otherDateTime->tz = $timezone;
        }
            dump($currentDateTime);
            dump($otherDateTime);

        if($currentDateTime->gt($otherDateTime)){
            dump('finished');
        }elseif ($currentDateTime->lt($otherDateTime)) {
            dump('active');
        }
    }

    public function list()
    {
        if (Auth::user()) {
            $this->route = FacadesRoute::current();

            $this->user_model = Auth::user();
            $users = $this->userService->getUsersModel();
            return view('admin.list', ['users' => $users, 'user_model' => $this->user_model,'route' => $this->route]);
        } else {
            return redirect('admin');
        }
    }

    public function edit($id)
    {
        if (Auth::user() && intval($id) != 0) {
            $this->team = $this->teamService->getById($id);
            return view('admin.team-edit', ['team' => $this->team]);
        } else {
            return redirect('admin');
        }
    }

    public function listByPlatform($platform_id)
    {
        return view('actions.teams',['platform_id' => $platform_id]);
    }
    public function show($id)
    {
        $users = null;

        if (Auth::user()) {
            $this->team = $this->teamService->getById($id);

            return view('admin.team-show', ['team' => $this->team]);
        } else {
            return redirect('admin');
        }
    }
    public function post(Request $request)
    {
        $team = $request->all();
        $this->user_model = Auth::user();
        $validated = $request->validate([
            'name' => 'required',

        ]);
        $team = $this->teamService->update($team);
        $team->users[0]->platform_id;

        return view('actions.teams',['platform_id' =>  $team->users[0]->platform_id]);
    }
}
