<?php
namespace App\Services;

use App\Models\Range;
use App\Models\Role;
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

final class RangeService
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
        $this->model = Range::class;
    }

    public function all(){
        $this->setModel();
        
        $roles = $this->model::all();
        if(count($roles) > 0){
            return $roles;
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

    public function userExistsActive($email, $twich_id = null)
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
            return $user;
        } else {
            return false;
        }
    }

    public function userLogin($email, $password)
    {
     
        $this->setModel();
        if (isset($email) && isset($password)) {
           
            $user = $this->model
                ::where('email', $email)
                ->where('channel',$password)
                ->first();
                session(['user-log' => $user]);
                return  $user;
        } else {
            return false;
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
            $user = new User();
            if (isset($userArray['id'])) {
                $user->twich_id = $userArray['id'];
            } else {
                $user->twich_id = Str::random(9);
            }
            $user->name = isset($userArray['name']) ? $userArray['name'] : $userArray['display_name'];
            $user->email = $userArray['email'] ?? $userArray['email'];
            $user->range_id = 1;
            $user->role_id = 2;
            $user->channel = $userArray['display_name'];
            $user->password = $userArray['display_name']; //TODO
            $user->status = $userArray['status'] ?? 0;
            $user->country_id = $userArray['country_id'] ?? 1;
            $user->img_profile = $userArray['profile_image_url'] ?? null;
            $user->save();

            $user->token = session('access_token') ?? '';
            $user->update();
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
            // $user->active = $userArray['active'];
            if(array_key_exists('status',$userArray)){
                $user->status = $userArray['status'];
            }else{
                $user->status = 0;
            }
            $user->update();
            return $user->id;
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
            $user->country_id = intval($userArray['country']);
            $user->area = $userArray['area'];
            $user->phone = $userArray['phone'];
            $user->time_zone = $userArray['timezone'];
            $user->update();
            return $user->id;
        } catch (Error $e) {
            return false;
        }
    }
}
