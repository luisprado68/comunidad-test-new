<?php

namespace App\Livewire;

use App\Models\Log as ModelsLog;
use App\Models\User;
use App\Services\ScheduleService;
use App\Services\StreamSupportService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Home extends Component
{
    public $search = '';
    public $users;
    public $twoElements1 = [];
    public $twoElements2 = [];
    public $twoElements3 = [];
    public $twoElements4 = [];
    public $top;
    public $top_two;
    public $top_three;
    public $top_four;
    public $list = [];

    public $profile_image_url;
    public $response;
    private $twichService;
    private $userService;
    private $scheduleService;
    private $streamSupportService;

    public function boot(TwichService $twichService, UserService $userService, ScheduleService $scheduleService)
    {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
    }


    public function render()
    {
        Log::debug('ssssssssss : ' . json_encode($this->search));
        // if(isset($this->search) && $this->search != ''){
        //     $this->list = User::where('name', 'like', '%'.$this->search.'%')->get();
        // }

        if (session()->exists('user')) {
            $user = session('user');

            $userModel = $this->userService->userExistsActive($user['display_name'] . '@gmail.com', $user['id']);


            if ($userModel->status) {

                session(['status' => $userModel->status]);
            } else {
                session(['status' => 0]);
            }


            $this->users = $this->userService->getUsersTop();
            if ($this->users) {
                $this->users = $this->users->toArray();

                $this->twoElements1 = array_slice($this->users, 0, 3);
                // Get two elements starting from index 3
                $this->twoElements2 = array_slice($this->users, 3, 3);
                // Get two elements starting from index 3
                $this->twoElements3 = array_slice($this->users, 6, 3);
                // Get two elements starting from index 3
                $this->twoElements4 = array_slice($this->users, 9, 1);
            }

            $this->top = 1;
            $this->top_two = 4;
            $this->top_three = 7;
            $this->top_four = 10;


            return view('livewire.home');
        }


    }
}
