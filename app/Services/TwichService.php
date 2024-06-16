<?php

namespace App\Services;

use App\Enums\PlatformType;
use App\Models\Log as ModelsLog;
use App\Models\User;
use Broobe\Services\Service;
use Broobe\Services\Traits\{CreateModel, DestroyModel, ReadModel, UpdateModel};
use Carbon\Carbon;
use DateTimeZone;
use Error;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\Else_;

final class TwichService
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

    private $userService;
    private $streamSupportService;
    private $scoreService;
    private $platformService;
    public $platform;
    public $result;
    /**
     * Set model class name.
     *
     * @return void
     */
    // protected function setModel(): void
    // {
    //     $this->model = User::class;
    // }
    public function __construct()
    {
        $this->userService = new UserService();
        $this->streamSupportService = new StreamSupportService();
        $this->scoreService = new ScoreService();
        $this->platformService = new PlatformService();
    }
    public function login()
    {
        $this->code = Str::random(10);
        $this->code_test = 'code';
        $this->url_twitch = 'https://id.twitch.tv/oauth2/authorize';
        $this->url_test = 'http://localhost';
        $this->url = 'https://www.comunidadnc.com/login_token';
        $this->client_id = 'vjl5wxupylcsiaq7kp5bjou29solwc';
        $this->force_verify = 'true';
        $this->test_url = $this->url_twitch . '?response_type=' . $this->code . '&client_id=' . $this->client_id . '&redirect_uri=' . $this->url . '&scope=channel%3Amanage%3Amoderators+moderator%3Aread%3Achatters+user%3Aread%3Afollows+channel%3Aread%3Apolls+user%3Aread%3Aemail+chat%3Aedit+chat%3Aread&state=c3ab8aa609ea11e793ae92361f002671';
        $this->complete_url = $this->url_twitch . '?response_type=' . $this->code_test . '&client_id=' . $this->client_id . '&redirect_uri=' . $this->url . '&scope=channel%3Amanage%3Amoderators+moderator%3Aread%3Achatters+user%3Aread%3Afollows+channel%3Aread%3Apolls+user%3Aread%3Aemail+chat%3Aedit+chat%3Aread&state=c3ab8aa609ea11e793ae92361f002671';
//        Log::debug('url login formated :' . $this->complete_url);
        return $this->complete_url;
    }

    public function loginTest($id)
    {
//        dd($platform);
        session(['platform_id' => $id]);
        $this->platform = $this->platformService->getById($id);
        if(isset($this->platform)){
            $this->code = Str::random(10);
            $this->code_test = 'code';
            $this->url_twitch = $this->platform->url;
            $this->url = 'https://www.comunidadnc.com/login_token_test';
            $this->client_id = $this->platform->client_id;
            $this->complete_url = $this->url_twitch . '?response_type=' . $this->code_test . '&client_id=' . $this->client_id . '&redirect_uri=' . $this->url . $this->platform->scope;
            Log::debug('url login formated :' . $this->complete_url);
            return $this->complete_url;
        }
        return '';
    }


    public function getToken(Request $request)
    {
        $all = $request->all();
        Log::debug('request get token: ' . json_encode($all));
        $code = $request->get('code');

        $this->url_test = 'http://localhost';
        $this->url = 'https://www.comunidadnc.com/login_token';
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
        ];
        $options = [
            'form_params' => [
                'client_id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                'client_secret' => 'b6jng7psl6bcqztt3huqlj9uwj6txy',
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->url,
                'code' =>  $code,
            ],
        ];
        $request = new Psr7Request('POST', 'https://id.twitch.tv/oauth2/token', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        $result = json_decode($res->getBody(), true);
        Log::debug("result getToken-------------------------------------------");
        Log::debug(json_encode($result));
        session(['access_token' => $result['access_token']]);
        session(['refresh_token' => $result['refresh_token']]);
    }

    public function getTokenTest(Request $request)
    {
        $this->platform = $this->platformService->getById(session('platform_id'));
        $result = null;
        $all = $request->all();
        $code = $request->get('code');
        Log::debug('request get token: --------- ' . json_encode($all));
        $this->url = 'https://www.comunidadnc.com/login_token_test';

        if($this->platform->id == PlatformType::twich){
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
            ];
            $options = [
                'form_params' => [
                    'client_id' => $this->platform->client_id,
                    'client_secret' => $this->platform->client_secret,
                    'grant_type' => 'authorization_code',
                    'redirect_uri' => $this->url,
                    'code' =>  $code,
                ],
            ];

            $request = new Psr7Request('POST', $this->platform->url_token, $headers);
            $res = $client->sendAsync($request, $options)->wait();
            $this->result = json_decode($res->getBody(), true);

        }else{

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'client-id' => '7c23b5396452b6ade3f848bf8b606e7a'
            ])->post('https://open-api.trovo.live/openplatform/exchangetoken', [
                'client_secret' => '80ea1ddc012d0186fba1854354560927',
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $this->url,
            ]);
