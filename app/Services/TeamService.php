<?php
namespace App\Services;

use App\Models\Team;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

final class TeamService
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
        $this->model = Team::class;
    }

    public function all(){
        $this->setModel();

        $users = $this->model::all();
        if(count($users) > 0){
            return $users;
        }else {
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

    public function getTeams()
    {
        $this->setModel();

        $users = $this->model::all();

        if (count($users) > 0) {
            return $users;
        } else {
            return false;
        }
    }

    public function getFirstTeamAviable()
    {
        $this->setModel();

        $teamWithUsers = $this->model::whereHas('users', function ($query) {
            $query->select('current_team_id', DB::raw('count(*) as user_count'))
                    ->where('deleted',0)
                    ->where('status',1)
                  ->groupBy('current_team_id')
                 ->having('user_count', '!=', 100);
                // ->whereBetween('user_count', [1, 5]);
        })
        ->with('users')
        ->first();
        // dump($teamWithUsers);

        if ($teamWithUsers) {
            return $teamWithUsers;
        } else {
            return false;
        }
    }
    public function TableQueryByPlatform($platform_id)
    {
        $this->setModel();
        $query = $this->model::query()
            ->join('users', 'teams.id', '=', 'users.current_team_id')
            ->where('users.platform_id', $platform_id)
            ->groupBy('teams.id');
        return $query;
    }

    public function getByPlatform($platform_id)
    {
        $this->setModel();
        $teams = $this->model::query()->select('teams.id','teams.name')
            ->join('users', 'teams.id', '=', 'users.current_team_id')
            ->where('users.platform_id', $platform_id)
            ->groupBy('teams.id')->get();
//        dd($teams);
        $teams_with_no_users = $this->model::doesntHave('users')->get();
        $teams = $teams->merge($teams_with_no_users);
//        dd($teams_with_no_users);
        return $teams;
    }

    /**
     * @param $userArray
     * @return false|mixed
     */
    public function create($teamArray)
    {

        try {
            $team = new Team();
            $team->user_id = $teamArray['user_id'];
            $team->name = $teamArray['name'] ?? null;
            $team->personal_team = 1;
            $team->save();
            return $team;
        } catch (Error $e) {
            return false;
        }
    }

    /**
     * @param array $user
     * @return User $user
     */
    public function update($teamArray)
    {
        // dd($userArray['checkbox']);
        try {
            Log::debug('id  -- ' . json_encode($teamArray['id']));
            $team = Team::find($teamArray['id']);
            Log::debug('team -- ' . json_encode($team));
            $team->name = $teamArray['name'] ?? null;
            $team->personal_team = 1;
            $team->save();
            // $user->active = $userArray['active'];
            return $team;
        } catch (Error $e) {
            return false;
        }
    }

    public function updateUser($userArray)
    {
        // dd($userArray['checkbox']);
        try {
            $user = User::find($userArray['id']);
            $user->name = $userArray['name'];
            $user->channel = $userArray['channel'];
            $user->description = $userArray['description'];
            $user->country_id = intval($userArray['country']);
            $user->area = $userArray['area'];
            $user->phone = $userArray['phone'];
            $user->time_zone = $userArray['timezone'];
            $user->instagram = $userArray['instagram'];
            $user->facebook = $userArray['facebook'];
            $user->youtube = $userArray['youtube'];
            $user->save();
            return $user->id;
        } catch (Error $e) {
            return false;
        }
    }

    public function TableQuery($filter = null)
    {
        $this->setModel();
        $query = $this->model::query()->select('*')
            // ->with('account')
            ->where('deleted', 0);
        return $query;
    }

    public function getUsersDeleted()
    {
        $this->setModel();

        $users = $this->model::where('deleted',true)->get();

        if (count($users) > 0) {
            return $users;
        } else {
            return [];
        }
    }

    public function getUsersTopQuery()
    {
        $this->setModel();

        $query = $this->model::query()->select('*','scores.points_day as points_day',
        'scores.points_week as points_week','scores.neo_coins as neo_coins')->join('scores', 'users.id', '=', 'scores.user_id')
        ->orderBy('scores.points_day', 'desc')
        ->orderBy('scores.points_week', 'desc')
        ->orderBy('scores.neo_coins', 'desc')
        ->where('users.deleted',0)
        ->limit(10);
        $list = $query->get();
        // Log::debug(json_encode($list));
        return $query;
    }

    public function getUsersTop()
    {
        $this->setModel();

        $users = $this->model::select('*','scores.points_day as points_day',
        'scores.points_week as points_week','scores.neo_coins as neo_coins')->join('scores', 'users.id', '=', 'scores.user_id')
        ->orderBy('scores.points_day', 'desc')
        ->orderBy('scores.points_week', 'desc')
        ->orderBy('scores.neo_coins', 'desc')
        ->where('users.deleted',0)
        ->limit(10)
        ->get();

        if (count($users) > 0) {
            return $users;
        } else {
            return false;
        }
    }

    public function getUsersSchedulers()
    {
        $this->setModel();

        $users = $this->model::join('schedule', 'users.id', '=', 'schedule.user_id')
        ->select('users.id as id','users.name as name', DB::raw('COUNT(schedule.user_id) as top','users.channel as channel','users.status as status'))
        ->where('users.deleted',false)
        ->groupBy('schedule.user_id')
        ->orderByDesc('top')
        ->limit(10)
        ->get();
        if (count($users) > 0) {
            return $users;
        } else {
            return false;
        }
    }

    public function getUsersSchedulersQuery()
    {
        $this->setModel();

        $query = $this->model::query()->select('*')->where('deleted',true)

        ->limit(10);

        $list = $query->get();
        // Log::debug(json_encode($list));
        return $query;
    }
}
