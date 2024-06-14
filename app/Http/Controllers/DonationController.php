<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ScheduleService;
use App\Services\TwichService;
use App\Services\UserService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    private $twichService;
    private $userService;
    private $scheduleService;
    public function __construct(TwichService $twichService, UserService $userService,ScheduleService $scheduleService)
    {
        $this->twichService = $twichService;
        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
    }
    public function index(){

//        $newss = User::all();
//        // Assign the 'streamer' role to each user
//        foreach ($newss as $user) {
//            $user->assignRole('streamer');
//        }

        $times = [];
        $active = false;
        if(session()->exists('user')){
            $user = session('user');

            if(array_key_exists('platform_id',$user)){
                if(array_key_exists('email',$user)){
                    $userModel = $this->userService->userExistsActive($user['email'],$user['id'],$user['platform_id']);
                }else{
                    $userModel = $this->userService->userExistsActive($user['display_name'].'@gmail.com',$user['id'],$user['platform_id']);
                }
            }else{
                $userModel = $this->userService->userExistsActive($user['email'],$user['id']);
            }

            $currentStreams = $this->scheduleService->getStreamByUser($userModel);

            if(count($currentStreams) > 0){
                $times = $this->scheduleService->getTimes($currentStreams,$userModel);
            }
            // @dd($active);
            if($userModel->status){

                session(['status' => $userModel->status]);
            }
            else{
                session(['status' => 0]);
            }
            return view('donation',['times' => json_encode($times)]);
        }
        // else{
        //     return redirect('/');
        // }

    }
}
