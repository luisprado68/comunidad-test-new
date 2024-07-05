<?php

namespace App\Services;

use App\Enums\RangeType;
use App\Enums\RoleType;
use App\Models\Log as ModelsLog;
use App\Models\Score;
use App\Models\User;
use Broobe\Services\Service;
use Broobe\Services\Traits\{CreateModel, DestroyModel, ReadModel, UpdateModel};
use Carbon\Carbon;
use Error;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

final class ScoreService
{
    public $model;
    public $code_test;
    public $url_twitch;
    public $url;
    public $url_test;
    public $client_id;
    public $force_verify;
    public $complete_url;
    public $test_url;
    public $user;
    /**
     * Set model class name.
     *
     * @return void
     */
    protected function setModel(): void
    {
        $this->model = Score::class;
    }

    public function getById($id)
    {
        $this->setModel();
        $user = $this->model::where('id', $id)->first();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    public function getByUserId($user_id)
    {
        $this->setModel();
        $user = $this->model::where('user_id', $user_id)->first();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    public function getByUsersId($user_id)
    {
        $this->setModel();
        $user = $this->model::whereJsonContains('streamer_supported->id',$user_id)->get();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    public function getUsersModel()
    {
        $this->setModel();

        $users = $this->model::all();

        if (count($users) > 0) {
            return $users;
        } else {
            return false;
        }
    }
    /**
     * @param $userArray
     * @return false|mixed
     */
    public function create($userArray)
    {
        try {
            $score = new Score();

            $score->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : null;
            $score->points_day = isset($userArray['points_day']) ? $userArray['points_day'] : null;
            $score->points_week =  isset($userArray['points_week']) ? $userArray['points_week'] : null;
            $score->neo_coins = isset($userArray['neo_coins']) ? $userArray['neo_coins'] : null;
            $score->streamer_supported = isset($userArray['streamer_supported']) ? $userArray['streamer_supported'] : null;
            $score->save();

            return $score;
        } catch (Error $e) {
            return false;
        }
    }

    /**
     * @param array $user
     * @return User $user
     */
    public function update($userArray)
    {
        // dd($userArray['checkbox']);
        try {

            $score = Score::where('user_id',$userArray['user_id'])->first();
            if(!empty($score)){
                $score->points_day = isset($userArray['points_day']) ? $userArray['points_day'] : null;
                $score->points_week = isset($userArray['points_week']) ? $userArray['points_week'] : null;
                if(isset($userArray['neo_coins'])){
                    $score->neo_coins =  $userArray['neo_coins'];
                }
                $score->save();
                return $score->id;
            }else{
                return false;
            }


        } catch (Error $e) {
            return false;
        }
    }

    public function evaluatePoint($user)
    {
                if ($user->score) {
                    $score = $user->score;
                    if(isset($score)){
                        if ($user->score->points_week >= 60) {
                                $range_current = $user->range_id;
                                $range_id = $user->range_id;
                                if ($user->hasRole('streamer') && $user->range_id >= RangeType::bronce &&  $user->range_id <= RangeType::hierro) {

                                    $range_id = $range_current + 1;

                                    ModelsLog::create([
                                        'action' => '60 puntos',
                                        'user_id' => $user->id,
                                        'date_action' => $user->updated_at,
                                        'message' => 'Usuario: '.$user->id . ' Channel: '.$user->channel.' Subio de rango puntaje semanal: '.$user->score->points_week,
                                    ]);

                                }elseif($user->hasRole('streamer') && $user->range_id <= RangeType::oro && $user->points_support == 25){

                                    $range_id = $range_current + 1;
                                }
                                $user->range_id = $range_id;
                                $user->save();
                        }
                        elseif ($user->range_id >= RangeType::bronce && $user->range_id <= RangeType::platino && $user->hasRole('streamer')) {
                            //comento que bajen de rango
                                $range_before =  $user->range_id;
                                $range_id = $user->range_id;
                                $fecha2 =now();
                                if ($user->range_id == RangeType::bronce && $user->created_at->diffInDays($fecha2) >= 7 && $user->score->points_week < 25) {
                                    $range_id = RangeType::hierro;
                                }elseif($user->range_id == RangeType::plata  && $user->score->points_week < 45){
                                    $range_id = $range_before - 1;
                                }elseif($user->range_id == RangeType::oro  && $user->score->points_week < 50){
                                    $range_id = $range_before - 1;
                                }elseif($user->range_id == RangeType::platino  && $user->score->points_week < 55){
                                    $range_id = $range_before - 1;
                                }
                                $user->range_id = $range_id;
                                $user->save();
                                ModelsLog::create([
                                    'action' => 'Bajo de rango ',
                                    'user_id' => $user->id,
                                    'date_action' => $user->updated_at,
                                    'message' => 'Usuario: '.$user->id . ' Channel: '.$user->channel.' bajo de rango puntaje semanal: '.$user->score->points_week,
                                ]);
                        }
                    }
                }
    }

    public function getUsersSixty(){
        $users_upload_range = User::select('users.name as name', 'users.channel as channel', 'ranges.id as range_id', 'scores.points_day as points_day', 'scores.points_week as points_week', 'scores.updated_at as updated_at')
        ->join('scores', 'users.id', '=', 'scores.user_id')
        ->join('ranges', 'users.range_id', '=', 'ranges.id')
        ->orderBy('scores.points_week', 'desc')
        // ->where('scores.points_week',60)
        ->get();
        if(count($users_upload_range)){
            return $users_upload_range;
        }else{
            return false;
        }
    }
}
