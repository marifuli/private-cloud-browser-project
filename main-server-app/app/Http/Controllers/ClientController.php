<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ClientController extends Controller
{
    function index(Request $request) 
    {
        $server = Server::query()
            ->whereNull('used_at')
            ->whereNull('destroyed_at')
            ->where('ready', true)
            ->first();

        if($server) 
        {
            $ip = $server->server_ip;
            // dd(redirect(get_vnc_url($ip)));
            $server->update(['used_at' => now()]);
            $url = get_vnc_url($ip);
            if($request->url) $url .= '&url=' . urlencode($request->url);
            if($request->email) $url .= '&email=' . urlencode($request->email);
            return redirect($url);
        }
        return redirect(Cache::get('error_page_link') ?? 'https://google.com');
    }
}
