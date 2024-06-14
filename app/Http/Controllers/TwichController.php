<?php

namespace App\Http\Controllers;

use App\Models\Log as ModelsLog;
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


    public function setPoints(Request $request)
    {
        $user = session('user');
        $user_model = $this->userService->userExistsActive($user['display_name'] . '@gmail.com', $user['id']);
        // Obtener los datos enviados mediante la solicitud AJAX
        $datos = $request->all();
        Log::debug('data --------------------------------------' . json_encode($datos));
        if(isset($datos)){
            if(isset($datos['minutos']) && isset($datos['stream_id'])){
                $minutos = intval($datos['minutos']);
                $twich_id = $datos['stream_id'];
                $user_streaming = $this->userService->getByIdandTwichId($twich_id);

                if (!empty($user_model) && $user_model->id != $user_streaming->id) {

                    // if($minutos <= 10  || $minutos >= 45){

                        ModelsLog::create([
                            'action' => 'Tiempo de minutos',
                            'message' => 'Usuario: '.$user_model->id . ' Channel: '.$user_model->channel .'minutos: '.$minutos.' viendo a Channel: '.$user_streaming->channel. ' ('.$user_streaming->id.')',
                        ]);
                        $supportStreams = $user_model->streamSupport;

                        $exist_supported = false;
                        if (count($supportStreams) > 0) {
                            foreach ($supportStreams as $key => $supportStream) {

                                $support_created = json_decode($supportStream->supported);

                                if ($support_created->id == $user_streaming->id) {
                                    Log::debug('*********** update supportStreams*************');
                                    $exist_supported = true;
                                    $supportStream->supported = json_encode($support_created);
                                    $supportStream->minutes = $minutos;
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
                            $streamSupport['minutes'] = $minutos;
                            $created = $this->streamSupportService->create($streamSupport);
                        }
                    // }


                    if($minutos >= 50){

                        $score = $user_model->score;
                                Log::debug('score---------------------');
                                Log::debug($score);
                                if (isset($score) && !empty($score)) {

                                    // $last = new Carbon($score->updated_at);
                                    $user_support['id'] = $user_streaming->id;
                                    $user_support['name'] = $user_streaming->channel;
                                    //minuto minute == 10

                                        if ($score->points_day == 10) {
                                            $score->points_day = 0;
                                        } else {
                                            $score->points_day =  $score->points_day + 1;
                                        }

                                        if ($score->points_week == 60) {
                                            // $score->points_week = 0;
                                            ModelsLog::create([
                                                'action' => 'Tiempo de minutos',
                                                'message' => 'Usuario: '.$user_model->id . ' Channel: '.$user_model->channel .'minutos: '.$minutos.' viendo a Channel: '.$user_streaming->channel. ' ('.$user_streaming->id.')',
                                            ]);
                                        } else {
                                            $score->points_week = $score->points_week + 1;
                                        }

                                        $score->neo_coins = $score->neo_coins + 1;
                                        $score->streamer_supported = json_encode($user_support);
                                        $score->update();

                                } else {
                                    Log::debug('new score---------------------');

                                    $score_new = [];
                                    $score_new['user_id'] = $user_model->id;
                                    $score_new['points_day'] = 1;
                                    $score_new['points_week'] = 1;
                                    $score_new['neo_coins'] = 1;
                                    $user_support['id'] = $user_streaming->id;
                                    $user_support['name'] = $user_streaming->channel;
                                    $score_new['streamer_supported'] = json_encode($user_support);

                                    $created = $this->scoreService->create($score_new);
                                    Log::debug($created);
                                }
                    }

                }








            }
        }
        // Procesar los datos (guardar en la base de datos, realizar alguna operación, etc.)

        // Devolver una respuesta (opcional)
        return response()->json(['mensaje' => 'Datos guardados correctamente']);
    }




}
