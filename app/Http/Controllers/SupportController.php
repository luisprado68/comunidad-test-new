<?php

namespace App\Http\Controllers;

use App\Enums\PlatformType;
use App\Models\Score;
use App\Models\StreamSupport;
use App\Services\ScheduleService;
use App\Services\StreamSupportService;
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
    private $streamSupportService;

    public $user;
    public $user_model;
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
        $this->streamSupportService = new StreamSupportService();
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
                    $this->user_model = $this->userService->userExistsActive($this->user['email'],$this->user['id'],$this->user['platform_id']);
                }else{
                    $this->user_model = $this->userService->userExistsActive($this->user['display_name'].'@gmail.com',$this->user['id'],$this->user['platform_id']);
                }

            }else{
                $this->user_model = $this->userService->userExistsActive($this->user['email'],$this->user['id']);
            }

            if(isset($this->user_model) && $this->user_model->status){

                session(['status' => $this->user_model->status]);
            }
            else{
                session(['status' => 0]);
            }
//            $user_model = $this->userService->getByIdandTwichId($this->user['id']);
            // dump($user_model);
            $currentStreams = $this->scheduleService->getCurrentStream($this->user_model);
            $nextHour = $this->scheduleService->getNextStream($this->user_model);
            // dump($nextHour);
            if(!empty($nextHour) && isset($nextHour)){
                $next =  new Carbon($nextHour->start);
                $next->tz= $this->user_model->time_zone;
            $day = $next->format('l');
            $date = $next->format('d-m ');
            $hour = $next->format('H:i');
            // dump($next->format('l'));
            // dump($currentStreams);
            $date_string = ' '.trans('user.create.'.strtolower($day)).' ' . $date .'a las '. $hour;
            // dump($date_string);
            }
//            dump($currentStreams);
            if(count($currentStreams) > 0){
                //levante la imagen del canal en base de datos y cuando esta en on live muestre la img del directo

                foreach ($currentStreams as $key => $currentStream) {

                        if($this->user_model->platform_id == PlatformType::twich){
                            $stream = $this->twichService->getStream($currentStream->user);
                        }else{
                            $stream = $this->trovoService->getStream($currentStream->user);
                        }
                    $size['id'] = $currentStream->user->id;
                    $size['instagram'] =  $currentStream->user->instagram;
                    $size['facebook'] = $currentStream->user->facebook;
                    $size['youtube'] = $currentStream->user->youtube;
                    //Todo revisar que muestre los camps con los valres devueltos por trovo
                    if(isset($stream) && !empty($stream)){

                        if($this->user_model->platform_id == PlatformType::twich){
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
                        if($this->user_model->platform_id == PlatformType::twich){
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
            'date_string' => $date_string,'user' => $this->user_model,'times' => json_encode($times)]);
        }
        else{
            return redirect('/');
        }

    }

    public function currentStream(){

        return view('support_stream');
    }


    public function support_user($user_id){
        $id = $user_id;
        if(session()->exists('user')) {
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
        }
        return view('support_user',compact('id','user_model'));
    }

    public function plusPointToUser(Request $request)
    {
        $this->user = session('user');
        $user_model = $this->userService->getByIdandTwichId($this->user['id']);
        $data = $request->all();

        if(isset($user_model) && !empty($data)){
            if(isset($user_model->score)){
                $score = $user_model->score;
                $dateToCompare = Carbon::parse(now());
                $user_streaming = $this->userService->getById($data['user_streaming']);
                $supportStreams = $user_model->streamSupport;
                $exist_supported = false;
                Log::debug('*********** supportStreams*************');
                Log::debug(json_encode($supportStreams));



                $results = StreamSupport::whereDate('updated_at', $dateToCompare->format('Y-m-d'))
                    ->whereTime('updated_at', '>=', $dateToCompare->format('H:00:00'))
                    ->whereTime('updated_at', '<=', $dateToCompare->format('H:59:59'))
                    ->where('user_id',$user_model->id)
                    ->whereJsonContains('supported->id',$data['user_streaming'])
                    ->get();

                if(count($results) == 0){
                    if($score->points_day < 10){
                        if(!empty($user_streaming)){

                            if (count($supportStreams)) {
                                foreach ($supportStreams as $key => $supportStream) {

                                    $support_created = json_decode($supportStream->supported);
                                    Log::debug('*********** support_exist*************');
                                    Log::debug(json_encode($support_created));
                                    if ($support_created->id == $user_streaming->id) {
                                        $exist_supported = true;
                                        Log::debug('*********** pasassss*************');
                                        Log::debug(json_encode($support_created));
                                        $supportStream->supported = json_encode($support_created);
                                        $supportStream->update();
                                    }
                                }
                            }
                            if($exist_supported == false || count($supportStreams) == 0){
                                Log::debug('*********** crea supportStreams*************');
                                $support['id'] = $user_streaming->id;
                                $support['name'] = $user_streaming->channel;
                                $streamSupport['user_id'] = $user_model->id;
                                $streamSupport['supported'] = json_encode($support);
                                $created = $this->streamSupportService->create($streamSupport);
                            }
                            $last = $score->points_day + 1;

                            if($score->neo_coins < 1000){
                                $score->neo_coins = $score->neo_coins + 1;
                            }
                            $score->points_day = $last;
                            $user_support['id'] = $user_streaming->id;
                            $user_support['name'] = $user_streaming->channel;
                            $score->streamer_supported = json_encode($user_support);
                            $score->save();
                        }
                    }
                }
            }

        }
        return 'ok';
    }
}
