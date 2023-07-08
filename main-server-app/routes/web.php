<?php

use App\Http\Controllers\ClientController;
use App\Services\VultrApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


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
Route::redirect('/', 'home');
Route::get('test', function() { 

});
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {

    Route::get('deploy', function() { 
        dump(shell_exec("cd /var/www/app && git pull && cd main-server-app && composer update && php artisan migrate"));
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/home', [App\Http\Controllers\HomeController::class, 'store'])->name('home.sore');
    Route::post('/create', [App\Http\Controllers\HomeController::class, 'create'])->name('create');
    Route::get('/delete/{server}', [App\Http\Controllers\HomeController::class, 'delete'])->name('delete');

});

Route::get('client', [ClientController::class, 'index'])->name('client');