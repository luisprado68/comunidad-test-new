<?php

namespace App\Http\Controllers;

use App\Services\ScheduleService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    private $userService;
    private $scheduleService;
    private $twichService;

    public $user;
    public $show_streams = false;

    public function __construct(
        UserService $userService,
        ScheduleService $scheduleService,
        TwichService $twichService
        )
    {

        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
        $this->twichService = $twichService;
    }
    public function index(){
        $active = false;
        $date_string = ' --';
        $arrayStream = [];
        $times = [];
        if(session()->exists('user')){
            $this->user = session('user');

            $userModel = $this->userService->userExistsActive($this->user['display_name'].'@gmail.com',$this->user['id']);

            if($userModel->status){

                session(['status' => $userModel->status]);
            }
            else{
                session(['status' => 0]);
            }
            $user_model = $this->userService->getByIdandTwichId($this->user['id']);
            // dump($user_model);
            $currentStreams = $this->scheduleService->getCurrentStream($user_model);
            $nextHour = $this->scheduleService->getNextStream($user_model);
            // dump($nextHour);
            if(!empty($nextHour) && isset($nextHour)){
                $next =  new Carbon($nextHour->start);
                $next->tz= $user_model->time_zone;
            $day = $next->format('l');
            $date = $next->format('d-m ');
            $hour = $next->format('H:i');
            // dump($next->format('l'));
            // dump($currentStreams);
            $date_string = ' '.trans('user.create.'.strtolower($day)).' ' . $date .'a las '. $hour;
            // dump($date_string);
            }

            if(count($currentStreams) > 0){
                //levante la imagen del canal en base de datos y cuando esta en on live muestre la img del directo

                foreach ($currentStreams as $key => $currentStream) {

                    $stream = $this->twichService->getStream($currentStream->user);
                    $size['instagram'] =  $currentStream->user->instagram;
                    $size['facebook'] = $currentStream->user->facebook;
                    $size['youtube'] = $currentStream->user->youtube;
                    // $userTwich = $this->twichService->getUser($currentStream->user);
                    if(isset($stream) && !empty($stream)){
                        $size['name'] = $stream['user_name'];
                        $size['login'] = $stream['user_login'];
                        // dump($stream['thumbnail_url']);
                        $size['img'] = str_replace("{width}x{height}", "500x300", $stream['thumbnail_url']);
                        $size['twich_id'] = $currentStream->user->twich_id;

                        array_push($arrayStream,$size);
                    }else{
                        $size['name'] = $currentStream->user->channel;
                        $size['login'] =  $currentStream->user->channel;
                        $size['img'] =  $currentStream->user->img_profile;
                        $size['twich_id'] = $currentStream->user->twich_id;
                        array_push($arrayStream,$size);
                    }

                }
            }
            if(count($arrayStream)> 0){
                $this->show_streams = true;
            }
            // dump($arrayStream);
            return view('support',['streams'=> $arrayStream,'show_streams'=> $this->show_streams,
            'date_string' => $date_string,'user' => $user_model,'times' => json_encode($times)]);
        }
        else{
            return redirect('/');
        }

    }
}
