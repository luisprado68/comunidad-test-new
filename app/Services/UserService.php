<?php
namespace App\Services;

use App\Enums\StreamType;
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

final class UserService
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
        $this->model = User::class;
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

    public function getByChannel($channel)
    {
        $this->setModel();
        $user = $this->model::where('channel', $channel)->first();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    public function getByIdandTwichId($twich_id)
    {
        $this->setModel();
        $user = $this->model::where('twich_id', $twich_id)->first();
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * @param $accountId
     * @return mixed
     */

    public function userExists($email, $twich_id = null)
    {
        $this->setModel();
        if (isset($twich_id)) {
            $user = $this->model
                // ::where('email', $email)
                ::where('twich_id', $twich_id)
                ->first();
        } else {
            $user = $this->model::where('email', $email)->first();
        }
        if ($user) {
            $user->token = session('access_token') ?? '';
            $user->refresh_token = session('refresh_token') ?? '';
            $user->update();
            return $user;
        } else {
            return false;
        }
    }

    public function userExistsTrovo($email, $trovo_id = null)
    {
        $user = null;
        $this->setModel();
        if (isset($email)) {
            $user = $this->model::where('email', $email)->first();
        }
        if(empty($user) && isset($trovo_id)){
            $user = $this->model
                ::where('trovo_id', $trovo_id)
                ->first();
        }
        if (isset($user)) {
            $user->token = session('access_token') ?? '';
            $user->refresh_token = session('refresh_token') ?? '';
            $user->update();
            return $user;
        } else {
            return false;
        }
    }

    public function userExistsActive($email, $stream_id = null,$streamType = 1)
    {
        $this->setModel();
        if (isset($stream_id)) {
            if($streamType == StreamType::trovo){
                $user = $this->model::where('trovo_id', $stream_id)->first();
            }else{
                $user = $this->model::where('twich_id', $stream_id)->first();
            }
        }
        if(empty($user)){
            $user = $this->model::where('email', $email)->first();
        }
        if ($user) {
            return $user;
        } else {
            return false;
        }
    }

    public function userLogin($email, $password)
    {
        $result['user'] = false;
        $result['message'] = '';

        $this->setModel();
        if (isset($email) && isset($password)) {

            $user = $this->model
            ::where('email', $email)
            ->first();

            if (Hash::check($password, $user->password)) {
                session(['user-log' => $user]);
                Log::debug('user---');
                Log::debug(json_encode($user));
                return  $user;
            }
        } else {
            return false;
        }


    }

    public function userLoginTwich($email, $password)
    {
     $result['user'] = false;
     $result['message'] = '';
        $this->setModel();
        if (isset($email) && isset($password)) {

            $user = $this->model
                ::where('email', $email)
                ->where('channel',$password)
                ->first();
                Log::debug('user --' . json_encode($user));
                session(['user-log' => $user]);
                if(isset($user)){
                    $result['user'] = $user;
                    return  $result;
                    // if($user->role_id == 1){
                    //     $result['user'] = $user;
                    //     return  $result;
                    // }else{
                    //     $result['message'] = 'No tiene permiso para acceder';
                    //     return $result;
                    // }
                }


        } else {
            return $result;
        }


    }

    public function getUsers()
    {
        $this->setModel();

        $users = $this->model::all()->toArray();

        if (count($users) > 0) {
            return $users;
        } else {
            return false;
        }
    }

    public function getUsersModel()
    {
        $this->setModel();

        $users = $this->model::where('deleted',false)->get();

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
    public function create($userArray,$streamType = 1,$teamId = null)
    {
        try {
            $user = new User();
            if (isset($userArray['id'])) {
                if(StreamType::trovo == $streamType){
                    $user->trovo_id = $userArray['id'];
                }else{
                    $user->twich_id = $userArray['id'];
                }
            }
            $user->name = isset($userArray['name']) ? $userArray['name'] : $userArray['display_name'];
            if(array_key_exists('email',$userArray)){
                $user->email = $userArray['email'] ?? $userArray['email'];
            }else{
                $user->email = $userArray['display_name'].'@gmail.com';
            }
            if(isset($teamId)){
                $user->current_team_id = $teamId;
            }
            //TODO cambiar rango a hierron pero falta imagen
            $user->range_id = 1;
            $user->description = $userArray['description'] ?? null;
            $user->channel = $userArray['display_name'];
            $user->password = $userArray['display_name']; //TODO
            $user->status = $userArray['status'] ?? 0;
            $user->country_id = $userArray['country_id'] ?? 1;
            $user->img_profile = $userArray['profile_image_url'] ?? null;
            $user->deleted = 0;
//            $user->current_team_id = null;
            $user->save();
            $user->token = session('access_token') ?? '';
            $user->update();

            $user->assignRole('streamer');
            return $user;
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
            $user = User::find($userArray['id']);
            $user->name = $userArray['name'];
            $user->email = $userArray['email'];

            $user->range_id = intval($userArray['range']);
            // $user->role_id = intval($userArray['role']);
            $user->current_team_id = intval($userArray['team']);
            // $user->active = $userArray['active'];
            if(array_key_exists('status',$userArray)){
                $user->status = $userArray['status'];
            }else{
                $user->status = 0;
            }
            $user->update();
            Log::debug('rol a actualizar --' .json_encode($userArray['role']));
            $user->syncRoles([$userArray['role']]);
            // $user->assignRole($userArray['role']);
            return $user;
        } catch (Error $e) {
            Log::error($e->getMessage());
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

    public function TableQuery($filter = null,$team = null)
    {
        $this->setModel();
        $query = $this->model::query()->select('*')
            // ->with('account')
            ->where('deleted', 0);

            if(isset($team)){
                $query->where('current_team_id',$team->id);
            }


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

        $query = $this->model::query()->select('*')->where('deleted',true);

//        ->limit(10);

        $list = $query->get();
        // Log::debug(json_encode($list));
        return $query;
    }

    public function getUsersNewsQuery()
    {
        $this->setModel();

        $query = $this->model::query()->select('*')->where('current_team_id',null);
        $list = $query->get();
        // Log::debug(json_encode($list));
        return $query;
    }
}
