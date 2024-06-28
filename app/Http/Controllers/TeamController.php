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
    public function login(Request $request)
    {

        Log::debug('login-----');
        $credentials = $request->all();
        // dd($credentials);
        $user = $this->twichService->getUser();
        $exist = $this->userService->userLogin($credentials['email'], $credentials['password']);


        if (!empty($exist['user']) && $exist['user'] != false) {
            // dd($exist);
            Log::debug('exist-----');
            return redirect('dashboard');
        } else {
            return redirect('admin')->with(['message' => $exist['message']]);
        }

        // return view('admin.adminLogin');
    }
    public function list()
    {

        if (Auth::user()) {
            $this->route = FacadesRoute::current();

            $this->user_model = Auth::user();
            $users = $this->userService->getUsersModel();
            // dd($users);
            return view('admin.list', ['users' => $users, 'user_model' => $this->user_model,'route' => $this->route]);
        } else {
            return redirect('admin');
        }
    }

    public function deleteScheduler()
    {

        if (Auth::user()) {
            $this->user_model = Auth::user();
            $users = $this->userService->getUsersModel();
            $ranges = $this->rangeService->all();
            $user = $this->userService->getById(31);
            // dd($users);
            return view('admin.deleteScheduler', ['users' => $users, 'user_model' => $this->user_model,'user' => $user,'ranges' => $ranges]);
        } else {
            return redirect('admin');
        }
    }
    public function deleteSchedulerUser($id){

        $streamers_supported = [];
        $test = null;
        if (Auth::user()) {
            $scheduler = $this->scheduleService->getById($id);
            Log::debug(json_encode('scheduler *************** '.$scheduler));
            $user = $scheduler->user;
            Log::debug(json_encode('user *************** '.$user));
            $scheduler->delete();
            // dd($users);

            if (isset($user->score)) {
                $date = new Carbon($user->score->updated_at);
                $date->tz = $user->time_zone;
                $test = $date->format('d-m-Y H:i:s');
            }

            if (isset($user->streamSupport)) {
                // dd($user->streamSupport);
                foreach ($user->streamSupport as $streamer) {
                    $supported = json_decode($streamer->supported);
                    // dd($supported->name);
                    array_push($streamers_supported, ['name' => $supported->name, 'time' => $streamer->updated_at]);
                }
            }

            $groupedArray = $this->scheduleService->getSchedulerByUser($user);
            return view('admin.show', ['user' => $user, 'week' => $groupedArray, 'date' => $test, 'streamers_supported' => $streamers_supported]);
        } else {
            return redirect('admin');
        }
    }

    public function usersDeleted(){

        if (Auth::user()) {
            $this->route = FacadesRoute::current();
            $this->user_model = Auth::user();
            $users = $this->userService->getUsersDeleted();
            // dd($users);
            return view('admin.list', ['users' => $users, 'user_model' => $this->user_model,'route' => $this->route]);
        } else {
            return redirect('admin');
        }
    }

    public function uploadUser($id){

        if (Auth::user()) {
            $this->user_model = Auth::user();
            $user = $this->userService->getById($id);

            $user->deleted = false;
            // $user->status = false;

            $user->save();
            Log::debug('user updated' . json_encode($user));

            return redirect('dashboard');
        }
    }
    public function schedulers()
    {
        $week_time_zone = [];
        $new_streams = [];
        $users = [];
        $all = [];
        if (Auth::user()) {
            $this->user_model = Auth::user();
            // dump($this->user_model);
            $users = $this->userService->getUsersModel();
            $week = $this->scheduleService->getSchedulerWeek($this->user_model);

            $supports_ids = $this->streamSupportService->getSupportsStreams();
            if(isset($supports_ids)){
                foreach ($supports_ids as $key => $support) {
                    // $test = $support->unique('supported');
                    // dd($support);
                    $user_obteined = $this->userService->getById($support->user_id);
                    if (isset($user_obteined)) {
                        $supports = $this->streamSupportService->getStreamSupportsByUserId($user_obteined->id);
                        $user['name'] =  $user_obteined->channel;
                        $collection = new Collection();
                        foreach ($supports as $key => $support_found) {

                            $sup = json_decode($support_found->supported);
                            $date = new Carbon($support_found->updated_at);
                            $date->tz = $this->user_model->time_zone;
                            $collection->push((object)[
                                'id' => $sup->id,
                                'name' => $sup->name,
                                'day' => $date->format('d-m-Y'),
                                'minutes' => $date->format('i')
                            ]);
                            // array_push($new_streams,$sup->name);
                        }
                        $unique = $collection->unique('id');
                        $new_streams = $unique->toArray();
                        // dump($new_streams);
                        $user['supported'] = $new_streams;
                        array_push($all, $user);
                    }
                }
            }
            // dump($all);
            // dump($supports_ids);
            // dump('ssss');
            // dump($all);
            // foreach ($week as $key => $value) {

            //     $date = new Carbon($value->start);
            //     $date->tz = $this->user_model->time_zone;

            //     array_push($week_time_zone,['date' => $date->format('d-m-Y H:i:s'),'user' => $value->user->name]);

            // }
            // dd($week);
            return view('admin.schedulers', ['users' => $users, 'user_model' => $this->user_model, 'week' => $week, 'all' => $all]);
        } else {
            return redirect('admin');
        }
    }

    public function rankingsPoints(){

        if (Auth::user()) {
            $this->route = FacadesRoute::current();

            $this->user_model = Auth::user();
            $users = $this->userService->getUsersTop();
            // dd($users);
            return view('admin.rankings-points', ['users' => $users, 'user_model' => $this->user_model,'route' => $this->route]);
        } else {
            return redirect('admin');
        }
    }

    public function rankingsSchedulers(){
        if (Auth::user()) {
            $this->route = FacadesRoute::current();

            $this->user_model = Auth::user();
            $users = $this->userService->getUsersSchedulers();
            // dd($users);
            return view('admin.rankings-schedulers', ['users' => $users, 'user_model' => $this->user_model,'route' => $this->route]);
        } else {
            return redirect('admin');
        }
    }

    public function schedulersDelete(Request $request){

        $scheduler = $request->all();
        $inicio = new Carbon($scheduler['inicio'] .' 00:00:00');
        $fin = new Carbon($scheduler['fin'] . ' 23:59:59');

        $schedulers = $this->scheduleService->getSchedulersToDelete($inicio,$fin);
        if(isset($schedulers)){
            if(count($schedulers) > 0){
                foreach ($schedulers as $key => $scheduler_by_user) {

                        $this->scheduleService->delete($scheduler_by_user->id);

                }
            }

        }
        // dd($schedulers);
        $allUsers = $this->userService->all();
        foreach ($allUsers as $key => $user) {

            $user_array['user_id'] = $user->id;
            $user_array['points_day'] = 0;
            $user_array['points_week'] = 0;
            $result = $this->scoreService->update($user_array);

            // $schedulers_by_user = $this->scheduleService->getByUserId($user->id);

            // if(isset($schedulers_by_user)){
            //     if(count($schedulers_by_user) > 0){
            //         foreach ($schedulers_by_user as $key => $scheduler_by_user) {
            //             $date = new Carbon($scheduler_by_user->start);
            //             $day = $date->format('l');
            //             if($day != 'Sunday'){
            //                 $this->scheduleService->delete($scheduler_by_user->id);
            //             }
            //         }
            //     }
            //     Log::debug('result:  ---' . json_encode($result));
            // }

        }
        return redirect('dashboard');
    }
    public function edit($id)
    {
        Log::debug('id **** ---------------------------------- ' . json_encode($id));
        if (Auth::user() && intval($id) != 0) {
            $this->team = $this->teamService->getById($id);
            Log::debug('teeeam **** ---------------------------------- ' . json_encode($this->team));



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



    public function getDateAndTime($days)
    {
        $this->user_model = Auth::user();
        $list_day = [];
        if(isset($days)){
            if (count($days) > 0) {
                $date = new Carbon($days[0]->start);
                $list_day['date'] = $date->format('Y-m-d');
                $list_day['times'] = [];
                foreach ($days as $key => $value) {
                    // dump($value->start);
                    $day = new Carbon($value->start);
                    $day->tz = $this->user_model->time_zone;
                    array_push($list_day['times'], $day->format('H:i'));
                }
                // return $list_day;
            }
        }

        return $list_day;
        // dump($list_day);

    }
    public function delete($id)
    {
        if (Auth::user()) {
            $this->user_model = Auth::user();
            $team = $this->teamService->getById($id);
            Log::debug('user to delete' . json_encode($team));
            $team->delete();


            // $users = $this->userService->getUsersModel();
            // return view('admin.list', ['users' => $users]);
            return view('actions/teams');
        }
        //  else {
        //     return redirect('admin');
        // }
    }
    public function post(Request $request)
    {
        $team = $request->all();
        Log::debug('team ---------------' . json_encode($team));
        $this->user_model = Auth::user();
        $validated = $request->validate([
            'name' => 'required',

        ]);
        $team = $this->teamService->update($team);
        $team->users[0]->platform_id;

        return view('actions.teams',['platform_id' =>  $team->users[0]->platform_id]);
    }




}
