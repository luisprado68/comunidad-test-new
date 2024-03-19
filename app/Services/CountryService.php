<?php
namespace App\Services;

use App\Models\Country;
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

final class CountryService
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
        $this->model = Country::class;
    }

    public function getById($id)
    {
        $this->setModel();
        $country = $this->model::where('id', $id)->first();
        if ($country) {
            return $country;
        } else {
            return null;
        }
    }

    public function getCountries()
    {
        $this->setModel();

        $countries = $this->model::all();

        if (count($countries) > 0) {
            return $countries;
        } else {
            return false;
        }
    }

    /**
     * @param $userArray
     * @return false|mixed
     */
    public function create($countryArray)
    {
        try {
            $country = new User();
            
            $country->name = isset($countryArray['name']) ? $countryArray['name'] : null;
            $country->code = isset($countryArray['code']) ? $countryArray['code'] : null;
            $country->save();
            return $country->id;
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
            if(array_key_exists('checkbox',$userArray)){
                $user->status = intval($userArray['checkbox'][0]);
            }else{
                $user->status = 0;
            }
            $user->update();
            return $user->id;
        } catch (Error $e) {
            return false;
        }
    }
}
