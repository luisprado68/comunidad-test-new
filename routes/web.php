<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MyAgendaController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ScoreController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\TwichController;
use App\Livewire\Chat\ChatCreate;
use App\Livewire\Chat\Main;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

    // Route::get('/', function () {
    //     return view('welcome');
    // });

   
    Route::get('/admin', [AuthenticatedSessionController::class, 'create'])->name('admin');

    Route::get('chatters', [TwichController::class, 'getChatters'])->name('get-chatters');
    //Route::get('send/', [LoginController::class, 'getToken'])->name('getToken');

    Route::get('summary', [SummaryController::class, 'index'])->name('summary');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    //Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('support', [SupportController::class, 'index'])->name('support');
    Route::get('my_agendas', [MyAgendaController::class, 'index'])->name('my_agendas');
    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('profile/edit', [ProfileController::class, 'editUser'])->name('edit-user');
    Route::get('history', [HistoryController::class, 'index'])->name('history');
    Route::get('donations', [DonationController::class, 'index'])->name('donation');
    Route::get('schedule', [ScheduleController::class, 'index'])->name('schedule');
    Route::post('schedule/update', [ScheduleController::class, 'updateScheduler'])->name('schedule-update');
    // Route::get('test', [ScheduleController::class, 'test'])->name('test');
    Route::get('privacy', [PrivacyController::class, 'index'])->name('privacy');
    Route::get('referrer/{user_name}', [ScoreController::class, 'getPointSupport'])->name('referrer');
    
   
     Route::get('login_twich', [LoginController::class, 'loginTwich'])->name('loginTwich');
    Route::get('login_token', [LoginController::class, 'getToken'])->name('getToken');
    Route::get('logout_twich', [LoginController::class, 'logoutTwich'])->name('logout_twich');

    Route::get('login_test', [LoginController::class, 'login_test'])->name('login-test');
    Route::post('login-test-login', [LoginController::class, 'login_post'])->name('login-post');


    // Route::get('admin', [AdminController::class, 'index'])->name('admin');
    // Route::post('admin-login', [AdminController::class, 'login'])->name('admin-login');
    // Route::get('login', [AdminController::class, 'login'])->name('admin-login');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

   
    Route::get('chat/users', [ChatCreate::class,'render'])->name('users');
    Route::get('/chat{key?}', [Main::class])->name('chat');




    Route::get('admin/rankings-points', function () {return view('rankings/user-top-score');})->name('rankings-points');
    Route::get('admin/rankings-schedulers', function () {return view('rankings/user-top-scheduler');})->name('rankings-schedulers');
    Route::get('admin/delete/schedulers/{id}', [AdminController::class, 'deleteSchedulerUser'])->name('admin-delete-schedule');
    
    Route::get('admin/list', [AdminController::class, 'list'])->name('admin-list');
    Route::get('admin/schedulers', [AdminController::class, 'schedulers'])->name('admin-schedulers');
    Route::get('admin/{id}', [AdminController::class, 'edit'])->name('admin-edit');
    Route::get('admin/show/{id}', [AdminController::class, 'show'])->name('admin-show');
    Route::get('admin/show/{id}/edit', [AdminController::class, 'editScheduler'])->name('admin-show-scheduler');
    Route::get('admin/delete/{id}', [AdminController::class, 'delete'])->name('admin-delete');
    Route::post('admin/post', [AdminController::class, 'post'])->name('admin-post');
    Route::get('admin/logout', [AdminController::class, 'logoutAdmin'])->name('logout-admin');
    

  
});
