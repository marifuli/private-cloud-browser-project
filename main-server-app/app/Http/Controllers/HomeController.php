<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\VultrApi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('home', [
            'servers' => Server::query()->latest()->get(),
        ]);
    }
    function create() 
    {
        $new = (new VultrApi)->create_instance();
        if(!isset($new['instance'])) return abort(500, "Something went wrong!");
        $new = $new['instance'];
        Server::query()->create([
            'server_id' => $new['id'], 
            'server_ip' => $new['main_ip'],
            'status' => $new['status'],
        ]);
        return redirect(back())->with('status', 'New server created!');
    }
}
