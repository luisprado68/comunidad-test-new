<?php

namespace App\Console;

use App\Enums\PlatformType;
use App\Models\Log as ModelsLog;
use App\Services\ScheduleService;
use App\Services\ScoreService;
use App\Services\StreamSupportService;
use App\Services\TrovoService;
use App\Services\TwichService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Kernel extends ConsoleKernel
{
    private $twichService;
    private $scheduleService;
    private $userService;
    private $scoreService;
    private $schedulerService;
    private $streamSupportService;
    private $trovoService;
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        //comentamos el kernel de twich
//        $schedule->call(function () {
//
//            $this->twichService = new TwichService();
//            $this->scheduleService = new ScheduleService();
//            $this->scoreService = new ScoreService();
//            $this->userService = new UserService();
//
//            $now =  Carbon::now();
//            $minute = $now->format('i');
//
//            if ($minute == 15  || $minute == 57) {
//
//                ModelsLog::create([
//                    'action' => 'Validacion de chat en stream',
//                    'message' => 'Se actualiza historial y puntaje'
//                ]);
//
//                Log::debug('---------------[START]  Chatters ------------');
//
//                $currentStreams = $this->scheduleService->getCurrentStreamKernel(PlatformType::twich);
//                Log::debug('**** currentStreams ******** ');
//                Log::debug(json_encode($currentStreams));
//                if (count($currentStreams) > 0) {
//                    foreach ($currentStreams as $key => $schedule_streaming) {
//
//                        $chatters_schedule =  $this->twichService->getChattersKernel($schedule_streaming);
//
//                    }
//                }
//                Log::debug('---------------[FINISH] END Chatters------------');
//            }
//        })->everyMinute();

        $schedule->call(function () {

            $this->trovoService = new TrovoService();
            $this->scheduleService = new ScheduleService();
            $this->scoreService = new ScoreService();
            $this->userService = new UserService();

            $now =  Carbon::now();
            $minute = $now->format('i');

            if ($minute == 10  || $minute == 50) {

                Log::debug('---------------[START]  Trovo Chatters ------------');

                $currentStreams = $this->scheduleService->getCurrentStreamKernel(PlatformType::trovo);
                if (count($currentStreams) > 0) {
                    foreach ($currentStreams as $key => $schedule_streaming) {
                        $chatters_schedule =  $this->trovoService->getChattersKernel($schedule_streaming);
                    }
                }
                Log::debug('---------------[FINISH] END Chatters------------');
            }
        })->everyFiveMinutes();

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
                        if(isset($user->score)){
                            $score = $user->score;
                            $points_week_old = $user->score->points_week;
                            $score->points_week_old = $points_week_old;
                            $score->save();
                        }

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
            $this->streamSupportService = new StreamSupportService();

            //corre a las 3 amm arg 00 mex
                ModelsLog::create([
                    'action' => 'Reset Calendar y Support Streams',
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

                    if(isset($user->streamSupport) && count($user->streamSupport)){
                        foreach ($user->streamSupport as $key => $streamSupport) {
                            $this->streamSupportService->delete($streamSupport->id);
                        }
                    }

                }
            Log::debug('---------------[FINISH] END Reset Calendar---------------');
        })->weeklyOn(0, '10:00');


        $schedule->call(function () {

            $this->userService = new UserService();
            $this->twichService = new TwichService();

            Log::debug('---------------[START]  Update score user --------');
                $allUsers = $this->userService->getUsersModel();
                foreach ($allUsers as $key => $user) {


                    if(isset($user->time_zone) && $user->time_zone != ""){
                        // Log::debug('---------------user timezone ---------------' . $user->channel);
                        $now =  Carbon::now($user->time_zone);
                        $hour = $now->format('H');
                        $score_day = 0;
                        $score_week = 0;
                        // Log::debug('---------------user hour ---------------' . $hour);
                        if($hour == '00'){

                            $score = $user->score;
                            if(isset($score)){
                                //se suma el punto del dia al semanal y despues resetea
                                $score_day = $score->points_day;
                                $score_week = $score->points_week;
                                $score_week = $score_week + $score_day;
                                if($score_week >= 60){
                                    $score->points_week = 60;
                                }else{
                                    $score->points_week = $score_week;
                                }
                                $score->points_day = 0;
                                $score->save();
                                ModelsLog::create([
                                    'action' => 'Reset de puntaje diario',
                                    'user_id' => $user->id,
                                    'date_action' => $score->updated_at,
                                    'message' => 'Se reseta los puntos a 0' . $user->channel
                                ]);

                            }
                            else{
                                ModelsLog::create([
                                    'action' => 'Error Reset de puntaje diario',
                                    'user_id' => $user->id,
                                    'date_action' => now(),
                                    'message' => 'No tiene puntaje creado'
                                ]);
                            }


                        }
                    }


                }
                Log::debug('---------------[FINISH] END  Update score user ---------------');
        })->hourly();


        $schedule->call(function () {
            Log::debug('---------------[START]  Refresh Tokens--------');
            $allUsers = $this->userService->getUsersModel();

            foreach ($allUsers as $key => $user) {
                if($user->platform_id == PlatformType::twich){
                    $this->twichService->getRefreshToken($user);
                }else{
                    $this->trovoService->getRefreshToken($user);
                }
            }

        Log::debug('---------------[FINISH] END Update Refresh Tokens---------------');
        })->everyTwoHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
