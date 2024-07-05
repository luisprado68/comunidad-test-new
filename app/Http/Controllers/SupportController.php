<?php

namespace App\Http\Controllers;

use App\Enums\PlatformType;
use App\Services\ScheduleService;
use App\Services\TrovoService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    private $userService;
    private $scheduleService;
    private $twichService;
    private $trovoService;

    public $user;
    public $show_streams = false;

    public function __construct(
        UserService $userService,
        ScheduleService $scheduleService,
        TwichService $twichService,
        TrovoService $trovoService
        )
    {

        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
        $this->twichService = $twichService;
        $this->trovoService = $trovoService;
    }
    public function index(){
        $active = false;
        $date_string = ' --';
        $arrayStream = [];
        $times = [];
        $userModel = null;
        if(session()->exists('user')){
            $this->user = session('user');

            if(array_key_exists('platform_id',$this->user)){
                if(array_key_exists('email',$this->user)){
                    $user_model = $this->userService->userExistsActive($this->user['email'],$this->user['id'],$this->user['platform_id']);
                }else{
                    $user_model = $this->userService->userExistsActive($this->user['display_name'].'@gmail.com',$this->user['id'],$this->user['platform_id']);
                }

            }else{
                $user_model = $this->userService->userExistsActive($this->user['email'],$this->user['id']);
            }

            if(isset($user_model) && $user_model->status){

                session(['status' => $user_model->status]);
            }
            else{
                session(['status' => 0]);
            }
//            $user_model = $this->userService->getByIdandTwichId($this->user['id']);
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

                        if($user_model->platform_id == PlatformType::twich){
                            $stream = $this->twichService->getStream($currentStream->user);
                        }else{
                            $stream = $this->trovoService->getStream($currentStream->user);
                        }

                    $size['instagram'] =  $currentStream->user->instagram;
                    $size['facebook'] = $currentStream->user->facebook;
                    $size['youtube'] = $currentStream->user->youtube;
                    //Todo revisar que muestre los camps con los valres devueltos por trovo
                    if(isset($stream) && !empty($stream)){

                        if($user_model->platform_id == PlatformType::twich){
                            $size['name'] = $stream['user_name'];
                            $size['login'] = 'https://www.twitch.tv/'. $stream['user_login'] ;
                            $size['img'] = str_replace("{width}x{height}", "500x300", $stream['thumbnail_url']);
                            $size['stream_id'] = $currentStream->user->stream_id;
                        }else{
                            $size['name'] = $currentStream->user->channel;
                            $size['login'] = $stream['channel_url'] ;
                            $size['img'] = !empty($stream['thumbnail']) ? $stream['thumbnail'] : $currentStream->user->img_profile;
                            $size['stream_id'] = $currentStream->user->stream_id;
                        }
                        array_push($arrayStream,$size);
                    }else{
                        if($user_model->platform_id == PlatformType::twich){
                            $size['login'] = 'https://www.twitch.tv/'. $currentStream->user->channel ;
                        }else{
                            $size['login'] = 'https://trovo.live/s/'. $currentStream->user->username ;
                        }
                        $size['name'] = $currentStream->user->channel;
                        $size['img'] =  $currentStream->user->img_profile;
                        $size['stream_id'] = $currentStream->user->stream_id;
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

    public function currentStream(){

        return view('support_stream');
    }
}
