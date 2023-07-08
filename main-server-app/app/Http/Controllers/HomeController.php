<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\Services\VultrApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
    function store(Request $request) 
    {
        if($request->min_server) Cache::forever('min_server', $request->min_server);
        if($request->error_page_link) Cache::forever('error_page_link', $request->error_page_link);
        return back()->with('status', 'Settings updated');
    }
    function create() 
    {
        $new = (new VultrApi)->create_instance();
        $new = $new['instance'];
        Server::query()->create([
            'server_id' => $new['id'], 
            'server_ip' => $new['main_ip'],
            'status' => $new['status'],
        ]);
        return redirect()->route('home')->with('status', 'New server created!');
    }
    function delete(Server $server) 
    {
        $delete = (new VultrApi)->delete_instance($server->server_id);
        $server->update(['destroyed_at' => now(), 'ready' => false, 'status' => 'deleted']);
        return back();
    }
}
