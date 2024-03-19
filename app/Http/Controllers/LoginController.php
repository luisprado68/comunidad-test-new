<?php

namespace App\Http\Controllers;

use App\Services\BillingService;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\SupportScoreService;
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


    public function __construct(
        TwichService $twichService,
        UserService $userService,
        ScheduleService $scheduleService,
        SupportScoreService $supportScoreService,
        ScoreService $scoreService
    ) {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->supportScoreService = $supportScoreService;
        $this->scoreService = $scoreService;
    }

    public function loginTwich()
    {
        $urlToken = $this->twichService->login();
        // Log::debug('**************** urlToken ***********************');
        // Log::debug(json_encode($urlToken));
        return redirect($urlToken);
    }

    public function getToken(Request $request)
    {
        $support_user = [];
        $supportScoreArray = [];
        $total = 0;
        $this->twichService->getToken($request);
        $user = $this->twichService->getUser();
        Log::debug('get token----------------------');
        Log::debug(json_encode($user));
        $user_model = $this->userService->userExists($user['email'], $user['id']);
        

        // dump($user_model);

        if ($user_model == false) {
            $user_model_created = $this->userService->create($user);
            if (session()->exists('support_to_user_id')) {
               
                $support_user['user_id'] = $user_model_created->id;
                $support_user['channel'] = $user_model_created->channel;

                $supportScoreArray['user_id'] =  session('support_to_user_id');
                $supportScoreArray['point'] = 0;
                $supportScoreArray['user'] = json_encode($support_user);
                $this->supportScoreService->create($supportScoreArray);
                // Log::debug('crear supportScoreArray');   
                // Log::debug(json_encode($supportScoreArray));
                
                // $total = count($user_model_created->supportScores->where('point',1));
            }
        }else{
            if(count($user_model->supportScores)> 0){
                $total = count($user_model->supportScores->where('point', 1));
            }
            
            if ($total != 0) {
                $user_model->points_support = $total;
                $user_model->update();
            }
            $this->scoreService->evaluatePoint($user_model);
        }
       
        if (isset($user_model->time_zone) && !empty($user_model->time_zone)) {
            return redirect('summary');
        } else {
            return redirect('profile');
        }
        // return redirect('/profile');
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
        $user_response = [];
        Log::debug('login-----');
        $credentials = $request->all();
        // dd($credentials);


        $user_model = $this->userService->userLogin($credentials['email'], $credentials['password']);
        Log::debug('login admin------------');
        Log::debug(json_encode($user_model));
        session(['access_token' => $user_model->token]);

        $user_twich = $this->twichService->getUser();
        // dd($user_test);
        if (isset($user_twich) && !empty($user_twich)) {
            $user_response = $user_twich;
        } else {

            $user_response['display_name'] = $user_model->channel;
            $user_response['id'] = $user_model->twich_id;
            if (isset($user_model->img_profile) && !empty($user_model->img_profile)) {
                $user_response['profile_image_url'] = $user_model->img_profile;
            } else {
                $user_response['profile_image_url'] = 'https://static-cdn.jtvnw.net/jtv_user_pictures/6471351b-ea90-4cd2-828b-406a7dea08e1-profile_image-300x300.png';
            }
            session(['user' => $user_response]);
        }


        // $user_response['profile_image_url'] = 'https://static-cdn.jtvnw.net/jtv_user_pictures/6471351b-ea90-4cd2-828b-406a7dea08e1-profile_image-300x300.png';
        // dd($user);
        if ($user_model) {

            $total = count($user_model->supportScores->where('point', 1));
            if ($total != 0) {
                $user_model->points_support = $total;
                $user_model->update();
            }
            $this->scoreService->evaluatePoint($user_model);
            Log::debug('exist-----');
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
