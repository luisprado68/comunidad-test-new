<?php

namespace App\Console;

use App\Models\Log as ModelsLog;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    private $twichService;
    private $scheduleService;
    private $userService;
    private $scoreService;
    private $schedulerService;
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
          
            $this->twichService = new TwichService();
            $this->scheduleService = new ScheduleService();
            $this->scoreService = new ScoreService();
            $this->userService = new UserService();

            $now =  Carbon::now();
            $minute = $now->format('i');

            if ($minute == 10  || $minute == 58) {
                
                Log::debug('-------------------------------------------------minute: ' . $minute);
                Log::debug('---------------[START]  Chatters ------------');
               
                $currentStreams = $this->scheduleService->getCurrentStreamKernel();
                Log::debug('**** currentStreams ******** ');
                Log::debug(json_encode($currentStreams));
                if (count($currentStreams) > 0) {
                    foreach ($currentStreams as $key => $schedule_streaming) {

                        $chatters_schedule =  $this->twichService->getChattersKernel($schedule_streaming);
                       
                    }
                }

                Log::debug('---------------[FINISH] END Chatters------------');
            } else {
                Log::debug('---------------No esta habilitado------------');
            }
        })->everyMinute();


        $schedule->call(function () {

                Log::debug('---------------[START]  Evaluete Points and Ranges & Start Reset Points ------------');
                $this->twichService = new TwichService();
                $this->scheduleService = new ScheduleService();
                $this->scoreService = new ScoreService();
                $this->userService = new UserService();
    
                $users = $this->userService->getUsersModel();
                // Log::debug('-------------------------------------------------users: '. json_encode($users));
                if(count($users) > 0){
                    foreach ($users as $key => $user) {
                        $this->scoreService->evaluatePoint($user);
                        //reseteo de puntos TODO bulk update
                        $user_array['user_id'] = $user->id;
                        $user_array['points_day'] = 0;
                        $user_array['points_week'] = 0;
                        $result = $this->scoreService->update($user_array);
                        
                    }
                    ModelsLog::create([
                        'action' => 'Validacion de puntos',
                        'message' => 'Se reseta los puntos'
                    ]);
                }
            
            Log::debug('---------------[END]  Evaluete Points and Ranges & Start Reset Points ------------');
        })->weeklyOn(7, '11:57');



        $schedule->call(function () {
            Log::debug('---------------[START]  Reset Calendar --------');
            $this->userService = new UserService();
            $this->schedulerService = new ScheduleService();
              
            //corre a las 3 amm arg 00 mex
                ModelsLog::create([
                    'action' => 'Reset Calendar',
                    'message' => 'Se reseta los calendarios'
                ]);
                
                $allUsers = $this->userService->all();
                foreach ($allUsers as $key => $user) {

                    $schedulers_by_user = $this->schedulerService->getByUserId($user->id);
                    if(isset($schedulers_by_user)){
                        if(count($schedulers_by_user) > 0){
                            foreach ($schedulers_by_user as $key => $scheduler_by_user) {
                                $date = new Carbon($scheduler_by_user->start);
                                //se elimina todos
                                   $this->schedulerService->delete($scheduler_by_user->id);
                            }
                        }
                    }
                }
            Log::debug('---------------[FINISH] END Reset Calendar---------------');
        })->weeklyOn(7, '10:00');


        $schedule->call(function () {
            Log::debug('---------------[START]  Refresh Tokens--------');
            $this->userService = new UserService();
            $this->twichService = new TwichService();
            
            
                $allUsers = $this->userService->all();
                foreach ($allUsers as $key => $user) {

                    $this->twichService->getRefreshToken($user);
                    if(isset($user->time_zone) && $user->time_zone != ""){
                        $now =  Carbon::now($user->time_zone);
                        $hour = $now->format('H');
                        if($hour == '00'){
                            
                            $score = $user->score;
                            if(isset($score)){
                                $score->points_day = 0;
                                $user->save();
                            }

                            // ModelsLog::create([
                            //     'action' => 'Reset de puntaje diario',
                            //     'message' => 'Se reseta los puntos a 0'
                            // ]);
                        }
                    }
                   

                }

            Log::debug('---------------[FINISH] END Update Refresh Tokens---------------');
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
