<?php
namespace App\Services;

use App\Enums\PlatformType;
use App\Models\Offer;
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

final class OfferService
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
        $this->model = Offer::class;
    }


    public function all(){
        $this->setModel();

        $offers = $this->model::all();
        if(count($offers) > 0){
            return $offers;
        }else {
            return collect();
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








    /**
     * @param $userArray
     * @return false|mixed
     */
    public function create($offerArray)
    {
        try {
            $offer = new Offer();
            $offer->user_id = $offerArray['user_id'];
            $offer->link = $offerArray['link'];
            $offer->viewers = $offerArray['viewers'];
            $offer->offer_coins = $offerArray['offer_coins'];
            $offer->price = $offerArray['price'];

            $offer->save();
            return $offer;
        } catch (\Exception $e) {
            Log::error('Error offer create' . $e->getMessage());
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

    public function TableQuery($filter = null)
    {
        $user = session('user');
        $userService = new UserService();
        $userFound = $userService->getByIdandTwichId($user['id'],$user['platform_id']);
        $this->setModel();
        return $this->model::query()->select('*')
            // ->with('account')
            ->where('user_id', $userFound->id);
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

    public function getQuery()
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
        $list = $query->get();
        return $query;
    }

    public function getUsersNewsQuery()
    {
        $this->setModel();

        $query = $this->model::query()->select('*')->where('current_team_id',null);
        $list = $query->get();
        return $query;
    }
}
