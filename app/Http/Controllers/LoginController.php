<?php

namespace App\Http\Controllers;

use App\Enums\PlatformType;
use App\Services\PlatformService;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\SupportScoreService;
use App\Services\TeamService;
use App\Services\TrovoService;
use App\Services\TwichService;
use App\Services\UserService;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
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
    private $twichService;
    private $userService;
    private $scheduleService;
    private $supportScoreService;
    private $scoreService;
    private $teamService;
    private $trovoService;
    private $platformService;
    public $platform;


    public function __construct(
        TwichService $twichService,
        UserService $userService,
        ScheduleService $scheduleService,
        SupportScoreService $supportScoreService,
        ScoreService $scoreService,
        TeamService $teamService,
        TrovoService $trovoService,
        PlatformService $platformService
    ) {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->supportScoreService = $supportScoreService;
        $this->scoreService = $scoreService;
        $this->teamService = $teamService;
        $this->trovoService = $trovoService;
        $this->platformService = $platformService;
    }

    public function loginTwich()
    {
        $urlToken = $this->twichService->login();
        return redirect($urlToken);
    }
    public function loginTwichTest($platform)
    {
        $urlToken = $this->twichService->loginTest($platform);
        return redirect($urlToken);
    }
    public function loginTrovo()
    {
        $urlToken = $this->trovoService->login();
        return redirect($urlToken);
    }
    public function getToken(Request $request)
    {
        $support_user = [];
        $supportScoreArray = [];
        $total = 0;
        $this->twichService->getToken($request);
        $user = $this->twichService->getUser();
        if(array_key_exists('email',$user)){
            $user_model = $this->userService->userExists($user['email'], $user['id'],$user['platform_id']);
        }else{
            $user_model = $this->userService->userExists($user['display_name'].'@gmail.com',$user['id'],$user['platform_id']);
        }


        // dump($user_model);

        if ($user_model == false) {
            // TODO validar y traer el primer equipo que tenga menos de 100 usuarios para asignar
//            $team = $this->teamService->getFirstTeamAviable();
            $user_model_created = $this->userService->create($user);
            if (session()->exists('support_to_user_id')) {

                $support_user['user_id'] = $user_model_created->id;
                $support_user['channel'] = $user_model_created->channel;

                $supportScoreArray['user_id'] =  session('support_to_user_id');
                $supportScoreArray['point'] = 0;
                $supportScoreArray['user'] = json_encode($support_user);
                $this->supportScoreService->create($supportScoreArray);
            }
        }else{
            $total = 0;
            if(count($user_model->supportScores)> 0){
                $total = count($user_model->supportScores->where('point', 1));
            }

            if ($total != 0) {
                $user_model->points_support = $total;
                $user_model->save();
            }

        }
        if (isset($user_model->time_zone) && !empty($user_model->time_zone)) {
            return redirect('summary');
        } else {
            return redirect('profile');
        }
    }

    public function getTokenTest(Request $request)
    {
        $support_user = [];
        $supportScoreArray = [];
        $total = 0;
        $this->twichService->getTokenTest($request);
        $this->platform = $this->platformService->getById(session('platform_id'));
        if($this->platform->id == PlatformType::twich){
            $user = $this->twichService->getUser();
        }else{
            $user = $this->trovoService->getUser();
        }

        if(array_key_exists('email',$user)){
            $user_model = $this->userService->userExists($user['email'], $user['id'],$user['platform_id']);
        }else{
            $user_model = $this->userService->userExists($user['display_name'].'@gmail.com',$user['id'],$user['platform_id']);
        }

        if ($user_model == false) {
            // TODO validar y traer el primer equipo que tenga menos de 100 usuarios para asignar

            $user_model_created = $this->userService->create($user);
            if (session()->exists('support_to_user_id')) {

                $support_user['user_id'] = $user_model_created->id;
                $support_user['channel'] = $user_model_created->channel;

                $supportScoreArray['user_id'] =  session('support_to_user_id');
                $supportScoreArray['point'] = 0;
                $supportScoreArray['user'] = json_encode($support_user);
                $this->supportScoreService->create($supportScoreArray);
            }
        }else{
            $total = 0;
            if(count($user_model->supportScores)> 0){
                $total = count($user_model->supportScores->where('point', 1));
            }

            if ($total != 0) {
                $user_model->points_support = $total;
                $user_model->save();
            }

        }

        if (isset($user_model->time_zone) && !empty($user_model->time_zone)) {
            return redirect('summary');
        } else {
            return redirect('profile');
        }
    }

    public function getTokenTrovo(Request $request)
    {
        $this->trovoService->getToken($request);
        $user = $this->trovoService->getUser();
        $user_model = false;
        if(isset($user)){
            if(array_key_exists('email',$user)){
                $user_model = $this->userService->userExistsTrovo($user['email'], $user['id']);
            }else{
                $user_model = $this->userService->userExistsTrovo($user['id']);
            }

            if ($user_model == false) {
                // TODO validar y traer el primer equipo que tenga menos de 100 usuarios para asignar
//            $team = $this->teamService->getFirstTeamAviable();
                $user_model_created = $this->userService->create($user,PlatformType::trovo);
                if (session()->exists('support_to_user_id')) {

                    $support_user['user_id'] = $user_model_created->id;
                    $support_user['channel'] = $user_model_created->channel;

                    $supportScoreArray['user_id'] =  session('support_to_user_id');
                    $supportScoreArray['point'] = 0;
                    $supportScoreArray['user'] = json_encode($support_user);
                    $this->supportScoreService->create($supportScoreArray);
                }
            }else{
                $total = 0;
                if(count($user_model->supportScores)> 0){
                    $total = count($user_model->supportScores->where('point', 1));
                }

                if ($total != 0) {
                    $user_model->points_support = $total;
                    $user_model->save();
                }

            }
            if (isset($user_model->time_zone) && !empty($user_model->time_zone)) {
                return redirect('summary');
            } else {
                return redirect('profile');
            }
        }else{
            return redirect('home');
        }


//        return json_encode($request->all());
    }
    public function logoutTwich()
    {
        session()->forget('user');
        session()->forget('user-log');
        session()->forget('status');
        return redirect('/');
    }

    public function login_test()
    {
        return view('admin.login_test');
    }
    public function login_post(Request $request)
    {
        $user_model = null;
        $user_response = [];
        $credentials = $request->all();
        $user_model = $this->userService->userLoginTwich($credentials['email'], $credentials['password']);
        if(isset($user_model) && $user_model != false){
            session(['access_token' => $user_model->token]);

                $user_response['display_name'] = $user_model->channel;
                $user_response['email'] = $user_model->email;
                $user_response['id'] = $user_model->stream_id;
                $user_response['platform_id'] = $user_model->platform_id;
                if($user_model->platform_id == PlatformType::twich){
                    $user_response['class_nav'] = 'banner-twich';
                }else{
                    $user_response['class_nav'] = 'banner-trovo';
                }
                if (isset($user_model->img_profile) && !empty($user_model->img_profile)) {
                    $user_response['profile_image_url'] = $user_model->img_profile;
                } else {
                    $user_response['profile_image_url'] = 'https://static-cdn.jtvnw.net/jtv_user_pictures/6471351b-ea90-4cd2-828b-406a7dea08e1-profile_image-300x300.png';
                }
                session(['user' => $user_response]);

        }

        if ($user_model) {

            $total = count($user_model->supportScores->where('point', 1));
            if ($total != 0) {
                $user_model->points_support = $total;
                $user_model->update();
            }
            $this->scoreService->evaluatePoint($user_model);
            if (isset($user_model->time_zone) && !empty($user_model->time_zone)) {
                return redirect('summary');
            } else {
                return redirect('profile');
            }
        } else {
            return redirect('login_test');
        }




        // return view('admin.adminLogin');
    }
}
