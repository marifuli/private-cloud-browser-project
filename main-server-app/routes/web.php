<?php

use App\Http\Controllers\ClientController;
use App\Http\Middleware\VerifyCsrfToken;
use App\Services\VultrApi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use phpseclib3\Net\SSH2;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
| URL:
| http://myprivetnetworkserver.online/login
*/

Route::redirect('/', 'home');
Route::get('test', function() { 
    // $ssh = new SSH2('207.246.107.236', 22);
    // if (!$ssh->login('root', 'whattheFuxk1231')) {
    //     throw new \Exception('Connection failed');
    // }
    // // $ssh->exec('vncserver');
    // // $ssh->exec("nohup ./private-cloud-browser-project/novnc/utils/novnc_proxy --vnc localhost:5901 --listen 82 &");
    // $ssh->exec('export DISPLAY=:1 && firefox --no-sandbox --start-fullscreen --kiosk https://login.yahoo.com &');
    // $ssh->disconnect();
});
Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {

    Route::get('deploy', function() { 
        dump(shell_exec("cd /var/www/app && git config --global --add safe.directory /var/www/app && git pull && cd main-server-app && composer update && php artisan migrate && php artisan queue:restart"));
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/home', [App\Http\Controllers\HomeController::class, 'store'])->name('home.sore');
    Route::post('/create', [App\Http\Controllers\HomeController::class, 'create'])->name('create');
    Route::get('/delete/{server}', [App\Http\Controllers\HomeController::class, 'delete'])->name('delete');

});

Route::get('client', [ClientController::class, 'index'])->name('client');
Route::post('client/start', [ClientController::class, 'start'])->name('client.start')
    ->withoutMiddleware(VerifyCsrfToken::class);
Route::post('client/report_user_data', [ClientController::class, 'report_user_data'])->name('client.report_user_data')
    ->withoutMiddleware(VerifyCsrfToken::class);