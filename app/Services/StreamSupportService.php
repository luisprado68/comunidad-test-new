<?php
namespace App\Services;

use App\Models\Score;
use App\Models\StreamSupport;
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

final class StreamSupportService
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
        $this->model = StreamSupport::class;
    }

    public function getSupportsStreams($teamId)
    {
        $this->setModel();
        $supportStreams = $this->model::select('user_id')->groupBy('user_id')
        ->join('users', 'stream_support.user_id', '=', 'users.id')->where('users.current_team_id',$teamId)
        ->where('users.deleted',false)
        ->get();

        if (count($supportStreams)) {
            return $supportStreams;
        } else {
            return null;
        }
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

    public function getStreamSupportsByUserId($id)
    {
        $this->setModel();
        $user = $this->model::where('user_id', $id)->get();
        // $user = $this->model::select('user_id','supported')->where('user_id', $id)->groupBy('supported')->get();
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
        $userSupport = $this->model::whereJsonContains('supported->id',$user_id)->get();
        if (count($userSupport) > 0) {
            return $userSupport;
        } else {
            return [];
        }
    }


    public function getStreamSupports()
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
            $score = new StreamSupport();
            $score->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : null;
            $score->supported = isset($userArray['supported']) ? $userArray['supported'] : null;
            $score->save();
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
            $score->supported = isset($userArray['supported']) ? $userArray['supported'] : null;
            $score->update();
            return $score->id;
        } catch (Error $e) {
            return false;
        }
    }

    public function delete($id)
    {
        $this->setModel();
        $schedule = $this->model::where('id', $id)->first();
        if (isset($schedule)) {
            $schedule->delete();
            return true;
        } else {
            return null;
        }
    }

}
