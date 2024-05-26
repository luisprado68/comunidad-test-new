<?php

namespace App\Http\Controllers;

use App\Enums\RoleType;
use App\Models\Schedule;
use App\Services\ScheduleService;
use App\Services\UserService;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Carbon\CarbonTimeZone;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;

class ScheduleController extends Controller
{
    private $userService;
    private $scheduleService;
    public $scheduler;
    public $times;
    public $days;
    public $days_with_time;
    public $schedule_avaible = false;
    public $user;
    public $hours_per_day_user = 0;
    public $countDay = 0;
    public $day_status = [];
    public $monday = [];
    public $tuesday = [];
    public $wednesday = [];
    public $thursday = [];
    public $friday = [];
    public $saturday = [];
    public $active = false;
    public $data;
    public $bronce_time = 20;
    public $plata_time = 19;
    public $oro_time = 18;
    public $platino_time = 17;
    public $diamante_time = 16;
    public $esmeralda_time = 15;
    public $lider_time = 14;
    public $bronce;
    public $plata;
    public $oro;
    public $platino;
    public $diamante;
    public $esmeralda;
    public $lider;
    public $active_time_zone = false;


    public function __construct(UserService $userService, ScheduleService $scheduleService)
    {

        $this->userService = $userService;
        $this->scheduleService = $scheduleService;
    }
    public function index()
    {

        $times = [];
        if (session()->exists('user')) {
            $this->user = session('user');
            $user_model = $this->userService->userExistsActive($this->user['display_name'] . '@gmail.com', $this->user['id']);



            if ($user_model->status) {

                session(['status' => $user_model->status]);
            } else {
                session(['status' => 0]);
            }
            $this->bronce = $this->getTimeSchedule($user_model, $this->bronce_time);
            $this->plata = $this->getTimeSchedule($user_model, $this->plata_time);
            $this->oro = $this->getTimeSchedule($user_model, $this->oro_time);
            $this->platino = $this->getTimeSchedule($user_model, $this->platino_time);
            $this->diamante = $this->getTimeSchedule($user_model, $this->diamante_time);
            $this->esmeralda = $this->getTimeSchedule($user_model, $this->esmeralda_time);
            $this->lider = $this->getTimeSchedule($user_model, $this->lider_time);

            if (!empty($user_model)) {


                if (isset($user_model->time_zone)) {
                    $this->active_time_zone = true;
                    $schedules_by_user = $this->scheduleService->getScheduleorThisWeekByUser($user_model);
                    // dump($schedules_by_user);
                    $current_t = Carbon::now();
                    $current_t->tz = $user_model->time_zone;
                    $day = $current_t->format('l');
                    // dump($day);
                    if ($day == 'Sunday' || $user_model->range_id == 1 ||  $user_model->role_id != 2 || $user_model->calendar_enabled ) {

                        // dump($day);
                        if ($day == 'Sunday') {

                            $hour = intval($current_t->format('H'));
                            // dump($hour);
                            // dump($this->bronce);
                            // dump($this->plata);
                            // dump($this->oro);
                            // dump($this->platino);
                            ////validar los horarios segun rango

                            if (
                                $hour >= $this->bronce && $user_model->range_id == 1 ||
                                $hour >= $this->plata && $user_model->range_id == 2 ||
                                $hour >= $this->oro && $user_model->range_id == 3 ||
                                $hour >= $this->platino && $user_model->range_id == 4 ||
                                $hour >= $this->diamante && $user_model->range_id == 6 ||
                                $hour >= $this->esmeralda && $user_model->range_id == 5 ||
                                $hour >= $this->lider && $user_model->range_id == 8
                            ) {
                                // dump('paasaa');
                                if (!isset($schedules_by_user)) {
                                    $this->schedule_avaible = true;
                                } elseif ($user_model->range->hours_for_week > count($schedules_by_user)) {
                                    // dump($schedules_by_user);
                                    // dump($user_model->range->hours_for_week);
                                    $this->schedule_avaible = true;
                                }
                            }
                        } else {
                            // dump('eslseeee');
                            if (!isset($schedules_by_user)) {
                                $this->schedule_avaible = true;
                            } elseif ($user_model->range->hours_for_week > count($schedules_by_user)) {
                                $this->schedule_avaible = true;
                            }
                        }
                    }
                }
            }
            // dump($this->schedule_avaible);
            if ($this->schedule_avaible && $this->active_time_zone) {

                $this->times = [
                    0 => ['hour' => '00:00', 'duplicated' => false, 'disabled' => false],
                    1 => ['hour' => '01:00', 'duplicated' => false, 'disabled' => false],
                    2 => ['hour' => '02:00', 'duplicated' => false, 'disabled' => false],
                    3 => ['hour' => '03:00', 'duplicated' => false, 'disabled' => false],
                    4 => ['hour' => '04:00', 'duplicated' => false, 'disabled' => false],
                    5 => ['hour' => '05:00', 'duplicated' => false, 'disabled' => false],
                    6 => ['hour' => '06:00', 'duplicated' => false, 'disabled' => false],
                    7 => ['hour' => '07:00', 'duplicated' => false, 'disabled' => false],
                    8 => ['hour' => '08:00', 'duplicated' => false, 'disabled' => false],
                    9 => ['hour' => '09:00', 'duplicated' => false, 'disabled' => false],
                    10 => ['hour' => '10:00', 'duplicated' => false, 'disabled' => false],
                    11 => ['hour' => '11:00', 'duplicated' => false, 'disabled' => false],
                    12 => ['hour' => '12:00', 'duplicated' => false, 'disabled' => false],
                    13 => ['hour' => '13:00', 'duplicated' => false, 'disabled' => false],
                    14 => ['hour' => '14:00', 'duplicated' => false, 'disabled' => false],
                    15 => ['hour' => '15:00', 'duplicated' => false, 'disabled' => false],
                    16 => ['hour' => '16:00', 'duplicated' => false, 'disabled' => false],
                    17 => ['hour' => '17:00', 'duplicated' => false, 'disabled' => false],
                    18 => ['hour' => '18:00', 'duplicated' => false, 'disabled' => false],
                    19 => ['hour' => '19:00', 'duplicated' => false, 'disabled' => false],
                    20 => ['hour' => '20:00', 'duplicated' => false, 'disabled' => false],
                    21 => ['hour' => '21:00', 'duplicated' => false, 'disabled' => false],
                    22 => ['hour' => '22:00', 'duplicated' => false, 'disabled' => false],
                    23 => ['hour' => '23:00', 'duplicated' => false, 'disabled' => false],
                ];
                $this->days_with_time = [
                    "lunes" => ['day' => 1, 'times' => $this->times, 'status' => true],
                    "martes" => ['day' => 2, 'times' => $this->times, 'status' => true],
                    "miercoles" => ['day' => 3, 'times' => $this->times, 'status' => true],
                    "jueves" => ['day' => 4, 'times' => $this->times, 'status' => true],
                    "viernes" => ['day' => 5, 'times' => $this->times, 'status' => true],
                    "sabado" => ['day' => 6, 'times' => $this->times, 'status' => true],

                ];
                //TODO validar las horas por rango se deben mostrar los dias hasta llegar al limite
                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::MONDAY))) {
                    $this->days_with_time['lunes']['status'] = false;
                }
                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::TUESDAY))) {
                    $this->days_with_time['martes']['status'] = false;
                }
                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::WEDNESDAY))) {
                    $this->days_with_time['miercoles']['status'] = false;
                }
                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::THURSDAY))) {
                    $this->days_with_time['jueves']['status'] = false;
                }

                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayEndByUser($user_model, Carbon::FRIDAY))) {
                    $this->days_with_time['viernes']['status'] = false;
                }
                if ($user_model->range->hours_for_day <= count($this->scheduleService->getSchedulerDayEndByUser($user_model, Carbon::SATURDAY))) {
                    $this->days_with_time['sabado']['status'] = false;
                }
                // dump($this->days_with_time);
                $schedules = $this->scheduleService->getScheduleorThisWeek($user_model);
                $new_schedules = [];
                // dump($schedules);

                if (isset($schedules)) {
                    // Log::debug('schedules' . json_encode($schedules));
                    $www = $this->scheduleService->setSunday();

                    foreach ($this->days_with_time as $key_day => $day_with_time) {
                        foreach ($day_with_time['times'] as $key_time => $time) {

                            $new_start = $this->parseToCountry($www, $day_with_time['day'], $time['hour'], $user_model->time_zone);
                            // dump($new_start);
                            if (count($schedules->where('start', $new_start)->where('user_id', $user_model->id)) == 1) {
                                // dump($this->days_with_time[$key_day]['times'][$key_time]['disabled']);
                                $this->days_with_time[$key_day]['times'][$key_time]['disabled'] = true;
                            }
                            else{
                                $key = $this->validateScheduleTeam($schedules,$new_start,$user_model);
                                $this->days_with_time[$key_day]['times'][$key_time][$key] = true;
                            }
//                            elseif (count($schedules->where('start', $new_start)) == 1) {
//                                // dump($this->days_with_time[$key_day]['times'][$key_time]['duplicated']);
//                                $this->days_with_time[$key_day]['times'][$key_time]['duplicated'] = true;
//                            } elseif (count($schedules->where('start', $new_start)) == 2) {
//                                //remove item
//                                $this->days_with_time[$key_day]['times'][$key_time]['disabled'] = true;
//                            }
                        }
                    }
                    $this->days = [
                        "lunes",
                        "martes",
                        "miercoles",
                        "jueves",
                        "viernes",
                        "sabado",

                    ];
                    // dump($day);
                    $day_int = 0;
                    switch ($day) {
                        case 'Sunday':
                            // $day_int = 0;
                            break;
                        case 'Monday':
                            $day_int = 0;
                            break;
                        case 'Tuesday':
                            $day_int = 1;
                            break;
                        case 'Wednesday':
                            $day_int = 2;
                            break;
                        case 'Thursday':
                            $day_int = 3;
                            break;
                        case 'Friday':
                            $day_int = 4;
                            break;
                        case 'Saturday':
                            $day_int = 5;
                            break;
                    }
                    // dump($day_int);
                    //saca los dias inclusive el actual para agendar los bronces
                    $i = 0;
                    foreach ($this->days_with_time as $key => $value) {
                         // Log::debug('i----------------------------- ' . json_encode($i));
                        if ($day_int == $i) {
                            $this->days_with_time[$key]['status'] = true;
                            break;
                        }
                        $this->days_with_time[$key]['status'] = false;
                        // else{
                        //     $this->days_with_time[$key]['status'] = true;
                        // }
                        $i++;
                    }
                }
            }
            return view('schedule', [
                'times' => $this->times, 'days' => $this->days, 'days_with_time' => $this->days_with_time,
                'schedule_avaible' => $this->schedule_avaible, 'day_status' => $this->day_status,
                "user" => $user_model, 'times' => json_encode($times), 'bronce' => $this->bronce,
                'plata' => $this->plata, 'oro' => $this->oro, 'platino' => $this->platino,'active_time_zone' => $this->active_time_zone
            ]);
        } else {
            return redirect('/');
        }

        //validar fecha por team en el caso de encontrar disable o duplicated

    }
    public function validateScheduleTeam($schedules,$new_start,$user_model){
        $result = '';
        $schedulers = $schedules->where('start', $new_start);
        $cantidad = 0;
        if(count($schedulers)>0){

            foreach ($schedulers as $scheduler){
                if($scheduler->user->team->id == $user_model->team->id){
                    $cantidad = $cantidad +1;
                }
            }
            if($cantidad == 1){
                $result ='duplicated';
            }elseif ($cantidad == 2){
                $result = 'disabled';
            }
        }
        return $result;
    }


    public function getTimeSchedule($user, $time)
    {

        $new = Carbon::now();
        $new->hour($time);
        $new->tz = $user->time_zone;

        $exe = $new->hour;
        // dump($exe);
        return $exe;
    }
    public function updateScheduler(Request $request)
    {
        $hourDuplicated = false;

        $this->user = session('user');

        // if(env('APP_ENV') == 'local'){
        //     $user_model = $this->userService->getById(env('USER_TEST'));

        // }else{
        $user_model = $this->userService->getByIdandTwichId($this->user['id']);
        // }

        $schedules_by_user = $this->scheduleService->getScheduleorThisWeekByUser($user_model);
        if (!isset($schedules_by_user)) {
            $hours_for_week = 0;
        } else {
            $hours_for_week = count($schedules_by_user);
        }
        $scheduleNew = [];


        $this->data = $request->all();
        $this->data['status'] = 'ok';

        Log::debug("DAYS");
        Log::debug(json_encode($this->data));


        foreach ($this->data['days'] as $key => $value) {

            // Log::debug('value ' . json_encode(($value)));
            // Log::debug('cantidad de horarios: ' . json_encode(count(($value['horarios']))));

            if ($value['day'] == "1") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::MONDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }
            if ($value['day'] == "2") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::TUESDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }
            if ($value['day'] == "3") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::WEDNESDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }
            if ($value['day'] == "4") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayByUser($user_model, Carbon::THURSDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }
            if ($value['day'] == "5") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayEndByUser($user_model, Carbon::FRIDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }
            if ($value['day'] == "6") {

                if (count(($value['horarios'])) +  count($this->scheduleService->getSchedulerDayEndByUser($user_model, Carbon::SATURDAY)) > $user_model->range->hours_for_day) {
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Supera la hora diaria permitida';
                    break;
                }
            }

            $hours_for_week = count(($value['horarios'])) + $hours_for_week;
        }

        if ($this->data['status'] == 'ok' && $hours_for_week > $user_model->range->hours_for_week) {
            $this->data['status'] = 'error';
            $this->data['message'] = 'Supera las horas semanales permitidas';
        } elseif ($this->data['status'] == 'ok') {

            $date  = $this->scheduleService->setSunday();

            foreach ($this->data['days'] as $key => $value) {

                if (count(($value['horarios'])) > 0) {
                    foreach ($value['horarios'] as $key => $time) {

                        $scheduleNewItem['user_id'] = $user_model->id;
                        //$monday->tz = 'America/Argentina/Buenos_Aires';
                        // $start =  new Carbon($date->setDaysFromStartOfWeek($value['day'])->format('Y-m-d') . $time);
                        $new_start = $this->parseToCountry($date, $value['day'], $time, $user_model->time_zone);
                        Log::debug('new_start:---------------' . json_encode($new_start));
                        $scheduleNewItem['start'] = $new_start;



                        array_push($scheduleNew, $scheduleNewItem);
                    }
                }
            }

            //validar que no hay otro usuario ya agendado al mismo tiempo
            foreach ($scheduleNew as $key => $schedule_user) {
                // Log::debug('schedule_user:-----' . json_encode($schedule_user));

                $dates_other_users =  $this->scheduleService->validateNewScheduleByUser($schedule_user['start']);
                $currentStreams = [];
                foreach ($dates_other_users as $currentStream_same_group){
                    if(count($currentStreams) <=2){
                        if($currentStream_same_group->user->team->id == $user_model->team->id){
                            array_push($currentStreams,$currentStream_same_group);

                        }
                    }

                }
                if (count($currentStreams) == 2) {
                    $hourDuplicated = true;
                    $date_time_zone = new Carbon($schedule_user['start']);
                    $date_time_zone->tz = $user_model->time_zone;
                    $this->data['status'] = 'error';
                    $this->data['message'] = 'Otro streamer ya agendo esta fecha: ' . $date_time_zone->format('Y-m-d H:00:00');;
                    break;
                }
            }
            if ($hourDuplicated == false) {
                $ids = $this->scheduleService->bulkCreate($scheduleNew);
                // Log::debug('ids');
                // Log::debug(json_encode($ids));
            }
        }
        // Log::debug('data');
        // Log::debug(json_encode($this->data));
        return $this->data;
    }

    public function parseToCountry($date, $day_param, $time, $time_zone)
    {
        $hour_diff = $this->scheduleService->parseHoursToCountry($date->endOfWeek($day_param), $time_zone);
        //validar
        $utc =  new Carbon($date->format('Y-m-d') . $time);
        $utc->addHours($hour_diff);
        return $utc;
    }
    public function parseHoursToCountry_test($date,$time_zone = null){
        dump('parseHoursToCountry-----------------------------------------');
        dump($date);
        dump($time_zone);
        $en = $this->scheduleService->setSunday();
        dump('en');
        dump($en);
    //  dump('endOfWeek--------------------------------');
    // dump($en->endOfWeek($date));

    // $hour_diff = $this->parseHoursToCountry($en->endOfWeek($date),$user->time_zone);
    $timezone1 = new DateTimeZone('Europe/Rome');
    $timezone2 = new DateTimeZone('UTC');

    // Get the offsets in seconds for each timezone
    $offset1 = $timezone1->getOffset(new DateTime());
    $offset2 = $timezone2->getOffset(new DateTime());

    // Convert offsets to hours
    $hourDifference = abs(($offset2 - $offset1) / 3600);

    dump('hourDifference  -------------------------*** '.$hourDifference);
        dump('time_zone  -------------------------'.$time_zone);
        $new_time_user = Carbon::now($time_zone);
        dump('new_time_user  -------------------------'.$new_time_user);
        $utc_teste =  Carbon::now('UTC');
        dump('new_test now -------------------------'.$utc_teste);
        $new_diff = $utc_teste->diffInHours($new_time_user);
        dump('new_diff  -------------------------'.$new_diff);

        // $new_time_user->tz = $time_zone;

        dump('new_test  time_zone-------------------------'.$new_time_user);

        $start =  $en->endOfWeek($date);
        dump('inicio -------------------------'.$start);
        $start->tz = $time_zone;
        dump('inicio tz-------------------------'.$start);
        $start_utc_country =  new Carbon($start->format('Y-m-d H:i'));
        dump('start_utc_country tz-------------------------'.$start_utc_country);
        $utc =  $en->endOfWeek($date);

        $diff = $start_utc_country->diffInHours($utc,false);
        // dd($diff);


        return $diff;
    }
}