//
            if ($response->successful()) {
                // Handle successful response
                $this->result = $response->json();

                // Do something with $data
            } else {
                // Handle error
                $error = $response->json();
                Log::debug("Error getToken trovo-------------------------------------------");
                Log::debug(json_encode($error));
                // Do something with $error
            }
        }

        Log::debug("result getToken-------------------------------------------");
        Log::debug(json_encode($this->result));
        session(['access_token' => $this->result['access_token']]);
        session(['refresh_token' => $this->result['refresh_token']]);

    }

    public function getRefreshToken($user)
    {
        try {

        // Log::debug("getRefreshToken user-------------------------------------------");
        // Log::debug(json_encode($user->channel));

        if (isset($user->refresh_token)){
            $refresh_token = $user->refresh_token;
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
            ];
            $options = [
                'form_params' => [
                    'client_id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                    'client_secret' => 'b6jng7psl6bcqztt3huqlj9uwj6txy',
                    'grant_type' => 'refresh_token',
                    'refresh_token' =>  $refresh_token,
                ],
            ];
            $request = new Psr7Request('POST', 'https://id.twitch.tv/oauth2/token', $headers);
            $res = $client->sendAsync($request, $options)->wait();
            $result = json_decode($res->getBody(), true);
            // Log::debug("getRefreshToken result-------------------------------------------");
            // Log::debug(json_encode($result));
            // session(['access_token' => $result['access_token']]);
            $user->token = $result['access_token'];
            $user->refresh_token = $result['refresh_token'];
            $user->update();
        }else{

            ModelsLog::create([
                'action' => 'No existe el refresh_token ',
                'user_id' => $user->id,
                'date_action' => now(),
                'message' => 'refresh_token:  '.$user->channel
            ]);
        }

        } catch (Exception $e) {
            Log::debug("Error refresh_token " . $e->getMessage());

        }

    }

    public function getUser()
    {

        try {
            if (!empty(session('access_token'))) {
                $client = new Client();
                $headers = [
                    'Client-Id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                    'Authorization' => 'Bearer ' . session('access_token'),
                    'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
                ];
                $request = new Psr7Request('GET', 'https://api.twitch.tv/helix/users', $headers);
                $res = $client->sendAsync($request)->wait();
                $result = json_decode($res->getBody(), true);
                $this->user = $result['data'][0];
                $this->user['platform_id'] = PlatformType::twich;
                $this->user['username'] = $this->user['display_name'];

                Log::debug('user twich---------');
                Log::debug(json_encode($this->user));
                // $img = $this->user['profile_image_url'];
                session(['user' => $this->user]);
                return $this->user;
            }
        } catch (\Exception $e) {
            session()->forget('user');
            return null;
            Log::debug($e->getMessage());
        }
    }

    public function getVideo($user)
    {
        // https://static-cdn.jtvnw.net/cf_vods/d1m7jfoe9zdc1j/642cc3d8aefda37f1b85_shingineo_42081665833_1701532096//thumb/thumb0-440x248.jpg
        try {

            if (!empty(session('access_token'))) {
                $client = new Client();
                $headers = [
                    'Client-Id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                    'Authorization' => 'Bearer ' . session('access_token'),
                    'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
                ];
                $request = new Psr7Request('GET', 'https://api.twitch.tv/helix/videos?user_id=' . $user->stream_id, $headers);
                $res = $client->sendAsync($request)->wait();
                $result = json_decode($res->getBody(), true);
                $video = $result['data'][0];

                // $img = $this->user['profile_image_url'];
                // session(['video' => $video]);
                return $video;
            }
        } catch (\Exception $e) {

            return null;
            Log::debug($e->getMessage());
        }
    }

    public function getStream($user)
    {
        // https://static-cdn.jtvnw.net/cf_vods/d1m7jfoe9zdc1j/642cc3d8aefda37f1b85_shingineo_42081665833_1701532096//thumb/thumb0-440x248.jpg
        try {

            if (!empty(session('access_token'))) {
                $client = new Client();
                $headers = [
                    'Client-Id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                    'Authorization' => 'Bearer ' . session('access_token'),
                    'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
                ];
                $request = new Psr7Request('GET', 'https://api.twitch.tv/helix/streams?user_login=' . $user->channel, $headers);
                $res = $client->sendAsync($request)->wait();
                $result = json_decode($res->getBody(), true);
                $video = $result['data'][0];

                // $img = $this->user['profile_image_url'];
                // session(['video' => $video]);
                return $video;
            }
        } catch (\Exception $e) {

            return null;
            Log::debug($e->getMessage());
        }
    }

    public function getUserChatters($user)
    {
        $users = [];
        try {
            // https://static-cdn.jtvnw.net/cf_vods/d1m7jfoe9zdc1j/642cc3d8aefda37f1b85_shingineo_42081665833_1701532096//thumb/thumb0-440x248.jpg
            if ($user->token) {
                $client = new Client();
                $headers = [
                    'Client-Id' => 'vjl5wxupylcsiaq7kp5bjou29solwc',
                    'Authorization' => 'Bearer ' . $user->token,
                    'Cookie' => 'twitch.lohp.countryCode=AR; unique_id=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O; unique_id_durable=0JaqWdYXGWGHNufLw7yDUgf6IYGyiI9O',
                ];
                $request = new Psr7Request('GET', 'https://api.twitch.tv/helix/chat/chatters?broadcaster_id=' . $user->stream_id . '&moderator_id=' . $user->stream_id, $headers);
                $res = $client->sendAsync($request)->wait();
                $result = json_decode($res->getBody(), true);
                $users = $result['data'];
                Log::debug('users chatters------------------');
                Log::debug(json_encode($users));

                return $users;
            }
            return $users;
        } catch (\Exception $e) {

            return $users;
            Log::debug($e->getMessage());
        }
    }


    public function getChattersKernel($schedule)
    {
        Log::debug("*****************getChatters*****************************");
        $data = [];

        $users = [];
        $users['status'] = 'error';
        $users['message'] = 'error';
        $supportStreams = [];

        $user_streaming = $schedule->user;
        Log::debug('*********** user_streaming*************');
        Log::debug(json_encode($user_streaming));
        if ($user_streaming) {

            $this->getRefreshToken($user_streaming);


            $users_chatters = $this->getUserChatters($user_streaming);
            Log::debug('*********** users_chatters*************');
            Log::debug(json_encode($users_chatters));
            if (count($users_chatters) > 0) {
                $users['chaters'] = $users_chatters;
                $users['status'] = 'success';
                $users['message'] = 'success';
                foreach ($users_chatters as $key => $item) {
                    $user_chat  = $this->userService->getByIdandTwichId($item['user_id']);

                    if (!empty($user_chat) && $user_chat->id != $user_streaming->id) {

                        Log::debug('*********** user_chat*************');
                        Log::debug(json_encode($user_chat));


                        $supportStreams = $user_chat->streamSupport;
                        Log::debug('*********** supportStreams*************');
                        Log::debug(json_encode($supportStreams));
                        $exist_supported = false;
                        if (count($supportStreams) > 0) {
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
                            $streamSupport['user_id'] = $user_chat->id;
                            $streamSupport['supported'] = json_encode($support);
                            $created = $this->streamSupportService->create($streamSupport);
                        }


                        $current = Carbon::now();
                        $minute = $current->format('i');

                        if ($minute >= 56 && $minute <= 59 ) {
                            $score = $user_chat->score;
                            Log::debug('score---------------------');
                            Log::debug($score);
                            if (isset($score) && !empty($score)) {

                                // $last = new Carbon($score->updated_at);
                                $user_support['id'] = $user_streaming->id;
                                $user_support['name'] = $user_streaming->channel;
                                //minuto minute == 10

                                    if ($score->points_day < 10) {
                                        // $score->points_day = 0;
                                        $score->points_day =  $score->points_day + 1;
                                    }
                                    //no se suman de una
                                    // if ($score->points_week < 60) {
                                    //     $score->points_week = $score->points_week + 1;
                                    // }

                                    $score->neo_coins = $score->neo_coins + 1;
                                    $score->streamer_supported = json_encode($user_support);
                                    $score->save();
                                    ModelsLog::create([
                                        'action' => 'Se actualiza suma puntos',
                                        'user_id' => $score->user_id,
                                        'date_action' => $score->updated_at,
                                        'message' => 'Usuario:' . $score->user_id . ' Channel: ' .$user_chat->channel
                                    ]);

                            } else {
                                Log::debug('new score---------------------');

                                $score_new = [];
                                $score_new['user_id'] = $user_chat->id;
                                $score_new['points_day'] = 1;
                                $score_new['points_week'] = 1;
                                $score_new['neo_coins'] = 1;
                                $user_support['id'] = $user_streaming->id;
                                $user_support['name'] = $user_streaming->channel;
                                $score_new['streamer_supported'] = json_encode($user_support);
                                // $score['count_users'] = count($users);

                                $created = $this->scoreService->create($score_new);
                                Log::debug($created);
                                ModelsLog::create([
                                    'action' => 'Se crea suma de puntos',
                                    'user_id' => $created->user_id,
                                    'date_action' => $created->updated_at,
                                    'message' => 'Usuario:' . $user_chat->id . ' Channel: ' .$user_chat->channel
                                ]);
                                // dump($score);
                            }
                        }
                    }else{
                        Log::debug("Usuario no existe en la comunidad ***********");
                        Log::debug(json_encode($item['user_login']));
                    }
                }

                Log::debug("users***********");
                Log::debug(json_encode($users));
                $users['status'] = 'ok';

                return $users;
            }
        }
        return $users;
    }
}
