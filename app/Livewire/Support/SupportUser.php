<?php

namespace App\Livewire\Support;


use App\Models\StreamSupport;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class SupportUser extends Component
{
    public $userId;
    public $user_model_id;
    public $user_model;
    public $user_stream;
    public $url;
    public $url_chat;
    public $channel;
    public $url_plus_point;
    public $url_summary;
    public $url_support;
    private $userService;

    public function mount($user_id)
    {
        $this->userId = $user_id;
        $this->userService = new UserService();
        $this->url_plus_point = env('APP_URL') . 'support/plus_point';
        $this->url_summary = env('APP_URL') . 'summary';
        $this->url_support = env('APP_URL') . 'support';
    }
    public function render()
    {
        $this->url = 'https://player.twitch.tv/?channel=lucho952000&parent=www.comunidadnc.com';
        $this->url_chat = 'https://www.twitch.tv/embed/lucho952000/chat?parent=www.comunidadnc.com';
        $this->channel = 'lucho952000';
        $this->user_stream = $this->userService->getById($this->userId);
        if(isset($this->user_stream)){
            $this->url = 'https://player.twitch.tv/?channel='.$this->user_stream->channel.'&parent=www.comunidadnc.com';
            $this->url_chat = 'https://www.twitch.tv/embed/'.$this->user_stream->channel.'/chat?parent=www.comunidadnc.com';
            $this->channel = $this->user_stream->channel;
        }

        return view('livewire.support.support-user');
    }
    public function validateTime(){
        $dateToComp = Carbon::parse(now());
        $current_minute = intval($dateToComp->format('i'));
       Log::debug('');

        if ($current_minute < env('TIME_MINIMUN_TO_PLUS_POINT')){
            return redirect()->route('support_user',['user_id' => $this->userId])->with('message', 'Aun el stream continua termina de verlo.');
        }else{
            $this->plusPointToUserv2($this->userId);
            return redirect()->route('summary');
        }

    }
    public function plusPointToUserv2($user_streaming_id)
    {
        $this->userService = new UserService();
        $this->user_model_id = session('user');
        $user_model = $this->userService->getByIdandTwichId($this->user_model_id['id']);
        $minutes_avaible = env('TIME_TO_NOT_BE_LATE');//15
        $sec_avaible = 59;
        $dateToComp = Carbon::parse(now());
        $current_minute = intval($dateToComp->format('i'));

        if ($current_minute >= env('TIME_MINIMUN_TO_PLUS_POINT')){

            if(isset($user_model) && !empty($user_streaming_id)){
                if(isset($user_model->score)){
                    $score = $user_model->score;
                    $dateToCompare = Carbon::parse(now());
                    $user_streaming = $this->userService->getById($user_streaming_id);
                    $supportStreams = $user_model->streamSupport;
                    $exist_supported = false;

                    $results = StreamSupport::whereDate('updated_at', $dateToCompare->format('Y-m-d'))
                        ->whereTime('updated_at', '>=', $dateToCompare->format('H:00:00'))
                        ->whereTime('updated_at', '<=', $dateToCompare->format('H:'.$minutes_avaible.':'.$sec_avaible))
                        ->where('user_id',$user_model->id)
                        ->whereJsonContains('supported->id',$user_streaming_id)
                        ->get();
                    Log::debug('$results --' . json_encode($results));
                    if(count($results) == 1){
                        if($score->points_day < 10){
                            if(!empty($user_streaming)){

                                if (count($supportStreams)) {
                                    foreach ($supportStreams as $key => $supportStream) {

                                        $support_created = json_decode($supportStream->supported);
                                        if ($support_created->id == $user_streaming->id) {
                                            $exist_supported = true;
                                            $supportStream->supported = json_encode($support_created);
                                            $supportStream->update();
                                        }
                                    }
                                }
                                if($exist_supported == false || count($supportStreams) == 0){
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
                                Log::debug('***** score updated user------------ ' .  json_encode($user_model->id.' - ' . $user_model->channel));
//                            return redirect()->back();
                            }
                        }
                    }
                    else{
                        Log::debug('***** user has already socore ------------ ' . json_encode($user_model->id.' - ' . $user_model->channel));
                    }
                }
            }
            return 'ok';
        }else{
            return 'error';
        }
    }
}
