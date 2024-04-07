<?php

namespace App\Services;

use App\Models\Schedule;
use App\Models\User;
use Broobe\Services\Service;
use Broobe\Services\Traits\{CreateModel, DestroyModel, ReadModel, UpdateModel};
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\Factory;
use DateTime;
use DateTimeZone;
use Error;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

final class ScheduleService
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
        $this->model = Schedule::class;
    }

    public function getById($id)
    {
        $this->setModel();
        $schedule = $this->model::where('id', $id)->first();
        if ($schedule) {
            return $schedule;
        } else {
            return null;
        }
    }

    public function getByUserId($user_id)
    {
        $this->setModel();
        $schedulers = $this->model::where('user_id', $user_id)->orderBy('start','asc')->get();
        if (count($schedulers) > 0) {
            return $schedulers;
        } else {
            return null;
        }
    }

    public function getSchedulerByUser($user_model)
    {
        $new_schedulers = [];
        $groupedArray = [];
        $items = [];

        $schedulers = $this->getByUserId($user_model->id);
        if(isset($schedulers)){
            if(count($schedulers) > 0){
                // $week = $this->getFormatDays($schedulers);
                foreach ($schedulers as $key => $scheduler) {
                   
                    $time = new Carbon($scheduler->start);
                    // dump($time);
                    $time->tz = $user_model->time_zone;
                    // dump($time);
                    array_push($new_schedulers,['day' => strtolower($time->format('l')),'time' => $time->format('H:00'),'id' => $scheduler->id]);
                   
                    Log::debug('new_schedulers------------ '. json_encode($new_schedulers));
                }
               
                $groupedArray = collect($new_schedulers)->groupBy('day')->toArray();
               
            }
        }
        Log::debug('groupedArray------------ '. json_encode($groupedArray));
        return $groupedArray;
    }

    public function getByUserIdDay($user_id)
    {
        $this->setModel();
        $schedulers = $this->model::select(DB::raw('DATE(start) as day'), DB::raw('COUNT(*) as count'))
        ->groupBy('day')
        ->where('user_id', $user_id)->orderBy('day','asc')
        ->get();
        if (count($schedulers) > 0) {
            return $schedulers;
        } else {
            return null;
        }
    }
    public function getByUserIdAndDate($user,$date){
        $this->setModel();
        
        $schedulers = $this->model::
        whereDate('start',$date)
        ->where('user_id', $user->id)->orderBy('start','asc')
        ->get();
        if (count($schedulers) > 0) {
            return $schedulers;
        } else {
            return null;
        }
    }

    public function getSchedulersToDelete($startDate,$endDate){
        $this->setModel();
        $schedulers = [];
        $schedulers = $this->model::
        whereBetween('created_at', [$startDate, $endDate])->get();
        return $schedulers;
    }

    public function validateNewScheduleByUser($date){
        $this->setModel();
       
        $en = $this->setSunday();
        $start_date = new Carbon($date);
        $test = $start_date->addHours(-1);

        $start_string = $start_date->format('Y-m-d H:59:00');
        
        $end_date = new Carbon($date);
        
        $end_string = $end_date->format('Y-m-d H:01:00');
        // Log::debug('start_string------------' . json_encode($start_string));
        // Log::debug('end_string------------' . json_encode($end_string));
        $day = $this->model::whereBetween('start', [$start_string, $end_string])->get();
       
        Log::debug('day------------' . json_encode($day));
        if (isset($day)) {
            return $day;
        } else {
            return null;
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

    public function getScheduleorThisWeek($user)
    {
        //para testeAR*****
        $this->setModel();
        // $en = CarbonImmutable::now()->locale('en_US');
        $en = $this->setSunday();
        $hour_first = $this->parseHoursToCountry($en->endOfWeek(Carbon::MONDAY),$user->time_zone);
        $start = $en->startOfWeek(Carbon::MONDAY)->addHours($hour_first)->format('Y-m-d H:00:00');
        // dump($start);
        $hour_end = $this->parseHoursToCountry($en->endOfWeek(Carbon::MONDAY),$user->time_zone);
        $end = $en->endOfWeek(Carbon::SATURDAY)->addHours($hour_end)->format('Y-m-d H:00:00');
        // dump($end);
        $week = $this->model::whereBetween('start', [$start, $end])->get();
        // $week = $this->model::all();
        // dump($week);
        if (count($week) > 0) {
            return $week;
        } else {
            return null;
        }
    }
    public function getDatesByDay($user,$day){
        $week = [];
        $week_time_zone = [];
        // dump('day');
        // dump($day);
        $this->setModel();
        $en = $this->setSunday();
        // dump('en');
        // dump($en);
        $hour_first = $this->parseHoursToCountry($en->endOfWeek($day),$user->time_zone);
       
        if($day == 6){
            $day_start = $en->startOfWeek($day)->addHours($hour_first)->format('Y-m-d H:00:00');
            // dump('start----');
            // dump($day_start);
            $day_end = $en->endOfWeek($day)->addHours($hour_first)->format('Y-m-d H:00:00');
            // dump('end----');
            // dump($day_end);
        }else{
            $day_start = $en->startOfWeek($day)->addHours($hour_first)->format('Y-m-d H:00:00');
            // dump('start----');
            // dump($day_start);
            $day_end = $en->endOfWeek($day)->addHours($hour_first)->format('Y-m-d H:00:00');
            // dump('end----');
            // dump($day_end);
        }
        
       
        
       
        
        $week = $this->model::whereBetween('start', [$day_start, $day_end])->orderBy('start')->get();
        // $week = $this->model::whereBetween('start', [$day_start, $day_end])->get();

        foreach ($week as $key => $day) {
            $date = new Carbon($day->start);
                $date->tz = $user->time_zone;
                
                array_push($week_time_zone,['date' => $date->format('d-m-Y H:i:s'),'user' => $day->user->channel]);
        }
        return $week_time_zone;
    }

    public function getSchedulerWeek($user)
    {
    
        $allDays = [];
        $monday = $this->getDatesByDay($user,Carbon::MONDAY);
        // dump($monday);
        $tuesday = $this->getDatesByDay($user,Carbon::TUESDAY);
        $wednesday = $this->getDatesByDay($user,Carbon::WEDNESDAY);
        $thursday = $this->getDatesByDay($user,Carbon::THURSDAY);
        $friday = $this->getDatesByDay($user,Carbon::FRIDAY);
        // dump('saturday');
        $saturday = $this->getDatesByDay($user,Carbon::SATURDAY);
        // dump($saturday);
        $allDays =[
            'Lunes' => $monday,
            'Martes' => $tuesday,
            'Miercoles' => $wednesday,
            'Jueves' => $thursday,
            'Viernes' => $friday,
            'Sabado' => $saturday,
        ];
        // dump($monday);
        if (count($allDays) > 0) {
            return $allDays;
        } else {
            return null;
        }
    }

    public function setSunday(){

        // if(env('APP_ENV') == 'local' || env('APP_ENV') == 'prod' ){
        //     $toDisplay = CarbonImmutable::parse('2023-12-10 00:00:00', 'UTC');
        //     $martinDateFactory = new Factory([
        //         'locale' => 'en_US'
        //     ]);
        //     $toDisplay->addDays(1);
        //     $en = $martinDateFactory->make($toDisplay);
        //     dd($en);
        // }
        // else{
            $now =  CarbonImmutable::now();
            $en = $now->startOfWeek(Carbon::SUNDAY);
            $martinDateFactory = new Factory([
                        'locale' => 'en_US'
                    ]);
            //         // $now->addDays(1);
             $en = $martinDateFactory->make($en);
            // dump($en);
            // $en->addDays(1);
        // }   
        
        return $en;
    }
    public function getScheduleorThisWeekByUser($user)
    {
        $this->setModel();
        // $en = CarbonImmutable::now()->locale('en_US');
        $en = $this->setSunday();
        $hour_first = $this->parseHoursToCountry($en->endOfWeek(Carbon::MONDAY),$user->time_zone);
        // dump($id);
        $start = $en->startOfWeek(Carbon::MONDAY)->addHours($hour_first)->format('Y-m-d H:00:00');
        //  dump('------ week -----------------start' . json_encode($hour_first));
        // dump($start);
       
        $end = $en->endOfWeek(Carbon::SATURDAY)->addHours($hour_first)->format('Y-m-d H:00:00');

        
        // dump($end);
        
        // $end = $en->startOfWeek(Carbon::SATURDAY);
       
        $week = $this->model::whereBetween('start', [$start, $end])->where('user_id',$user->id)->get();
        // dump($week);
        if (count($week) > 0) {
            return $week;
        } else {
            return null;
        }
    }
    public function getScheduleorThisWeekByUserString($user)
    {

        $this->setModel();
        $en = $this->setSunday();
        
        // $en = CarbonImmutable::now()->locale('en_US');
       
        // dump($id);
       
        // dump($start);
        
        $hour_first = $this->parseHoursToCountry($en->endOfWeek(Carbon::MONDAY),$user->time_zone);
        $start = $en->startOfWeek(Carbon::MONDAY)->addHours($hour_first)->format('Y-m-d H:00:00');
        // $end = $en->startOfWeek(Carbon::SATURDAY);
        // dump($start);
        $hour_end = $this->parseHoursToCountry($en->startOfWeek(Carbon::MONDAY),$user->time_zone);
        $end = $en->endOfWeek(Carbon::SATURDAY)->addHours($hour_end);
        $end->format('Y-m-d H:00:00');
        // dump($end);
        $week = $this->model::whereBetween('start', [$start, $end])->where('user_id',$user->id)->get();
        // dump($week);
        if (count($week) > 0) {
            return $week;
        } else {
            return null;
        }
    }

    public function getCurrentStream($user){

        $currentStreams = [];
        $this->setModel();
        $date = Carbon::now();
        
        $date_next = Carbon::now();
        $dates_next = $date_next->format('Y-m-d');
        
        $dates = $date->format('Y-m-d');
        $hour = $date->format('H');
        $minutes = $date->format('i');
        
        if($hour == "00"){
            $backHour = 23;
            $date->addDays(-1);
            $dates = $date->format('Y-m-d');
        }else{
            $backHour = $hour - 1;
        }
       
        if($minutes > 59  || $minutes <= env('WATCH_SUPPORT_MINUTE')){
            $back_minute = 59;
            $minute = env('WATCH_SUPPORT_MINUTE');
        }
        else{
            $back_minute = 59;
            $minute = env('WATCH_SUPPORT_MINUTE');
            $hour = $date->format('H');
            // $backHour = $hour-1;
        } 
    
        $actual = new Carbon($dates.' ' .$backHour.':'.$back_minute.':00');
        $actual_next = new Carbon($dates_next.' ' .$hour.':'.$minute.':00');
        $start_string = $actual->format('Y-m-d H:i:s');
        // dump($start_string);
        $end_string = $actual_next->format('Y-m-d H:i:s');
        // dump($end_string);

        if($minutes <= env('WATCH_SUPPORT_MINUTE')){
            $currentStreams = $this->model::whereBetween('start',[$start_string, $end_string])->where('user_id','!=',$user->id)->distinct()->take(2)->get();
        }
        // dump($currentStreams);
        return $currentStreams;
    }

    public function getCurrentStreamKernel(){

        $currentStreams = [];
        $this->setModel();
        $date_before = Carbon::now();
        $date_next = Carbon::now();

        $dates_next = $date_next->format('Y-m-d');
        $dates_before = $date_before->format('Y-m-d');

        $hour = $date_before->format('H');
        $minutes = $date_before->format('i');
        
        if($hour == "00"){
            $backHour = 23;
            $date_before->addDays(-1);
            $dates_before = $date_before->format('Y-m-d');
        }else{
            $backHour = $hour - 1;
        }
       
        // if($minutes > 59  || $minutes <= env('WATCH_SUPPORT_MINUTE')){
        //     $back_minute = 59;
        //     $minute = env('WATCH_SUPPORT_MINUTE');
        // }
        // else{
            $back_minute = 58;
            $minute = env('WATCH_SUPPORT_MINUTE');
            $hour = $date_before->format('H');
            // $backHour = $hour-1;
        // } 
    
        $actual = new Carbon($dates_before.' ' .$backHour.':'.$back_minute.':00');
        $actual_next = new Carbon($dates_next.' ' .$hour.':'.$minute.':00');
        $start_string = $actual->format('Y-m-d H:i:s');
        // Log::debug('start: ' . $start_string);
        $end_string = $actual_next->format('Y-m-d H:i:s');
        // Log::debug('end: ' . $end_string);

        
        $currentStreams = $this->model::whereBetween('start',[$start_string, $end_string])->distinct()->get();
        
        // dump($currentStreams);
        return $currentStreams;
    }

    public function getStreamByUser($user){
        $currentStreams = [];
        $this->setModel();
        $date = Carbon::now();
        $dateBefore = $date->addDays(-1);
        $dates = $dateBefore->format('Y-m-d');
        $current = Carbon::now();
        $backHour =23;
        $back_minute = 59;
        $actual_before = new Carbon($dates.' ' .$backHour.':'.$back_minute.':00');

        // dump($current->format('Y-m-d'));
        

        $actual_next = new Carbon($current->format('Y-m-d').' ' .$backHour.':'.$back_minute.':00');
   
        $start_string = $actual_before->format('Y-m-d H:i:s');
       
        // dump($start_string);
      
        $end_string = $actual_next->format('Y-m-d H:i:s');
        // dump($end_string);
        $currentStreams = $this->model::whereBetween('start',[$start_string, $end_string])->where('user_id','=',$user->id)->distinct()->get();
        // dump($currentStreams);
        return $currentStreams;
    }

    public function getTimes($currentStreams,$userModel){
        $times = [];
        foreach ($currentStreams as $key => $currentStream) {
               
            $time = new Carbon($currentStream->start);
            $time->tz = $userModel->time_zone;
            
            array_push($times,$time->format('H')); 
        }
        return $times;
    }
    public function getNextStream($user){

        $this->setModel();
        $actual = Carbon::now();
        // $actual->addHour(1);
       
        $dates = $actual->format('Y-m-d');
        $hour_actual = $actual->format('H');
        $hour_next =$hour_actual + 1;
       
        $actual = new Carbon($dates.' ' .$hour_actual.':59:00');
        $actual_next = new Carbon($dates.' ' .$hour_next.':00:00');
      
        $start_string = $actual->format('Y-m-d H:i:s');
        // dump($start_string);
        $end_string = $actual_next->format('Y-m-d H:i:s');
        // dump($end_string);
        $currentStreams = $this->model::whereBetween('start',[$start_string, $end_string])->where('user_id','!=',$user->id)->distinct()->first();

        //  dump($currentStreams);
        return $currentStreams;
    }

    public function parseHoursToCountry($end,$time_zone = null){
        $hourDifference = 0;
        // dump('parseHoursToCountry-----------------------------------------');
        // dump($time_zone);
        // dd($end);
        
        // $start =  $end;
        // // dump($start);
        // $start->tz = $time_zone;
        // // dump($start);
        // $start_utc_country =  new Carbon($start->format('Y-m-d H:i'));
        // // dump($start_utc_country);
        // $utc =  $end;
       
        // $diff = $start_utc_country->diffInHours($utc,false);   
        // dd($diff);
        Log::debug('time_zone : ------------ ' . json_encode($time_zone));
        if(isset($time_zone)){
            
            $timezone1 = new DateTimeZone($time_zone);
            $timezone2 = new DateTimeZone('UTC');
            
            // Get the offsets in seconds for each timezone
            $offset1 = $timezone1->getOffset(new DateTime());
            $offset2 = $timezone2->getOffset(new DateTime());
            
            // Convert offsets to hours
            $hourDifference = abs(($offset1 - $offset2) / 3600);
            if($time_zone == 'Europe/Rome'){
                $hourDifference = $hourDifference  * -1;
            }
            if($time_zone == 'Europe/Madrid'){
                $hourDifference = $hourDifference  * -1;
            }
            if($time_zone == 'Europe/Brussels'){
                $hourDifference = $hourDifference  * -1;
            }
            if($time_zone == 'Europe/Berlin'){
                $hourDifference = $hourDifference  * -1;
            }
            if($time_zone == 'Atlantic/Canary'){
                $hourDifference = $hourDifference  * -1;
            }
        }
       
        
        // Log::debug('hourDifference------------------------***' . json_encode($hourDifference));
        return $hourDifference;
    }
    public function getSchedulerDayByUser($user,$date)
    {   
        // dump('day');
       
        // dump($date);
        $this->setModel();
        $dates = null;
        
        if(isset($user->time_zone) && $user->time_zone != '' ){
            $en = $this->setSunday();
            $hour_diff = $this->parseHoursToCountry($en->endOfWeek($date),$user->time_zone);
            if($date != 1){
                $start = $en->startOfWeek($date)->addHours($hour_diff)->format('Y-m-d H:00:00');
                $end = $en->startOfWeek($date)->addHour(23 + $hour_diff)->format('Y-m-d H:00:00');
            }
            else{
                $start = $en->startOfWeek($date)->addHours($hour_diff)->format('Y-m-d H:00:00');
                $end = $en->startOfWeek($date)->addHour(23 + $hour_diff)->format('Y-m-d H:00:00');
            }
            $dates = $this->model::whereBetween('start', [$start, $end])->where('user_id',$user->id)->orderBy('start', 'ASC')->get();
        }
       if($date == 3){
        // dump('start');
        // dump($start);
        // //dump('end');
        // dump($end);
       }
        
  
        
        // dump('hours');
        //  dump($dates);
        return $dates;

    }
    public function getSchedulerDayEndByUser($user,$date)
    {
        $hour_diff = null;
        $dates = [];
        if(isset($user->time_zone) && $user->time_zone != ''){
            $en = $this->setSunday();
            // dump('en');
            // dump($en);
        //  dump('endOfWeek--------------------------------');
        // dump($en->endOfWeek($date));
        
        $hour_diff = $this->parseHoursToCountry($en->endOfWeek($date),$user->time_zone);
        
        // if($date == 5){
        //      dump('hour_diff--------------------------------');
        // dump($hour_diff);
        // }
        
       
        $this->setModel();
        $dates = null;
        
        $start = $en->endOfWeek($date)->addHours(-23 + $hour_diff )->format('Y-m-d H:00:00');
        // dump('start--------------------------------');
        // dump($start);

        
        $end = $en->endOfWeek($date)->addHours($hour_diff);
        $end = $end->format('Y-m-d H:00:00');
        // dump('end--------------------------------');
        // dump($end);
        $dates = $this->model::whereBetween('start', [$start, $end])->where('user_id',$user->id)->get();
        // dump($hours);
        // dump('hours--------------');
        // dump($hours);
        }
        
        
        return $dates;

    }

    // public function getByUserId($user_id)
    // {
    //     $this->setModel();
    //     $schedule = $this->model::where('user_id', $user_id)->first();
    //     if ($schedule) {
    //         return $schedule;
    //     } else {
    //         return null;
    //     }
    // }

    public function getUSchedules()
    {
        $this->setModel();

        $schedules = $this->model::all()->toArray();

        if (count($schedules) > 0) {
            return $schedules;
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
            $schedule = new Schedule();
            $schedule->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : $userArray['user_id'];
            $schedule->start = isset($userArray['start']) ? $userArray['start'] : $userArray['start'];
            // $schedule->end = isset($userArray['end']) ? $userArray['end'] : $userArray['end'];
            $schedule->save();
            return $schedule->id;
        } catch (Error $e) {
            return false;
        }
    }

    public function bulkCreate($userArray)
    {
        $schedulesIds = [];
        try {
            foreach ($userArray as $key => $value) {
                $schedule = new Schedule();
                $schedule->user_id = isset($value['user_id']) ? $value['user_id'] : $value['user_id'];
                $schedule->start = isset($value['start']) ? $value['start'] : $value['start'];
                // $schedule->end = isset($userArray['end']) ? $userArray['end'] : $userArray['end'];
                $schedule->save();
                array_push($schedulesIds, $schedule->id);
            }

            
            return $schedulesIds;
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
            $schedule = Schedule::find($userArray['id']);
            $schedule->user_id = isset($userArray['user_id']) ? $userArray['user_id'] : $userArray['user_id'];
            $schedule->start = isset($userArray['start']) ? $userArray['start'] : $userArray['start'];
            // $schedule->end = isset($userArray['end']) ? $userArray['end'] : $userArray['end'];
            $schedule->update();
            return $schedule->id;
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
