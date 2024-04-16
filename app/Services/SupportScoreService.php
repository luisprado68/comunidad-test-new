<?php
namespace App\Services;

use App\Models\Score;
use App\Models\SupportScore;
use App\Models\User;
use Broobe\Services\Service;
use Broobe\Services\Traits\{CreateModel, DestroyModel, ReadModel, UpdateModel};
use Error;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

final class SupportScoreService
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
        $this->model = SupportScore::class;
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

    public function getByUserSupportId($user_id)
    {
        $this->setModel();
        $userSupport = $this->model::whereJsonContains('user->user_id',$user_id)->first();
        if (isset($userSupport)) {
            return $userSupport;
        } else {
            return null;
        }
    }

    public function getByUserScore($user_id)
    {
        $total = 0;
        $this->setModel();
        $userSupport = $this->model::where('user_id',$user_id)->where('point',1)->get();
        if (count($userSupport) > 0) {
            $total = count($userSupport);
            return $total;
        } else {
            return $total;
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
            $score = new SupportScore();
            $score->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : null;
            $score->point = isset($userArray['point']) ? $userArray['point'] :null;
            $score->user = isset($userArray['user']) ? $userArray['user'] : null;
            $score->save();
            Log::debug('SupportScore');
            Log::debug(json_encode($score));
            return $score;
        } catch (Error $e) {
            Log::debug(json_encode($e->getMessage()));
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
            $score = SupportScore::find($userArray['id']);
            $score->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : null;
            $score->point = isset($userArray['point']) ? $userArray['point'] :null;
            $score->user = isset($userArray['user']) ? $userArray['user'] : null;
            $score->update();
            return $score->id;
        } catch (Error $e) {
            return false;
        }
    }

    
}
